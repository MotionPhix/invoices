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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('number')->unique()->after('id');
            $table->foreignId('client_id')->constrained('clients')->after('number');
            $table->date('date')->after('client_id');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft')->after('date');
            $table->decimal('total', 15, 2)->default(0)->after('status');
            $table->string('currency', 3)->default('USD')->after('total');
            $table->text('notes')->nullable()->after('currency');

            // Recurring invoice fields
            $table->boolean('is_recurring')->default(false)->after('notes');
            $table->enum('recurring_frequency', ['daily', 'weekly', 'monthly', 'yearly'])->nullable()->after('is_recurring');
            $table->unsignedInteger('recurring_interval')->nullable()->after('recurring_frequency');
            $table->json('recurring_days')->nullable()->after('recurring_interval');
            $table->date('recurring_start_date')->nullable()->after('recurring_days');
            $table->date('recurring_end_date')->nullable()->after('recurring_start_date');
            $table->date('next_recurring_date')->nullable()->after('recurring_end_date');
            $table->date('last_recurring_date')->nullable()->after('next_recurring_date');
            $table->unsignedInteger('recurring_total_cycles')->nullable()->after('last_recurring_date');
            $table->unsignedInteger('recurring_completed_cycles')->default(0)->after('recurring_total_cycles');
            $table->timestamp('recurring_paused_at')->nullable()->after('recurring_completed_cycles');
            $table->timestamp('recurring_cancelled_at')->nullable()->after('recurring_paused_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'number',
                'client_id',
                'date',
                'status',
                'total',
                'currency',
                'notes',
                'is_recurring',
                'recurring_frequency',
                'recurring_interval',
                'recurring_days',
                'recurring_start_date',
                'recurring_end_date',
                'next_recurring_date',
                'last_recurring_date',
                'recurring_total_cycles',
                'recurring_completed_cycles',
                'recurring_paused_at',
                'recurring_cancelled_at',
            ]);
        });
    }
};
