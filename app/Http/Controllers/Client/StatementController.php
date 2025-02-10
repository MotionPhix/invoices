<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class StatementController extends Controller
{
  public function index(Request $request)
  {
    $client = auth()->guard('client')->user();

    $startDate = $request->get('start_date')
      ? Carbon::parse($request->start_date)
      : Carbon::now()->startOfYear();

    $endDate = $request->get('end_date')
      ? Carbon::parse($request->end_date)
      : Carbon::now();

    $statement = $this->generateStatement($client, $startDate, $endDate);

    return Inertia::render('ClientPortal/Statements/Index', [
      'statement' => $statement,
      'filters' => [
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
      ]
    ]);
  }

  public function download(Request $request)
  {
    $client = auth()->guard('client')->user();

    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    $statement = $this->generateStatement($client, $startDate, $endDate);

    $pdf = PDF::loadView('pdfs.statement', [
      'client' => $client,
      'statement' => $statement,
      'start_date' => $startDate,
      'end_date' => $endDate
    ]);

    return $pdf->download("statement-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.pdf");
  }

  private function generateStatement($client, $startDate, $endDate)
  {
    $invoices = $client->invoices()
      ->whereBetween('date', [$startDate, $endDate])
      ->with('payments')
      ->get();

    $payments = $client->payments()
      ->whereBetween('date', [$startDate, $endDate])
      ->with('invoice')
      ->get();

    return [
      'start_date' => $startDate->format('Y-m-d'),
      'end_date' => $endDate->format('Y-m-d'),
      'opening_balance' => $this->calculateOpeningBalance($client, $startDate),
      'invoices' => $invoices,
      'payments' => $payments,
      'closing_balance' => $this->calculateClosingBalance($client, $endDate),
    ];
  }

  private function calculateOpeningBalance($client, $date)
  {
    $invoicesTotal = $client->invoices()
      ->where('date', '<', $date)
      ->sum('total');

    $paymentsTotal = $client->payments()
      ->where('date', '<', $date)
      ->sum('amount');

    return $invoicesTotal - $paymentsTotal;
  }

  private function calculateClosingBalance($client, $date)
  {
    $invoicesTotal = $client->invoices()
      ->where('date', '<=', $date)
      ->sum('total');

    $paymentsTotal = $client->payments()
      ->where('date', '<=', $date)
      ->sum('amount');

    return $invoicesTotal - $paymentsTotal;
  }
}
