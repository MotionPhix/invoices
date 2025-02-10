<?php

namespace App\Http\Controllers\Client;

use App\Exports\ClientExport;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientImportExportController extends Controller
{
  private array $csvHeaders = [
    'Name',
    'Email',
    'Phone',
    'Company Name',
    'VAT Number',
    'Billing Address',
    'Billing City',
    'Billing State',
    'Billing Postal Code',
    'Billing Country',
    'Use Billing for Shipping',
    'Shipping Address',
    'Shipping City',
    'Shipping State',
    'Shipping Postal Code',
    'Shipping Country',
    'Notes',
    'Currency',
    'Status'
  ];

  public function export(Request $request)
  {
    // Start with base query
    $format = $request->get('format', 'csv');
    $clients = $this->getFilteredClients($request);

    switch ($format) {
      case 'pdf':
        return $this->exportPdf($clients);
      case 'excel':
        return $this->exportExcel($clients);
      default:
        return $this->exportCsv($clients);
    }
  }

  private function getFilteredClients(Request $request)
  {
    $query = Client::query();

    if ($request->has('search')) {
      $search = $request->get('search');
      $query->where(function($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhere('company_name', 'like', "%{$search}%");
      });
    }

    if ($request->has('status')) {
      $query->where('status', $request->get('status'));
    }

    if ($request->has('selected')) {
      $selectedIds = explode(',', $request->get('selected'));
      $query->whereIn('id', $selectedIds);
    }

    return $query->get();
  }

  private function exportCsv($clients): StreamedResponse
  {
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="clients-'.date('Y-m-d').'.csv"',
    ];

    $callback = function() use ($clients) {
      $handle = fopen('php://output', 'w');

      fputcsv($handle, $this->csvHeaders);

      foreach ($clients as $client) {
        fputcsv($handle, [
          $client->name,
          $client->email,
          $client->phone,
          $client->company_name,
          $client->vat_number,
          $client->billing_address,
          $client->billing_city,
          $client->billing_state,
          $client->billing_postal_code,
          $client->billing_country,
          $client->use_billing_for_shipping ? 'Yes' : 'No',
          $client->shipping_address,
          $client->shipping_city,
          $client->shipping_state,
          $client->shipping_postal_code,
          $client->shipping_country,
          $client->notes,
          $client->currency,
          $client->status
        ]);
      }

      fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
  }

  private function exportExcel($clients)
  {
    return (new ClientExport($clients))->download('clients-'.date('Y-m-d').'.xlsx');
  }

  private function exportPdf($clients)
  {
    $pdf = PDF::loadView('exports.clients', ['clients' => $clients]);

    return $pdf->stream('clients-'.date('Y-m-d').'.pdf');
  }

  public function getSampleFile(): StreamedResponse
  {
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="clients-sample.csv"',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0'
    ];

    $callback = function() {
      $handle = fopen('php://output', 'w');

      // Add headers
      fputcsv($handle, $this->csvHeaders);

      // Add sample data with all fields matching our schema
      fputcsv($handle, [
        'John Doe', // Name
        'john@example.com', // Email
        '+265888123456', // Phone (Malawi format)
        'Sample Company Ltd', // Company Name
        'VAT123456', // VAT Number
        '123 Sample Street', // Billing Address
        'Lilongwe', // Billing City
        'Central Region', // Billing State
        'P.O. Box 1234', // Billing Postal Code
        'Malawi', // Billing Country
        'Yes', // Use Billing for Shipping
        '', // Shipping Address (empty because using billing)
        '', // Shipping City (empty because using billing)
        '', // Shipping State (empty because using billing)
        '', // Shipping Postal Code (empty because using billing)
        '', // Shipping Country (empty because using billing)
        'Sample client notes', // Notes
        'MWK', // Currency
        'active' // Status
      ]);

      // Add another sample with different shipping address
      fputcsv($handle, [
        'Jane Smith',
        'jane@example.com',
        '+265999123456',
        'Another Company Ltd',
        'VAT789012',
        '456 Billing Road', // Billing Address
        'Blantyre', // Billing City
        'Southern Region', // Billing State
        'P.O. Box 5678', // Billing Postal Code
        'Malawi', // Billing Country
        'No', // Use Billing for Shipping
        '789 Shipping Street', // Different Shipping Address
        'Zomba', // Different Shipping City
        'Southern Region', // Different Shipping State
        'P.O. Box 9012', // Different Shipping Postal Code
        'Malawi', // Shipping Country
        'Client with different shipping address',
        'MWK',
        'active'
      ]);

      fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
  }

  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|file|mimes:csv,txt|max:2048'
    ]);

    $file = $request->file('file');
    $csv = Reader::createFromPath($file->getPathname());
    $csv->setHeaderOffset(0);

    $records = LazyCollection::make(function () use ($csv) {
      foreach ($csv->getRecords() as $offset => $record) {
        yield $offset => $record;
      }
    });

    $batchSize = 100; // Process 100 records at a time
    $totalProcessed = 0;
    $errors = [];
    $successCount = 0;

    $records->chunk($batchSize)->each(function ($chunk) use (&$totalProcessed, &$errors, &$successCount) {
      foreach ($chunk as $offset => $record) {
        $totalProcessed++;

        $validator = Validator::make($record, [
          'Name' => 'required|string|max:255',
          'Email' => 'required|email|unique:clients,email',
          'Phone' => 'nullable|string|max:20',
          'Company Name' => 'nullable|string|max:255',
          'VAT Number' => 'nullable|string|max:50',
          'Currency' => 'required|string|size:3',
          'Status' => 'required|in:active,inactive',
          'Billing Address' => 'required|string',
          'Billing City' => 'required|string',
          'Billing State' => 'required|string',
          'Billing Country' => 'required|string',
          'Use Billing for Shipping' => 'required|in:Yes,No',
        ]);

        if ($validator->fails()) {
          $errors[] = [
            'row' => $offset + 2,
            'errors' => $validator->errors()->all()
          ];
          continue;
        }

        try {
          $useBillingForShipping = $record['Use Billing for Shipping'] === 'Yes';

          // Validate shipping address fields if not using billing address
          if (!$useBillingForShipping) {
            $shippingValidator = Validator::make($record, [
              'Shipping Address' => 'required|string',
              'Shipping City' => 'required|string',
              'Shipping State' => 'required|string',
              'Shipping Country' => 'required|string',
            ]);

            if ($shippingValidator->fails()) {
              $errors[] = [
                'row' => $offset + 2,
                'errors' => $shippingValidator->errors()->all()
              ];
              continue;
            }
          }

          // Prepare client data
          $clientData = [
            'name' => $record['Name'],
            'email' => $record['Email'],
            'phone' => $record['Phone'] ?? null,
            'company_name' => $record['Company Name'] ?? null,
            'vat_number' => $record['VAT Number'] ?? null,
            'billing_address' => $record['Billing Address'],
            'billing_city' => $record['Billing City'],
            'billing_state' => $record['Billing State'],
            'billing_postal_code' => $record['Billing Postal Code'] ?? null,
            'billing_country' => $record['Billing Country'],
            'use_billing_for_shipping' => $useBillingForShipping,
            'notes' => $record['Notes'] ?? null,
            'currency' => $record['Currency'] ?? 'MWK',
            'status' => $record['Status'] ?? 'active',
            'user_id' => auth()->id()
          ];

          // Set shipping address based on use_billing_for_shipping
          if ($useBillingForShipping) {
            $clientData += [
              'shipping_address' => $record['Billing Address'],
              'shipping_city' => $record['Billing City'],
              'shipping_state' => $record['Billing State'],
              'shipping_postal_code' => $record['Billing Postal Code'] ?? null,
              'shipping_country' => $record['Billing Country'],
            ];
          } else {
            $clientData += [
              'shipping_address' => $record['Shipping Address'],
              'shipping_city' => $record['Shipping City'],
              'shipping_state' => $record['Shipping State'],
              'shipping_postal_code' => $record['Shipping Postal Code'] ?? null,
              'shipping_country' => $record['Shipping Country'],
            ];
          }

          Client::create($clientData);
          $successCount++;
        } catch (\Exception $e) {
          $errors[] = [
            'row' => $offset + 2,
            'errors' => [$e->getMessage()]
          ];
        }
      }
    });

    return response()->json([
      'message' => "Imported {$successCount} clients successfully",
      'total_processed' => $totalProcessed,
      'success_count' => $successCount,
      'error_count' => count($errors),
      'errors' => $errors
    ]);
  }
}
