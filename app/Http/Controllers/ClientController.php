<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
  public function index()
  {
    $clients = Client::query()
      ->orderBy('name')
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('Clients/Index', [
      'clients' => $clients,
    ]);
  }

  public function create()
  {
    return Inertia::render('Clients/Create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:clients,email',
      'phone' => 'nullable|string|max:20',
      'company_name' => 'nullable|string|max:255',
      'vat_number' => 'nullable|string|max:50',
      'billing_address' => 'nullable|string',
      'shipping_address' => 'nullable|string',
      'country' => 'nullable|string|max:100',
      'city' => 'nullable|string|max:100',
      'postal_code' => 'nullable|string|max:20',
      'notes' => 'nullable|string',
      'currency' => 'nullable|string|size:3',
    ]);

    Client::create($validated);

    return redirect()->route('clients.index')
      ->with('message', 'Client created successfully');
  }

  public function edit(Client $client)
  {
    return Inertia::render('Clients/Edit', [
      'client' => $client,
    ]);
  }

  public function update(Request $request, Client $client)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:clients,email,' . $client->id,
      'phone' => 'nullable|string|max:20',
      'company_name' => 'nullable|string|max:255',
      'vat_number' => 'nullable|string|max:50',
      'billing_address' => 'nullable|string',
      'shipping_address' => 'nullable|string',
      'country' => 'nullable|string|max:100',
      'city' => 'nullable|string|max:100',
      'postal_code' => 'nullable|string|max:20',
      'notes' => 'nullable|string',
      'currency' => 'nullable|string|size:3',
    ]);

    $client->update($validated);

    return redirect()->route('clients.index')
      ->with('message', 'Client updated successfully');
  }

  public function destroy(Client $client)
  {
    $client->delete();

    return redirect()->route('clients.index')
      ->with('message', 'Client deleted successfully');
  }
}
