<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use function Spatie\LaravelPdf\Support\pdf;

class DownloadController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Invoice $invoice)
  {
    $invoice->load('items');

    $settings = \App\Models\Settings::first();

    return pdf()
      ->view('pdf.invoice', compact('invoice', 'settings'))
      ->name('invoice-2023-04-10.pdf')
      ->download();
  }
}
