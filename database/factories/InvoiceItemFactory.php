<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'invoice_id' => Invoice::factory(),
      'description' => fake('ZA')->sentence,
      'quantity' => fake()->numberBetween(1, 10),
      'unit_price' => fake()->randomFloat(2, 1000, 10000),
    ];
  }
}
