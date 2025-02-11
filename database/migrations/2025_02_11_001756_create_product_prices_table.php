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
    Schema::create('product_prices', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained()->cascadeOnDelete();
      $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
      $table->decimal('price', 15, 2);
      $table->integer('minimum_quantity')->default(1);
      $table->string('currency')->default('USD');
      $table->timestamp('valid_from')->nullable();
      $table->timestamp('valid_until')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_prices');
  }
};
