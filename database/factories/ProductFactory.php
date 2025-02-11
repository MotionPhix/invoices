<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
  protected $model = Product::class;

  public function definition(): array
  {
    $type = $this->faker->randomElement(['product', 'service']);
    $trackInventory = $type === 'product' ? $this->faker->boolean(70) : false;

    return [
      'name' => $this->faker->words(3, true),
      'description' => $this->faker->paragraph(),
      'price' => $this->faker->randomFloat(2, 10, 1000),
      'cost' => $this->faker->optional(0.7)->randomFloat(2, 5, 500),
      'type' => $type,
      'is_active' => $this->faker->boolean(90),
      'category_id' => Category::factory(),
      'unit' => $type === 'service' ?
        fake()->randomElement(['hour', 'day', 'project']) :
        fake()->randomElement(['piece', 'box', 'kg']),
      'track_inventory' => $trackInventory,
      'stock' => $trackInventory ? $this->faker->numberBetween(0, 100) : 0,
      'low_stock_threshold' => $trackInventory ? $this->faker->numberBetween(5, 20) : null,
    ];
  }

  public function service(): static
  {
    return $this->state(fn (array $attributes) => [
      'type' => 'service',
      'track_inventory' => false,
      'stock' => 0,
      'low_stock_threshold' => null,
      'unit' => $this->faker->randomElement(['hour', 'day', 'project']),
    ]);
  }

  public function product(): static
  {
    return $this->state(fn (array $attributes) => [
      'type' => 'product',
      'track_inventory' => true,
      'stock' => fake()->numberBetween(0, 100),
      'low_stock_threshold' => fake()->numberBetween(5, 20),
      'unit' => fake()->randomElement(['piece', 'box', 'kg']),
    ]);
  }
}
