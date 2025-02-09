<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientActivityController extends Controller
{
  public function index(Client $client)
  {
    $activities = $client->activities()
      ->with('causer')
      ->latest()
      ->paginate(10);

    return Inertia::render('Clients/Activity', [
      'client' => $client,
      'activities' => $activities,
    ]);
  }
}
