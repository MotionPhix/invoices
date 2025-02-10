<?php

namespace App\Http\Controllers\Client;

use App\Exports\ClientExport;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
          $client->uuid,
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
        '123 Sample Street', // Shipping Address
        'Lilongwe', // Shipping City
        'Central Region', // Shipping State
        'P.O. Box 1234', // Shipping Postal Code
        'Malawi', // Shipping Country
        'Sample client notes', // Notes
        'MWK', // Currency
        'active' // Status
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
        'Currency' => 'required|string|size:3',
        'Status' => 'required|in:active,inactive',
      ]);

      if ($validator->fails()) {
        $errors[] = [
          'row' => $offset + 2,
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
          'billing_city' => $record['Billing City'] ?? null,
          'billing_state' => $record['Billing State'] ?? null,
          'billing_postal_code' => $record['Billing Postal Code'] ?? null,
          'billing_country' => $record['Billing Country'] ?? null,
          'use_billing_for_shipping' => ($record['Use Billing for Shipping'] ?? 'Yes') === 'Yes',
          'shipping_address' => $record['Shipping Address'] ?? null,
          'shipping_city' => $record['Shipping City'] ?? null,
          'shipping_state' => $record['Shipping State'] ?? null,
          'shipping_postal_code' => $record['Shipping Postal Code'] ?? null,
          'shipping_country' => $record['Shipping Country'] ?? null,
          'notes' => $record['Notes'] ?? null,
          'currency' => $record['Currency'] ?? 'MWK',
          'status' => $record['Status'] ?? 'active',
          'user_id' => auth()->id()
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
