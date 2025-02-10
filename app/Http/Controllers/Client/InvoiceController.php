<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
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
