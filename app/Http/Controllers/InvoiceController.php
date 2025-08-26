<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index(): \Inertia\Response
    {
        $invoices = Invoice::latest()->paginate(15);
        return Inertia::render('invoices/Index', [
            'invoices' => $invoices,
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('invoices/Create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $invoice = Invoice::create($request->all());
        return redirect()->route('owner.invoices.show', $invoice);
    }

    public function show(Invoice $invoice): \Inertia\Response
    {
        return Inertia::render('invoices/Show', [
            'invoice' => $invoice,
        ]);
    }

    public function edit(Invoice $invoice): \Inertia\Response
    {
        return Inertia::render('invoices/Edit', [
            'invoice' => $invoice,
        ]);
    }

    public function update(Request $request, Invoice $invoice): \Illuminate\Http\RedirectResponse
    {
        $invoice->update($request->all());
        return redirect()->route('owner.invoices.show', $invoice);
    }

    public function destroy(Invoice $invoice): \Illuminate\Http\RedirectResponse
    {
        $invoice->delete();
        return redirect()->route('owner.invoices.index');
    }
}
