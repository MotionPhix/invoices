<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'type' => fake()->randomElement(['home', 'work']),

      'street' => fake('ZA')->streetAddress(),

      'city' => fake('ZA')->city(),

      'state' => fake('ZA')->streetName(),

      'country' => fake()->country(),
    ];
  }
}
