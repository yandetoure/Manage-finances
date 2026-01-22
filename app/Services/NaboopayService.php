<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NaboopayService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.naboopay.api_key');
        $this->baseUrl = config('services.naboopay.base_url');
    }

    /**
     * Create a checkout (deposit) transaction
     *
     * @param float $amount
     * @param int $userId
     * @param array $metadata
     * @return array
     */
    public function createCheckout($amount, $userId, $metadata = [])
    {
        try {
            // Get base URL and ensure it's HTTPS (Naboopay doesn't accept localhost)
            $baseUrl = config('app.url');
            if (str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
                // For local development, use a public HTTPS URL
                $baseUrl = 'https://manage.tond2679.odns.fr'; // Replace with your production domain
            }

            // Ensure URL uses HTTPS
            if (!str_starts_with($baseUrl, 'https://')) {
                $baseUrl = 'https://' . ltrim($baseUrl, 'http://');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/transaction/create-transaction', [
                        'method_of_payment' => ['WAVE', 'ORANGE_MONEY'],
                        'products' => [
                            [
                                'name' => 'Dépôt sur Manage',
                                'category' => 'deposit',
                                'amount' => (int) $amount,
                                'quantity' => 1,
                                'description' => 'Dépôt d\'argent sur le compte Naboopay - User #' . $userId,
                            ]
                        ],
                        'success_url' => $baseUrl . '/naboopay?status=success',
                        'error_url' => $baseUrl . '/naboopay?status=cancelled',
                    ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Naboopay Checkout Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Erreur lors de la création du checkout',
            ];
        } catch (\Exception $e) {
            Log::error('Naboopay Checkout Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Une erreur est survenue lors de la création du paiement',
            ];
        }
    }

    /**
     * Create a payout (withdrawal) transaction
     *
     * @param float $amount
     * @param string $phoneNumber
     * @param string $provider (wave, orange_money)
     * @param int $userId
     * @return array
     */
    public function createPayout($amount, $phoneNumber, $provider, $userId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/payouts', [
                        'amount' => $amount,
                        'phone' => $phoneNumber,
                        'payment_method' => $provider,
                        'description' => 'Retrait depuis Manage - User #' . $userId,
                    ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Naboopay Payout Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Erreur lors de la création du retrait',
            ];
        } catch (\Exception $e) {
            Log::error('Naboopay Payout Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Une erreur est survenue lors de la création du retrait',
            ];
        }
    }

    /**
     * Get transaction details
     *
     * @param string $transactionId
     * @return array
     */
    public function getTransaction($transactionId)
    {
        try {
            Log::info('Naboopay - Checking transaction status', ['transaction_id' => $transactionId]);

            // Try multiple possible endpoints for transaction status
            $endpoints = [
                '/transaction/' . $transactionId,
                '/transaction/get-transaction',
                '/transactions/' . $transactionId,
            ];

            foreach ($endpoints as $endpoint) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])->get($this->baseUrl . $endpoint);

                    if ($response->successful()) {
                        Log::info('Naboopay - Transaction status retrieved', [
                            'endpoint' => $endpoint,
                            'data' => $response->json()
                        ]);

                        return [
                            'success' => true,
                            'data' => $response->json(),
                        ];
                    }
                } catch (\Exception $e) {
                    Log::warning('Naboopay - Endpoint failed', [
                        'endpoint' => $endpoint,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            // If GET doesn't work, try POST
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/transaction/get-transaction', [
                        'order_id' => $transactionId,
                    ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Naboopay - Transaction not found', ['transaction_id' => $transactionId]);

            return [
                'success' => false,
                'error' => 'Transaction non trouvée',
            ];
        } catch (\Exception $e) {
            Log::error('Naboopay Get Transaction Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Erreur lors de la récupération de la transaction',
            ];
        }
    }

    /**
     * Verify webhook signature
     *
     * @param array $payload
     * @param string $signature
     * @return bool
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        $secret = config('services.naboopay.webhook_secret');

        if (!$secret) {
            return true; // Skip verification if no secret is configured
        }

        $computedSignature = hash_hmac('sha256', json_encode($payload), $secret);

        return hash_equals($computedSignature, $signature);
    }
}
