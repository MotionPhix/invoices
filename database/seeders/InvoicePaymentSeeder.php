<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoicePaymentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $paymentMethods = ['credit_card', 'bank_transfer', 'cheque', 'paypal'];

    // Get all invoices or generate random invoice ids
    $invoices = Invoice::all();

    foreach ($invoices as $invoice) {

      if (fake()->boolean(30)) {

        // Choose a random payment method
        $paymentMethod = fake()->randomElement($paymentMethods);

        // Base data
        $paymentData = [
          'invoice_id' => $invoice->id,
          'amount' => fake()->randomFloat(2, 100, 1000),
          'payment_date' => fake()->date(),
          'payment_method' => $paymentMethod,
          'status' => 'completed',  // default status for this example
        ];

        // Add specific fields depending on the payment method
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

        // Create the payment
        InvoicePayment::create($paymentData);
      }
    }
  }
}
