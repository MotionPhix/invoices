<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessRecurringInvoices implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find all active recurring invoices due today or earlier
        $now = now();
        $recurringInvoices = Invoice::where('is_recurring', true)
            ->whereNull('recurring_cancelled_at')
            ->where(function ($q) use ($now) {
                $q->whereNull('recurring_end_date')
                  ->orWhere('recurring_end_date', '>=', $now->toDateString());
            })
            ->where('next_recurring_date', '<=', $now->toDateString())
            ->get();

        foreach ($recurringInvoices as $invoice) {
            DB::transaction(function () use ($invoice, $now) {
                // Clone invoice
                $newInvoice = $invoice->replicate([
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
                $newInvoice->number = $this->generateUniqueInvoiceNumber();
                $newInvoice->date = $now->toDateString();
                $newInvoice->status = 'draft';
                $newInvoice->is_recurring = false;
                $newInvoice->save();

                // Clone items
                foreach ($invoice->items as $item) {
                    $newItem = $item->replicate(['invoice_id']);
                    $newItem->invoice_id = $newInvoice->id;
                    $newItem->save();
                }

                // Update recurring invoice for next cycle
                $invoice->last_recurring_date = $now->toDateString();
                $invoice->recurring_completed_cycles = ($invoice->recurring_completed_cycles ?? 0) + 1;
                $invoice->next_recurring_date = $this->calculateNextDate($invoice, $now);
                $invoice->save();
            });
        }
    }

    /**
     * Generate a unique invoice number.
     */
    protected function generateUniqueInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . random_int(10000, 99999);
        } while (\App\Models\Invoice::where('number', $number)->exists());
        return $number;
    }

    /**
     * Calculate the next recurring date for an invoice.
     */
    protected function calculateNextDate(Invoice $invoice, Carbon $from): ?string
    {
        if (!$invoice->recurring_frequency || !$invoice->recurring_interval) {
            return null;
        }

        $date = $from->copy();
        switch ($invoice->recurring_frequency) {
            case 'daily':
                $date->addDays($invoice->recurring_interval);
                break;
            case 'weekly':
                $date->addWeeks($invoice->recurring_interval);
                break;
            case 'monthly':
                $date->addMonths($invoice->recurring_interval);
                break;
            case 'yearly':
                $date->addYears($invoice->recurring_interval);
                break;
        }
        return $date->toDateString();
    }
}
