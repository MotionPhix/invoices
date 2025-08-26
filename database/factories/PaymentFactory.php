<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_id' => null, // Set in test
            'date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'cash']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
