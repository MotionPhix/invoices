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
    Schema::create('settings', function (Blueprint $table) {
      $table->id();
      $table->string('invoice_prefix')->nullable();
      $table->string('invoice_suffix')->nullable();
      $table->integer('invoice_start_number')->default(1);
      $table->decimal('vat_rate', 5, 2)->default(16.50); // Default VAT rate of 16.5%
      $table->string('company_name')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('settings');
  }
};
