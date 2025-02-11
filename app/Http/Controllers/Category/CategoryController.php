<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $query = Category::query()
      ->withCount('products')
      ->when($request->search, function ($query, $search) {
        $query->where('name', 'like', "%{$search}%");
      })
      ->when($request->sort, function ($query, $sort) {
        [$column, $direction] = explode(',', $sort);
        if ($column === 'products_count') {
          $query->orderBy('products_count', $direction);
        } else {
          $query->orderBy($column, $direction);
        }
      }, function ($query) {
        $query->orderBy('name', 'asc');
      });

    return Inertia::render('Products/Categories/Index', [
      'categories' => $query->paginate(10)->withQueryString(),
      'filters' => $request->only(['search', 'sort']),
      'sortOptions' => [
        ['value' => 'name,asc', 'label' => 'Name (A-Z)'],
        ['value' => 'name,desc', 'label' => 'Name (Z-A)'],
        ['value' => 'products_count,desc', 'label' => 'Most Products'],
        ['value' => 'products_count,asc', 'label' => 'Least Products'],
      ],
    ]);
  }

  public function create()
  {
    return Inertia::render('Products/Categories/Create');
  }

  public function store(CategoryRequest $request)
  {
    Category::create($request->validated());

    return redirect()->route('categories.index')
      ->with('success', 'Category created successfully.');
  }

  public function edit(Category $category)
  {
    return Inertia::render('Products/Categories/Edit', [
      'category' => $category,
    ]);
  }

  public function update(CategoryRequest $request, Category $category)
  {
    $category->update($request->validated());

    return redirect()->route('categories.index')
      ->with('success', 'Category updated successfully.');
  }

  public function destroy(Category $category)
  {
    $category->delete();

    return redirect()->route('categories.index')
      ->with('success', 'Category deleted successfully.');
  }
}
