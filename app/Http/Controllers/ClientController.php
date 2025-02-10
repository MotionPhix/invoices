<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ClientController extends Controller
{
  public function index(Request $request)
  {
    $thirtyDaysAgo = now()->subDays(30);

    $recentActivity = Activity::query()
      ->where('log_name', 'client')
      ->where('created_at', '>=', $thirtyDaysAgo)
      ->select('event', DB::raw('count(*) as count'))
      ->groupBy('event')
      ->get()
      ->mapWithKeys(fn ($item) => [$item->event => $item->count])
      ->toArray();

    $statistics = [
      'total' => Client::count(),
      'active' => Client::where('status', 'active')->count(),
      'inactive' => Client::where('status', 'inactive')->count(),
      'new_this_month' => Client::whereMonth('created_at', now()->month)->count(),
      'recent_activity' => [
        'total' => array_sum($recentActivity),
        'created' => $recentActivity['created'] ?? 0,
        'updated' => $recentActivity['updated'] ?? 0,
        'deleted' => $recentActivity['deleted'] ?? 0,
        'restored' => $recentActivity['restored'] ?? 0,
      ],
      'top_countries' => Client::select('billing_country', DB::raw('count(*) as count'))
        ->whereNotNull('billing_country')
        ->groupBy('billing_country')
        ->orderByDesc('count')
        ->limit(5)
        ->get(),
    ];

    $query = Client::query()
      ->when($request->search, function ($query, $search) {
        $query->where(function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('company_name', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%");
        });
      })
      ->when($request->status, function ($query, $status) {
        $query->where('status', $status);
      })
      ->when($request->sort, function ($query, $sort) {
        [$column, $direction] = explode(',', $sort);
        $query->orderBy($column, $direction);
      }, function ($query) {
        $query->orderBy('created_at', 'desc');
      });

    return Inertia::render('Clients/Index', [
      'clients' => $query->paginate(10)->withQueryString(),
      'filters' => $request->only(['search', 'status', 'sort']),
      'statistics' => $statistics,
      'sortOptions' => [
        ['value' => 'name,asc', 'label' => 'Name (A-Z)'],
        ['value' => 'name,desc', 'label' => 'Name (Z-A)'],
        ['value' => 'created_at,desc', 'label' => 'Newest First'],
        ['value' => 'created_at,asc', 'label' => 'Oldest First'],
      ],
      'statusOptions' => [
        ['value' => 'active', 'label' => 'Active'],
        ['value' => 'inactive', 'label' => 'Inactive'],
      ],
    ]);
  }

  public function create()
  {
    return Inertia::render('Clients/Create');
  }

  public function store(ClientRequest $request)
  {
    $validated = $request->validated();

    Client::create($validated);

    return redirect()->route('clients.index')
      ->with('message', 'Client created successfully');
  }

  public function show(Client $client)
  {
    return inertia('Clients/Show', [
      'client' => $client->load('user'),
      'activities' => $client->activities()
        ->with('causer')
        ->latest()
        ->take(20)
        ->get()
        ->map(function ($activity) {
          return [
            'id' => $activity->id,
            'description' => $activity->description,
            'causer_name' => $activity->causer->name ?? 'System',
            'created_at' => $activity->created_at->diffForHumans(),
          ];
        }),
      'invoices' => $client->invoices()
        ->latest()
        ->take(10)
        ->get(),
      'documents' => $client->documents()
        ->latest()
        ->take(10)
        ->get(),
    ]);
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

  public function bulkAction(Request $request)
  {
    $request->validate([
      'action' => 'required|in:delete,status',
      'selected' => 'required|array',
      'selected.*' => 'exists:clients,id',
      'value' => 'required_if:action,status|in:active,inactive'
    ]);

    $clients = Client::whereIn('id', $request->selected);

    try {
      DB::beginTransaction();

      switch ($request->action) {
        case 'delete':
          // Store the client names for activity log
          $clientNames = $clients->pluck('name')->toArray();

          // Perform the delete
          $clients->delete();

          // Log the bulk delete action
          activity()
            ->withProperties([
              'clients' => $clientNames,
              'count' => count($request->selected)
            ])
            ->log('Bulk deleted clients');
          break;

        case 'status':
          // Store the client names and new status for activity log
          $clientNames = $clients->pluck('name')->toArray();

          // Update the status
          $clients->update(['status' => $request->value]);

          // Log the bulk status update
          activity()
            ->withProperties([
              'clients' => $clientNames,
              'status' => $request->value,
              'count' => count($request->selected)
            ])
            ->log('Bulk updated client status');
          break;
      }

      DB::commit();

      return back()->with([
        'message' => 'Bulk action completed successfully'
      ]);
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function restore($id)
  {
    $client = Client::onlyTrashed()->findOrFail($id);
    $client->restore();

    return redirect()->back()
      ->with('message', 'Client restored successfully');
  }

  public function forceDelete($id)
  {
    $client = Client::onlyTrashed()->findOrFail($id);
    $client->forceDelete();

    return redirect()->back()
      ->with('message', 'Client permanently deleted');
  }

  public function trashed()
  {
    $trashedClients = Client::onlyTrashed()
      ->when(request('search'), function ($query, $search) {
        $query->where(function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('company_name', 'like', "%{$search}%");
        });
      })
      ->latest('deleted_at')
      ->paginate(10)
      ->withQueryString();

    return Inertia::render('Clients/Trashed', [
      'clients' => $trashedClients,
      'filters' => request()->only(['search']),
    ]);
  }
}
