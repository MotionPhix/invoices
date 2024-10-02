<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\User;

class DashboardController extends Controller
{
  public function index()
  {
    $statistics = [
      'total_invoices' => Invoice::count(),
      'total_contacts' => Contact::count(),
      'total_companies' => Company::count(),
      'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
      'recently_paid_invoices' => Invoice::where('status', 'paid')->orderBy('updated_at', 'desc')->take(5)->get(),
      'outstanding_invoices' => Invoice::whereIn('status',['unpaid'])->sum('amount_due'),
      'overdue_invoices' => Invoice::where('due_date', '<', now())->where('status', 'unpaid')->count(),
    ];

    return view('dashboard', compact('statistics'));
  }
}
