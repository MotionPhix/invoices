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
    Schema::create('invoices', function (Blueprint $table) {
      $table->id();

      $table->string('invoice_number')->unique();

      $table->date('invoice_date');

      $table->text('description')->nullable();

      $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();

      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('invoices');
  }
};
