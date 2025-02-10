<?php

namespace App\Http\Controllers\Client\Portal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
  public function index()
  {
    $client = auth()->guard('client')->user();

    return Inertia::render('ClientPortal/Dashboard', [
      'client' => $client,
      'recentInvoices' => $client->invoices()->latest()->take(5)->get(),
      'recentPayments' => $client->payments()->latest()->take(5)->get(),
    ]);
  }
}
