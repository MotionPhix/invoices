<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Writer;
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
    'Shipping Address',
    'Country',
    'City',
    'Postal Code',
    'Status',
    'Currency'
  ];

  public function export(Request $request): StreamedResponse
  {
    // Start with base query
    $query = Client::query();

    // Apply filters from request
    if ($request->has('search')) {
      $search = $request->get('search');
      $query->where(function($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhere('company_name', 'like', "%{$search}%")
          ->orWhere('phone', 'like', "%{$search}%");
      });
    }

    if ($request->has('status')) {
      $query->where('status', $request->get('status'));
    }

    if ($request->has('sort')) {
      [$column, $direction] = explode(',', $request->get('sort'));
      $query->orderBy($column, $direction);
    }

    $clients = $query->get();

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="clients-'.date('Y-m-d').'.csv"',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0'
    ];

    $callback = function() use ($clients) {
      $handle = fopen('php://output', 'w');

      // Add headers
      fputcsv($handle, $this->csvHeaders);

      // Add data
      foreach ($clients as $client) {
        fputcsv($handle, [
          $client->name,
          $client->email,
          $client->phone,
          $client->company_name,
          $client->vat_number,
          $client->billing_address,
          $client->shipping_address,
          $client->country,
          $client->city,
          $client->postal_code,
          $client->status,
          $client->currency
        ]);
      }

      fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
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

      // Add sample data
      fputcsv($handle, [
        'John Doe',
        'john@example.com',
        '+1234567890',
        'Sample Company Ltd',
        'VAT123456',
        '123 Billing St',
        '456 Shipping Ave',
        'United States',
        'New York',
        '10001',
        'active',
        'USD'
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

    $records = $csv->getRecords();
    $importedCount = 0;
    $errors = [];

    foreach ($records as $offset => $record) {
      $validator = Validator::make($record, [
        'Name' => 'required|string|max:255',
        'Email' => 'required|email|unique:clients,email',
        'Phone' => 'nullable|string|max:20',
        'Company Name' => 'nullable|string|max:255',
        'VAT Number' => 'nullable|string|max:50',
        'Country' => 'nullable|string|max:100',
        'City' => 'nullable|string|max:100',
        'Postal Code' => 'nullable|string|max:20',
        'Currency' => 'nullable|string|size:3',
      ]);

      if ($validator->fails()) {
        $errors[] = [
          'row' => $offset + 2, // +2 because of 0-based index and header row
          'errors' => $validator->errors()->all()
        ];
        continue;
      }

      try {
        Client::create([
          'name' => $record['Name'],
          'email' => $record['Email'],
          'phone' => $record['Phone'] ?? null,
          'company_name' => $record['Company Name'] ?? null,
          'vat_number' => $record['VAT Number'] ?? null,
          'billing_address' => $record['Billing Address'] ?? null,
          'shipping_address' => $record['Shipping Address'] ?? null,
          'country' => $record['Country'] ?? null,
          'city' => $record['City'] ?? null,
          'postal_code' => $record['Postal Code'] ?? null,
          'status' => $record['Status'] ?? 'active',
          'currency' => $record['Currency'] ?? 'USD'
        ]);
        $importedCount++;
      } catch (\Exception $e) {
        $errors[] = [
          'row' => $offset + 2,
          'errors' => [$e->getMessage()]
        ];
      }
    }

    return response()->json([
      'message' => "Imported {$importedCount} clients successfully",
      'errors' => $errors
    ]);
  }
}
