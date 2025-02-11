<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  public function run(): void
  {
    // Create some base categories
    $categories = [
      'Software Development' => '#4f46e5',
      'Consulting' => '#06b6d4',
      'Hardware' => '#f59e0b',
      'Support' => '#10b981',
      'Training' => '#ec4899',
    ];

    foreach ($categories as $name => $color) {
      Category::factory()->create([
        'name' => $name,
        'color' => $color,
      ]);
    }

    // Create products with prices
    Category::all()->each(function ($category) {
      // Create 3-5 products per category
      Product::factory()
        ->count(fake()->numberBetween(3, 5))
        ->create(['category_id' => $category->id])
        ->each(function ($product) {
          // Create base price
          ProductPrice::factory()->create([
            'product_id' => $product->id,
            'minimum_quantity' => 1,
          ]);

          // Create bulk prices
          ProductPrice::factory()
            ->count(2)
            ->sequence(
              ['minimum_quantity' => 10],
              ['minimum_quantity' => 25],
            )
            ->create([
              'product_id' => $product->id,
            ]);
        });

      // Create 2-3 services per category
      Product::factory()
        ->service()
        ->count(fake()->numberBetween(2, 3))
        ->create(['category_id' => $category->id])
        ->each(function ($service) {
          ProductPrice::factory()->create([
            'product_id' => $service->id,
            'minimum_quantity' => 1,
          ]);
        });
    });
  }
}
