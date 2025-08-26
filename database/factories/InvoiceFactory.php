<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numerify('INV-#####'),
            'client_id' => fn() => \App\Models\Client::factory()->create()->id,
            'date' => $this->faker->date(),
            'status' => 'draft',
            'total' => 0,
            'currency' => 'USD',
            'notes' => $this->faker->optional()->sentence(),
            'is_recurring' => false,
            'recurring_frequency' => null,
            'recurring_interval' => null,
            'recurring_days' => null,
            'recurring_start_date' => null,
            'recurring_end_date' => null,
            'next_recurring_date' => null,
            'last_recurring_date' => null,
            'recurring_total_cycles' => null,
            'recurring_completed_cycles' => 0,
            'recurring_paused_at' => null,
            'recurring_cancelled_at' => null,
        ];
    }
}
