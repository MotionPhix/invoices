<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientImportExportController extends Controller
{
  public function export(): StreamedResponse
  {
    $clients = Client::all();

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="clients-'.date('Y-m-d').'.csv"',
    ];

    $callback = function() use ($clients) {
      $handle = fopen('php://output', 'w');

      // Add headers
      fputcsv($handle, [
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
      ]);

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
