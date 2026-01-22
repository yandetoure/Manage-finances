<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NaboopayService;
use App\Models\NaboopayTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NaboopayController extends Controller
{
    protected $naboopayService;

    public function __construct(NaboopayService $naboopayService)
    {
        $this->naboopayService = $naboopayService;
    }

    /**
     * Display Naboopay management page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $transactions = NaboopayTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $status = $request->get('status');
        $message = null;

        if ($status === 'success') {
            $message = ['type' => 'success', 'text' => 'Paiement effectué avec succès ! Votre solde sera mis à jour dans quelques instants.'];
        } elseif ($status === 'cancelled') {
            $message = ['type' => 'warning', 'text' => 'Paiement annulé.'];
        }

        return view('mobile.naboopay.index', compact('user', 'transactions', 'message'));
    }

    /**
     * Create a checkout (deposit)
     */
    public function createCheckout(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $user = Auth::user();

        // Create checkout via Naboopay API
        $result = $this->naboopayService->createCheckout(
            $validated['amount'],
            $user->id
        );

        if (!$result['success']) {
            return back()->with('error', $result['error']);
        }

        // Save transaction in database
        $transaction = NaboopayTransaction::create([
            'user_id' => $user->id,
            'transaction_id' => $result['data']['id'] ?? null,
            'type' => 'checkout',
            'amount' => $validated['amount'],
            'currency' => 'XOF',
            'status' => 'pending',
            'metadata' => $result['data'],
        ]);

        // Redirect to Naboopay checkout page
        $checkoutUrl = $result['data']['checkout_url'] ?? $result['data']['url'] ?? null;

        if (!$checkoutUrl) {
            return back()->with('error', 'URL de paiement non disponible');
        }

        return redirect($checkoutUrl);
    }

    /**
     * Create a payout (withdrawal)
     */
    public function createPayout(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'phone_number' => 'required|string',
            'provider' => 'required|in:wave,orange_money',
        ]);

        $user = Auth::user();

        // Check if user has sufficient balance
        if ($user->naboopay_balance < $validated['amount']) {
            return back()->with('error', 'Solde insuffisant. Votre solde actuel est de ' . number_format($user->naboopay_balance, 0, ',', ' ') . ' XOF');
        }

        DB::beginTransaction();

        try {
            // Deduct from balance immediately
            $user->naboopay_balance -= $validated['amount'];
            $user->save();

            // Create payout via Naboopay API
            $result = $this->naboopayService->createPayout(
                $validated['amount'],
                $validated['phone_number'],
                $validated['provider'],
                $user->id
            );

            if (!$result['success']) {
                DB::rollBack();
                return back()->with('error', $result['error']);
            }

            // Save transaction in database
            $transaction = NaboopayTransaction::create([
                'user_id' => $user->id,
                'transaction_id' => $result['data']['id'] ?? null,
                'type' => 'payout',
                'amount' => $validated['amount'],
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => $validated['provider'],
                'phone_number' => $validated['phone_number'],
                'metadata' => $result['data'],
            ]);

            DB::commit();

            return back()->with('success', 'Retrait en cours de traitement. Vous recevrez l\'argent dans quelques instants.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payout Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Une erreur est survenue lors du retrait');
        }
    }

    /**
     * Handle Naboopay webhook
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('X-Naboopay-Signature');
        $payload = $request->all();

        if (!$this->naboopayService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Invalid Naboopay webhook signature');
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        Log::info('Naboopay Webhook Received', ['event' => $event, 'data' => $data]);

        try {
            switch ($event) {
                case 'transaction.completed':
                case 'checkout.completed':
                    $this->handleCheckoutCompleted($data);
                    break;

                case 'transaction.failed':
                case 'checkout.failed':
                    $this->handleCheckoutFailed($data);
                    break;

                case 'payout.completed':
                    $this->handlePayoutCompleted($data);
                    break;

                case 'payout.failed':
                    $this->handlePayoutFailed($data);
                    break;

                default:
                    Log::info('Unhandled Naboopay event', ['event' => $event]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Webhook Processing Error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle completed checkout
     */
    private function handleCheckoutCompleted($data)
    {
        $transactionId = $data['id'] ?? null;

        if (!$transactionId) {
            return;
        }

        $transaction = NaboopayTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            Log::warning('Transaction not found', ['transaction_id' => $transactionId]);
            return;
        }

        if ($transaction->status === 'completed') {
            return; // Already processed
        }

        DB::beginTransaction();

        try {
            // Update transaction status
            $transaction->update([
                'status' => 'completed',
                'payment_method' => $data['payment_method'] ?? null,
                'metadata' => $data,
            ]);

            // Add to user's Naboopay balance
            $user = $transaction->user;
            $user->naboopay_balance += $transaction->amount;
            $user->save();

            DB::commit();

            Log::info('Checkout completed', [
                'transaction_id' => $transactionId,
                'amount' => $transaction->amount,
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout completion error', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Handle failed checkout
     */
    private function handleCheckoutFailed($data)
    {
        $transactionId = $data['id'] ?? null;

        if (!$transactionId) {
            return;
        }

        $transaction = NaboopayTransaction::where('transaction_id', $transactionId)->first();

        if ($transaction) {
            $transaction->update([
                'status' => 'failed',
                'error_message' => $data['error'] ?? 'Paiement échoué',
                'metadata' => $data,
            ]);
        }
    }

    /**
     * Handle completed payout
     */
    private function handlePayoutCompleted($data)
    {
        $transactionId = $data['id'] ?? null;

        if (!$transactionId) {
            return;
        }

        $transaction = NaboopayTransaction::where('transaction_id', $transactionId)->first();

        if ($transaction) {
            $transaction->update([
                'status' => 'completed',
                'metadata' => $data,
            ]);

            Log::info('Payout completed', [
                'transaction_id' => $transactionId,
                'amount' => $transaction->amount,
            ]);
        }
    }

    /**
     * Handle failed payout
     */
    private function handlePayoutFailed($data)
    {
        $transactionId = $data['id'] ?? null;

        if (!$transactionId) {
            return;
        }

        $transaction = NaboopayTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            return;
        }

        DB::beginTransaction();

        try {
            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'error_message' => $data['error'] ?? 'Retrait échoué',
                'metadata' => $data,
            ]);

            // Refund the balance
            $user = $transaction->user;
            $user->naboopay_balance += $transaction->amount;
            $user->save();

            DB::commit();

            Log::info('Payout failed, balance refunded', [
                'transaction_id' => $transactionId,
                'amount' => $transaction->amount,
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payout failure handling error', ['message' => $e->getMessage()]);
        }
    }
}
