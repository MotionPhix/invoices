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

    // Get all invoices
    $invoices = Invoice::all();

    foreach ($invoices as $invoice) {
      // Randomly decide whether this invoice will be fully or partially paid
      if (fake()->boolean(70)) {  // 70% chance that the invoice will have at least one payment
        $totalAmount = $invoice->totalAmount;  // Assuming this method returns the total amount of the invoice
        $paidAmount = 0;  // Start with 0 paid amount

        while ($paidAmount < $totalAmount) {
          // Randomly generate a payment amount: could be partial or full
          $paymentAmount = fake()->randomFloat(2, 0, $totalAmount - $paidAmount);

          // If the remaining amount is small, make this the final payment
          if ($totalAmount - $paidAmount < 100) {
            $paymentAmount = $totalAmount - $paidAmount;  // Make the final payment match the remaining due amount
          }

          // Create the payment
          InvoicePayment::create([
            'invoice_id' => $invoice->id,
            'amount' => $paymentAmount,
            'payment_date' => fake()->date(),
            'payment_method' => fake()->randomElement($paymentMethods),
            'status' => 'completed',
            // Add any other necessary fields depending on payment method
          ]);

          // Update the total paid amount
          $paidAmount += $paymentAmount;
        }

        // Update invoice status based on total paid
        if ($paidAmount >= $totalAmount) {
          $invoice->update(['status' => Invoice::STATUS_PAID]);
        } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
          $invoice->update(['status' => Invoice::STATUS_PARTIAL]);
        }
      }
    }
  }
}
