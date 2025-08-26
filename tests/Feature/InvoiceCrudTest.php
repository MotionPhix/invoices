<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;

describe('Invoice CRUD', function () {
    it('can create an invoice with items', function () {
        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create(['client_id' => $client->id]);
        $item = InvoiceItem::factory()->create([
            'invoice_id' => $invoice->id,
            'description' => 'Test Product',
            'quantity' => 3,
            'price' => 50,
            'total' => 150,
        ]);
        expect($invoice->items)->toHaveCount(1);
        expect($invoice->items->first()->description)->toBe('Test Product');
    });

    it('can update invoice status', function () {
        $invoice = Invoice::factory()->create(['status' => 'draft']);
        $invoice->update(['status' => 'sent']);
        expect($invoice->fresh()->status)->toBe('sent');
    });

    it('can delete an invoice and its items', function () {
        $invoice = Invoice::factory()->create();
        $item = InvoiceItem::factory()->create(['invoice_id' => $invoice->id]);
        $invoice->delete();
        expect(Invoice::find($invoice->id))->toBeNull();
        expect(InvoiceItem::where('invoice_id', $invoice->id)->count())->toBe(0);
    });
});

describe('Payment CRUD', function () {
    it('can create a payment for an invoice', function () {
        $invoice = Invoice::factory()->create();
        $payment = Payment::factory()->create([
            'invoice_id' => $invoice->id,
            'amount' => 100,
            'date' => now()->toDateString(),
            'method' => 'credit_card',
        ]);
    expect($invoice->payments)->toHaveCount(1);
    expect((float) $invoice->payments->first()->amount)->toBe(100.0);
    });
});
