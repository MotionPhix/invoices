<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoicePayment>
 */
class InvoicePaymentFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $paymentMethods = ['credit_card', 'bank_transfer', 'cheque', 'paypal'];

    // Randomly pick a payment method
    $paymentMethod = fake()->randomElement($paymentMethods);

    // Generate random payment information based on the method
    $paymentData = [
      'payment_method' => $paymentMethod,
      'amount' => fake()->randomFloat(2, 100, 1000), // Random payment amount
      'payment_date' => fake()->date(),
      'status' => 'completed',
    ];

    switch ($paymentMethod) {
      case 'credit_card':
        $paymentData['card_type'] = fake()->randomElement(['Visa', 'MasterCard', 'AmEx']);
        $paymentData['last_four'] = fake()->randomNumber(4, true);
        $paymentData['authorization_code'] = strtoupper(fake()->bothify('??####'));
        break;

      case 'bank_transfer':
        $paymentData['bank_name'] = fake()->company;
        $paymentData['account_number'] = fake()->bankAccountNumber;
        $paymentData['routing_number'] = fake()->randomNumber(9, true);
        $paymentData['transaction_reference'] = strtoupper(fake()->bothify('TRX####'));
        break;

      case 'cheque':
        $paymentData['cheque_number'] = fake()->randomNumber(9, true);
        $paymentData['bank_name'] = fake()->company;
        $paymentData['cheque_date'] = fake()->date();
        $paymentData['cheque_status'] = fake()->randomElement(['pending', 'cleared', 'bounced']);
        break;

      case 'paypal':
        $paymentData['paypal_email'] = fake()->safeEmail;
        $paymentData['payer_id'] = strtoupper(fake()->bothify('PAYER####'));
        $paymentData['transaction_reference'] = strtoupper(fake()->bothify('PAYPALTX####'));
        break;
    }

    return $paymentData;
  }
}
