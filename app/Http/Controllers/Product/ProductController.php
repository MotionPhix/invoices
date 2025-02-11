<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    $query = Product::query()
      ->with(['category', 'media'])
      ->when($request->search, function ($query, $search) {
        $query->where(function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        });
      })
      ->when($request->category, function ($query, $category) {
        $query->where('category_id', $category);
      })
      ->when($request->type, function ($query, $type) {
        $query->where('type', $type);
      })
      ->when($request->status, function ($query, $status) {
        $query->where('is_active', $status === 'active');
      })
      ->when($request->stock, function ($query, $stock) {
        if ($stock === 'low') {
          $query->whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('track_inventory', true);
        } elseif ($stock === 'out') {
          $query->where('stock', 0)
            ->where('track_inventory', true);
        }
      })
      ->when($request->sort, function ($query, $sort) {
        [$column, $direction] = explode(',', $sort);
        $query->orderBy($column, $direction);
      }, function ($query) {
        $query->orderBy('created_at', 'desc');
      });

    $statistics = [
      'total' => Product::count(),
      'active' => Product::where('is_active', true)->count(),
      'products' => Product::where('type', 'product')->count(),
      'services' => Product::where('type', 'service')->count(),
      'low_stock' => Product::whereColumn('stock', '<=', 'low_stock_threshold')
        ->where('track_inventory', true)
        ->count(),
      'categories' => Category::withCount('products')
        ->orderBy('products_count', 'desc')
        ->limit(5)
        ->get(),
    ];

    $filters = [
      'search' => $request->input('search', ''),
      'category' => $request->input('category', ''),
      'type' => $request->input('type', ''),
      'status' => $request->input('status', ''),
      'stock' => $request->input('stock', ''),
      'sort' => $request->input('sort', 'created_at,desc'),
    ];

    return Inertia::render('Product/Index', [
      'products' => $query->paginate(10)->withQueryString(),
      'filters' => $filters,
      'statistics' => $statistics,
      'categories' => Category::all(),
      'sortOptions' => [
        ['value' => 'name,asc', 'label' => 'Name (A-Z)'],
        ['value' => 'name,desc', 'label' => 'Name (Z-A)'],
        ['value' => 'created_at,desc', 'label' => 'Newest First'],
        ['value' => 'created_at,asc', 'label' => 'Oldest First'],
        ['value' => 'price,asc', 'label' => 'Price (Low to High)'],
        ['value' => 'price,desc', 'label' => 'Price (High to Low)'],
        ['value' => 'stock,asc', 'label' => 'Stock (Low to High)'],
        ['value' => 'stock,desc', 'label' => 'Stock (High to Low)'],
      ],
    ]);
  }

  public function create()
  {
    return Inertia::render('Product/Create', [
      'categories' => Category::all(),
      'units' => [
        'product' => ['piece', 'box', 'kg', 'meter', 'liter'],
        'service' => ['hour', 'day', 'project', 'session'],
      ],
    ]);
  }

  public function store(ProductRequest $request)
  {
    DB::beginTransaction();

    try {
      $product = Product::create($request->validated());

      if ($request->hasFile('image')) {
        $product->addMediaFromRequest('image')
          ->toMediaCollection('product-images');
      }

      // Create base price
      $product->prices()->create([
        'price' => $request->input('price'),
        'minimum_quantity' => 1,
        'currency' => $request->input('currency', 'USD'),
      ]);

      DB::commit();

      return redirect()->route('products.index')
        ->with('success', 'Product created successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Failed to create product.');
    }
  }

  public function edit(Product $product)
  {
    return Inertia::render('Product/Edit', [
      'product' => $product->load(['category', 'prices', 'media']),
      'categories' => Category::all(),
      'units' => [
        'product' => ['piece', 'box', 'kg', 'meter', 'liter'],
        'service' => ['hour', 'day', 'project', 'session'],
      ],
    ]);
  }

  public function update(ProductRequest $request, Product $product)
  {
    DB::beginTransaction();

    try {
      $product->update($request->validated());

      if ($request->hasFile('image')) {
        $product->clearMediaCollection('product-images');
        $product->addMediaFromRequest('image')
          ->toMediaCollection('product-images');
      }

      // Update base price
      $product->prices()
        ->where('minimum_quantity', 1)
        ->where('client_id', null)
        ->update(['price' => $request->input('price')]);

      DB::commit();

      return redirect()->route('products.index')
        ->with('success', 'Product updated successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Failed to update product.');
    }
  }

  public function destroy(Product $product)
  {
    $product->delete();

    return redirect()->route('products.index')
      ->with('success', 'Product deleted successfully.');
  }
}
