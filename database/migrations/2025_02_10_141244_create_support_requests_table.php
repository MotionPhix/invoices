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
    Schema::create('support_requests', function (Blueprint $table) {
      $table->id();
      $table->foreignId('client_id')->constrained()->cascadeOnDelete();
      $table->string('subject');
      $table->text('message');
      $table->enum('priority', ['low', 'medium', 'high']);
      $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
      $table->text('resolution')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('support_requests');
  }
};
