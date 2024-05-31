<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Phone>
 */
class PhoneFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'country_code' => fake()->countryCode(),
      'is_primary_phone' => fake()->randomElement([True, False]),
      'formatted' => fake()->e164PhoneNumber(),
      'number' => fake()->phoneNumber(),
      'type' => fake()->randomElement(['mobile', 'work', 'fax', 'home']),
    ];
  }
}
