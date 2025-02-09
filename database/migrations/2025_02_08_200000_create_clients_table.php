<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('clients', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid')->index();
      $table->string('name');
      $table->string('email')->unique();
      $table->string('phone')->nullable();
      $table->string('company_name')->nullable();
      $table->string('vat_number')->nullable();

      // Billing Address
      $table->text('billing_address')->nullable();
      $table->string('billing_city')->nullable();
      $table->string('billing_state')->nullable();
      $table->string('billing_postal_code')->nullable();
      $table->string('billing_country')->nullable();

      // Shipping Address
      $table->boolean('use_billing_for_shipping')->default(true);
      $table->text('shipping_address')->nullable();
      $table->string('shipping_city')->nullable();
      $table->string('shipping_state')->nullable();
      $table->string('shipping_postal_code')->nullable();
      $table->string('shipping_country')->nullable();

      // pin it to a user
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

      $table->text('notes')->nullable();
      $table->string('currency')->default('MWK');
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('clients');
  }
};
