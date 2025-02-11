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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->string('sku')->unique();
      $table->text('description')->nullable();
      $table->decimal('price', 15, 2);
      $table->decimal('cost', 15, 2)->nullable();
      $table->enum('type', ['product', 'service'])->default('product');
      $table->boolean('is_active')->default(true);
      $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
      $table->string('unit')->default('piece'); // piece, hour, day, etc.
      $table->boolean('track_inventory')->default(false);
      $table->integer('stock')->default(0);
      $table->integer('low_stock_threshold')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
