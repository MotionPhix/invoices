<?php

namespace App\Http\Controllers\Client\Portal;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentController extends Controller
{
  public function index(Request $request)
  {
    $client = auth()->guard('client')->user();

    $payments = $client->payments()
      ->with('invoice')
      ->latest()
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('ClientPortal/Payments/Index', [
      'payments' => $payments
    ]);
  }

  public function create(Request $request, Invoice $invoice)
  {
    $this->authorize('view', $invoice);

    if ($invoice->status === 'paid') {
      return redirect()->route('client-portal.invoices.show', $invoice)
        ->with('error', 'This invoice has already been paid.');
    }

    // Generate a unique transaction reference
    $transactionRef = 'INV-' . Str::random(10);

    // Create PayChangu payment request
    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . config('services.paychangu.secret_key'),
      'Accept' => 'application/json',
    ])->post(config('services.paychangu.api_url') . '/payment-requests', [
      'amount' => $invoice->total,
      'currency' => 'MWK',
      'reference' => $transactionRef,
      'email' => auth()->guard('client')->user()->email,
      'callback_url' => route('client-portal.payments.callback'),
      'return_url' => route('client-portal.payments.complete'),
      'metadata' => [
        'invoice_id' => $invoice->id,
        'client_id' => auth()->guard('client')->id()
      ]
    ]);

    if (!$response->successful()) {
      return back()->with('error', 'Unable to initialize payment. Please try again.');
    }

    $paymentData = $response->json();

    // Store payment request details in session
    session(['payment_request' => [
      'reference' => $transactionRef,
      'invoice_id' => $invoice->id,
      'amount' => $invoice->total
    ]]);

    return Inertia::render('ClientPortal/Payments/Create', [
      'invoice' => $invoice,
      'paymentUrl' => $paymentData['payment_url'],
      'reference' => $transactionRef
    ]);
  }

  public function callback(Request $request)
  {
    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . config('services.paychangu.secret_key'),
    ])->get(config('services.paychangu.api_url') . '/transactions/' . $request->reference);

    if (!$response->successful()) {
      return response()->json(['error' => 'Unable to verify payment'], 400);
    }

    $transactionData = $response->json();

    if ($transactionData['status'] === 'completed') {
      $paymentRequest = session('payment_request');

      if (!$paymentRequest) {
        return response()->json(['error' => 'Invalid payment session'], 400);
      }

      $invoice = Invoice::findOrFail($paymentRequest['invoice_id']);

      // Create payment record
      $payment = Payment::create([
        'invoice_id' => $invoice->id,
        'client_id' => auth()->guard('client')->id(),
        'amount' => $paymentRequest['amount'],
        'payment_method' => 'paychangu',
        'transaction_id' => $request->reference,
        'status' => 'completed',
        'date' => now(),
      ]);

      // Update invoice status if fully paid
      if ($invoice->payments()->sum('amount') >= $invoice->total) {
        $invoice->update(['status' => 'paid']);
      }

      // Clear payment request from session
      session()->forget('payment_request');

      return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Payment not completed'], 400);
  }

  public function complete(Request $request)
  {
    if ($request->status === 'success') {
      return redirect()->route('client-portal.invoices.index')
        ->with('success', 'Payment processed successfully.');
    }

    return redirect()->route('client-portal.invoices.index')
      ->with('error', 'Payment failed or was cancelled.');
  }
}
