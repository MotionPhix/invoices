<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Email;
use App\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'first_name' => fake('ZA')->firstName(fake()->randomElement(['female', 'male'])),
      'last_name' => fake('ZA')->lastName(),
      'bio' => fake('ZA')->paragraph(),
      'job_title' => fake()->jobTitle(),
      'middle_name' => fake()->randomElement([fake('ZA')->firstNameFemale(), fake('ZA')->firstNameMale()]),
      'nickname' => fake('ZA')->userName()
    ];
  }

  public function configure()
  {
    return $this->afterCreating(function (Contact $contact) {
      $contact->phones()->create(Phone::factory(rand(1, 2))->make()->toArray());
      $contact->emails()->create(Email::factory(rand(1, 2))->make()->toArray());
    });
  }
}
