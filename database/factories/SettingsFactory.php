<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Settings>
 */
class SettingsFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'invoice_prefix' => 'INV-',
      'invoice_suffix' => '',
      'invoice_start_number' => fake()->randomNumber(3),
      'vat_rate' => 20.00,
      'company_name' => fake('ZA')->company(),
      'company_email' => fake('ZA')->companyEmail(),
      'company_phone' => fake('ZA')->phoneNumber(),
      'currency' => fake('ZA')->currencyCode(),
    ];
  }

  public function configure()
  {
    return $this->afterCreating(function (Settings $settings) {
      $settings->address()->create(Address::factory()->make()->toArray());
    });
  }
}
