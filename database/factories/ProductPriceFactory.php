<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPriceFactory extends Factory
{
  protected $model = ProductPrice::class;

  public function definition(): array
  {
    return [
      'product_id' => Product::factory(),
      'price' => fake()->randomFloat(2, 10, 1000),
      'minimum_quantity' => fake()->randomElement([1, 5, 10, 25, 50, 100]),
      'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
      'valid_from' => fake()->optional()->dateTimeBetween('-1 month', '+1 month'),
      'valid_until' => fake()->optional()->dateTimeBetween('+1 month', '+6 months'),
    ];
  }

  public function forClient(Client $client = null): static
  {
    return $this->state(fn (array $attributes) => [
      'client_id' => $client ?? Client::factory(),
    ]);
  }
}
