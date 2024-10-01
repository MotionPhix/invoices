<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('invoice_payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
      $table->decimal('amount', 10, 2); // payment amount
      $table->date('payment_date');
      $table->string('payment_method')->nullable(); // e.g., 'credit card', 'bank transfer'
      $table->string('transaction_reference')->nullable(); // Optional: for tracking external payments
      $table->string('status')->default('pending');
      // Bank Transfer fields
      $table->string('bank_name')->nullable();
      $table->string('account_number')->nullable();
      $table->string('routing_number')->nullable();
      // Credit Card fields
      $table->string('card_type')->nullable();
      $table->string('last_four')->nullable();
      $table->string('authorization_code')->nullable();
      // PayPal fields
      $table->string('paypal_email')->nullable();
      $table->string('payer_id')->nullable();
      $table->timestamps();
      // Cheque-specific fields
      $table->string('cheque_number')->nullable();
      $table->date('cheque_date')->nullable();
      $table->string('cheque_status')->default('pending'); // e.g., 'pending', 'cleared', 'bounced'
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('invoice_payments');
  }
};
