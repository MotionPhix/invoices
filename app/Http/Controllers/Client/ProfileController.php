<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{
  public function edit()
  {
    return Inertia::render('Portal/Profile/Edit', [
      'client' => auth()->guard('client')->user()
    ]);
  }

  public function update(Request $request)
  {
    $client = auth()->guard('client')->user();

    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', Rule::unique('clients')->ignore($client->id)],
      'phone' => ['nullable', 'string', 'max:20'],
      'company_name' => ['nullable', 'string', 'max:255'],
      'vat_number' => ['nullable', 'string', 'max:50'],
      'billing_address' => ['required', 'string'],
      'billing_city' => ['required', 'string'],
      'billing_state' => ['required', 'string'],
      'billing_postal_code' => ['nullable', 'string'],
      'billing_country' => ['required', 'string'],
      'use_billing_for_shipping' => ['required', 'boolean'],
      'shipping_address' => [Rule::requiredIf(!$request->use_billing_for_shipping), 'string'],
      'shipping_city' => [Rule::requiredIf(!$request->use_billing_for_shipping), 'string'],
      'shipping_state' => [Rule::requiredIf(!$request->use_billing_for_shipping), 'string'],
      'shipping_postal_code' => ['nullable', 'string'],
      'shipping_country' => [Rule::requiredIf(!$request->use_billing_for_shipping), 'string'],
    ]);

    if ($validated['use_billing_for_shipping']) {
      $validated['shipping_address'] = $validated['billing_address'];
      $validated['shipping_city'] = $validated['billing_city'];
      $validated['shipping_state'] = $validated['billing_state'];
      $validated['shipping_postal_code'] = $validated['billing_postal_code'];
      $validated['shipping_country'] = $validated['billing_country'];
    }

    $client->update($validated);

    return back()->with('success', 'Profile updated successfully.');
  }
}
