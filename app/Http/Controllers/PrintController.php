<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class PrintController extends Controller
{

  public function print(Invoice $invoice)
  {
    $invoice->load('items');

    $settings = \App\Models\Settings::first();

    return view('pdf.invoice', compact('invoice', 'settings'));
  }

}
