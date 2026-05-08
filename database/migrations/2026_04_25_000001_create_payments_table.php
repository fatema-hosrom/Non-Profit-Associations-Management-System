<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('donation_id')->constrained('donations')->cascadeOnDelete();
                $table->decimal('amount', 15, 2);
                $table->string('currency', 10)->default('SAR');
                $table->string('status', 20)->default('pending');
                $table->string('payment_method', 50)->default('card');
                $table->string('transaction_reference')->nullable();
                $table->string('card_brand')->nullable();
                $table->string('card_last4', 4)->nullable();
                $table->string('response_message')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
