<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake('ZA')->company(),
      'slogan' => fake('ZA')->sentence(5),
      'url' => fake('ZA')->url(),
    ];
  }

  public function configure()
  {
    return $this->afterCreating(function (Company $company) {
      $company->address()->create(Address::factory()->make()->toArray());
    });
  }
}
