<?php

namespace Database\Factories;

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
      'invoice_start_number' => 1,
      'vat_rate' => 20.00,
      'company_name' => fake('ZA')->company(),
      'company_email' => fake('ZA')->companyEmail(),
      'company_phone' => fake('ZA')->phoneNumber(),
    ];
  }
}
