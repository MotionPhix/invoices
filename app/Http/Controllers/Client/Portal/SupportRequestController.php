<?php

namespace App\Http\Controllers\Client\Portal;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportRequestController extends Controller
{
  public function index()
  {
    $requests = auth()->guard('client')->user()
      ->supportRequests()
      ->latest()
      ->paginate(10);

    return Inertia::render('ClientPortal/Support/Index', [
      'requests' => $requests
    ]);
  }

  public function create()
  {
    return Inertia::render('ClientPortal/Support/Create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'subject' => 'required|string|max:255',
      'message' => 'required|string',
      'priority' => 'required|in:low,medium,high'
    ]);

    $supportRequest = auth()->guard('client')->user()
      ->supportRequests()
      ->create($validated);

    return redirect()
      ->route('client-portal.support.show', $supportRequest)
      ->with('success', 'Support request submitted successfully.');
  }

  public function show(SupportRequest $supportRequest)
  {
    $this->authorize('view', $supportRequest);

    return Inertia::render('ClientPortal/Support/Show', [
      'supportRequest' => $supportRequest
    ]);
  }
}
