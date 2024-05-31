<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;

class SettingsController extends Controller
{

  public function index()
  {

    $settings = Settings::first();

    return view('settings', compact('settings'));

  }

  public function update(Request $request)
  {
    $validated = $request->validate([
      'invoice_prefix' => 'required|string|max:5',
      'invoice_suffix' => 'nullable|string|max:5',
      'invoice_number_length' => 'required|integer|min:3',
      'invoice_start_number' => 'required|integer|min:1',
      'currency' => 'nullable|string|max:3',
      'vat_rate' => 'required|numeric|min:0|max:100',
      'company_name' => 'required|string|max:255',
      'company_email' => 'required|email|max:255',
      'company_phone' => 'nullable|string|max:20',
    ]);

    $settings = Settings::first();

    $settings->update($validated);

    Toast::message('Settings updated successfully.')->autoDismiss(5);

    return redirect()->back();
  }

}
