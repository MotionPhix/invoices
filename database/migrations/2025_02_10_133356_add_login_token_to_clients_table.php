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
    Schema::table('clients', function (Blueprint $table) {
      $table->string('login_token')->nullable();
      $table->timestamp('login_token_expires_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('clients', function (Blueprint $table) {
      $table->dropColumn(['login_token', 'login_token_expires_at']);
    });
  }
};
