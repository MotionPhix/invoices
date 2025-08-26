<?php

namespace App\Http\Controllers\Client\Portal;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller {
  public function create()
  {
    // Fetch products and clients for selection
    $products = \App\Models\Product::query()->where('is_active', true)->get(['id', 'name', 'price']);
    $clients = \App\Models\Client::query()->get(['id', 'name']);
    return Inertia::render('Portal/Invoices/Create', [
      'products' => $products,
      'clients' => $clients,
    ]);
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'client_id' => 'required|exists:clients,id',
      'items' => 'required|array|min:1',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1',
      'items.*.price' => 'required|numeric|min:0',
      'notes' => 'nullable|string',
    ]);

    $invoice = Invoice::create([
      'client_id' => $data['client_id'],
      'notes' => $data['notes'] ?? null,
      // Add other invoice fields as needed
    ]);

    foreach ($data['items'] as $item) {
      $invoice->items()->create([
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
        'price' => $item['price'],
      ]);
    }

    return redirect()->route('invoices.show', $invoice);
  }

  public function edit(Invoice $invoice)
  {
    $this->authorize('update', $invoice);
    $invoice->load('items');
    $products = \App\Models\Product::query()->where('is_active', true)->get(['id', 'name', 'price']);
    $clients = \App\Models\Client::query()->get(['id', 'name']);
    return Inertia::render('Portal/Invoices/Edit', [
      'invoice' => $invoice,
      'products' => $products,
      'clients' => $clients,
    ]);
  }

  public function update(Request $request, Invoice $invoice)
  {
    $this->authorize('update', $invoice);
    $data = $request->validate([
      'client_id' => 'required|exists:clients,id',
      'items' => 'required|array|min:1',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1',
      'items.*.price' => 'required|numeric|min:0',
      'notes' => 'nullable|string',
    ]);

    $invoice->update([
      'client_id' => $data['client_id'],
      'notes' => $data['notes'] ?? null,
    ]);

    $invoice->items()->delete();
    foreach ($data['items'] as $item) {
      $invoice->items()->create([
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
        'price' => $item['price'],
      ]);
    }

    return redirect()->route('invoices.show', $invoice);
  }
  
  public function index(Request $request)
  {
    $client = auth()->guard('client')->user();

    $invoices = $client->invoices()
      ->with('payments')
      ->when($request->search, function ($query, $search) {
        $query->where('number', 'like', "%{$search}%");
      })
      ->latest()
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('Portal/Invoices/Index', [
      'invoices' => $invoices
    ]);
  }

  public function show(Invoice $invoice)
  {
    $this->authorize('view', $invoice);

    $invoice->load('items', 'payments');

    return Inertia::render('Portal/Invoices/Show', [
      'invoice' => $invoice
    ]);
  }

  public function download(Invoice $invoice)
  {
    $this->authorize('view', $invoice);

    // Generate PDF using your preferred method (e.g., DomPDF)
    $pdf = PDF::loadView('pdfs.invoice', ['invoice' => $invoice]);

    return $pdf->download("invoice-{$invoice->number}.pdf");
  }
}
