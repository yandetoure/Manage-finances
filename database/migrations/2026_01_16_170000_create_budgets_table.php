<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category'); // Category name (Alimentation, Transport, etc.)
            $table->decimal('amount', 10, 2); // Budget limit amount
            $table->string('period')->default('monthly'); // monthly, weekly, yearly
            $table->integer('month'); // 1-12
            $table->integer('year'); // e.g., 2024
            $table->timestamps();

            // Unique constraint: one budget per category per month per user
            $table->unique(['user_id', 'category', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
