<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
  protected $clients;

  public function __construct($clients)
  {
    $this->clients = $clients;
  }

  public function collection()
  {
    return $this->clients;
  }

  public function headings(): array
  {
    return [
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
  }

  public function map($client): array
  {
    return [
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
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      1 => [
        'font' => ['bold' => true],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['rgb' => 'F3F4F6']
        ]
      ],
    ];
  }
}
