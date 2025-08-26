<?php

namespace Database\Factories;

use App\Models\SupportRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportRequestFactory extends Factory
{
    protected $model = SupportRequest::class;

    public function definition(): array
    {
        return [
            'client_id' => fn() => \App\Models\Client::factory()->create()->id,
            'subject' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['open', 'closed', 'pending']),
            'resolution' => $this->faker->optional()->sentence(),
        ];
    }
}
