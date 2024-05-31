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
    // Gather data for the dashboard
    $invoices = Invoice::with('items', 'contact.company')->limit(10)->get();
    $contacts = Contact::limit(5)->get();
    $companies = Company::all();
    $users = User::all();

    // Example statistics
    $statistics = [
      'total_invoices' => $invoices->count(),
      'total_contacts' => $contacts->count(),
      'total_companies' => $companies->count(),
      'total_users' => $users->count(),
      'total_revenue' => $invoices->sum('total_amount'),
      'outstanding_invoices' => $invoices->where('status', 'outstanding')->count(),
    ];

    return view('dashboard', compact('invoices', 'contacts', 'companies', 'users', 'statistics'));
  }
}
