<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Invoice;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;

class InvoiceController extends Controller
{
  public function index()
  {

    $invoices = Invoice::get();

    return view('invoices.index', compact('invoices'));

  }

  public function create()
  {
    $invoice = new Invoice(['status' => 'draft']);
    $invoice->items = [['description' => 'Sample item', 'quantity' => 1, 'unit_price' => 0]];

    return view('invoices.form', compact('invoice'));
  }

  public function store(Request $request)
  {
    $contact = Contact::inRandomOrder()->first();

    $request->merge(["contact_id" => $contact->id]);

    $validated = $request->validate([
      'invoice_date' => 'required|date',
      'description' => 'nullable|string',
      'contact_id' => 'required|exists:contacts,id',
      'items' => 'required|array',
      'items.*.description' => 'required|string',
      'items.*.quantity' => 'required|integer',
      'items.*.unit_price' => 'required|numeric',
    ]);

    $invoice = Invoice::create($validated);

    foreach ($validated['items'] as $item) {
      $invoice->items()->create($item);
    }

    return redirect()->route('invoices.show', $invoice);

  }

  public function show(Invoice $invoice)
  {
    $invoice->load('items');

    $settings = \App\Models\Settings::first();

    return view('invoices.show', compact('invoice', 'settings'));
  }

  public function edit(Invoice $invoice)
  {
    if (! in_array($invoice->status, [Invoice::STATUS_DRAFT, Invoice::STATUS_PARTIAL])) {

      Toast::message('Only draft or partially paid invoices can be edited.')->autoDismiss(5);

      return redirect()->back();

    }

    $invoice->load('items');

    return view('invoices.form', compact('invoice'));
  }

  public function update(Request $request, Invoice $invoice)
  {
    $validated = $request->validate([
      'invoice_date' => 'required|date',
      'currency' => 'nullable|string|in:' . implode(',', \App\Models\Settings::CURRENCIES),
      'status' => 'string|in:' . implode(',', array_column(\App\Models\Invoice::STATUSES, 'value')),
      'description' => 'nullable|string',
      'items' => 'required|array',
      'items.*.description' => 'required|string',
      'items.*.quantity' => 'required|integer',
      'items.*.unit_price' => 'required|numeric',
    ]);

    $invoice->update($validated);

    $invoice->items()->delete();

    foreach ($validated['items'] as $item) {

      $invoice->items()->create($item);

    }

    Toast::message('Invoices was updated successfully.')->autoDismiss(5);

    return redirect()->back();
  }

  public function destroy(Invoice $invoice)
  {

    $invoice->delete();

    return redirect()->route('invoices.index');

  }
}
