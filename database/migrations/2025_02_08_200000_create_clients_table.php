<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('clients', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->unique();
      $table->string('phone')->nullable();
      $table->string('company_name')->nullable();
      $table->string('vat_number')->nullable();
      $table->text('billing_address')->nullable();
      $table->text('shipping_address')->nullable();
      $table->string('country')->nullable();
      $table->string('city')->nullable();
      $table->string('postal_code')->nullable();
      $table->text('notes')->nullable();
      $table->string('currency')->default('MWK');
      $table->string('status')->default('active');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('clients');
  }
};
