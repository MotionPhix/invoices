<?php

use App\Jobs\ProcessRecurringInvoices;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

describe('Recurring Invoice Processing', function () {
    it('creates a new invoice and items for due recurring invoices', function () {
        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create([
            'client_id' => $client->id,
            'is_recurring' => true,
            'recurring_frequency' => 'monthly',
            'recurring_interval' => 1,
            'next_recurring_date' => now()->toDateString(),
            'recurring_completed_cycles' => 0,
        ]);
        $item = InvoiceItem::factory()->create([
            'invoice_id' => $invoice->id,
            'description' => 'Test Service',
            'quantity' => 2,
            'price' => 100,
            'total' => 200,
        ]);

        (new ProcessRecurringInvoices())->handle();

        $newInvoice = Invoice::where('client_id', $client->id)
            ->where('is_recurring', false)
            ->where('date', now()->toDateString())
            ->first();
        expect($newInvoice)->not->toBeNull();
        expect($newInvoice->items)->toHaveCount(1);
        expect($newInvoice->items->first()->description)->toBe('Test Service');
        $invoice->refresh();
        expect($invoice->recurring_completed_cycles)->toBe(1);
        expect($invoice->last_recurring_date)->toBe(now()->toDateString());
        expect($invoice->next_recurring_date)->not->toBeNull();
    });
});
