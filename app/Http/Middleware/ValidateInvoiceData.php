<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateInvoiceData
{
  /**
   * Handle an incoming request.
   *
   * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $request->validate([
      'invoice_number' => 'required|unique:invoices',
      'invoice_date' => 'required|date',
      'amount' => 'required|numeric',
      'items' => 'required|array',
      'items.*.description' => 'required|string',
      'items.*.quantity' => 'required|integer',
      'items.*.unit_price' => 'required|numeric',
    ]);

    return $next($request);
  }
}
