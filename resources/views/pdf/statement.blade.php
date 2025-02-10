<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Account Statement</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      line-height: 1.4;
      color: #333;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .client-info {
      margin-bottom: 30px;
    }
    .statement-period {
      margin-bottom: 20px;
      text-align: right;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 8px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #f8f9fa;
    }
    .amount {
      text-align: right;
    }
    .balance {
      text-align: right;
      font-weight: bold;
    }
    .summary {
      margin-top: 30px;
      border-top: 2px solid #333;
      padding-top: 20px;
    }
    .credit {
      color: #38a169;
    }
    .debit {
      color: #e53e3e;
    }
  </style>
</head>
<body>
<div class="header">
  <h1>Account Statement</h1>
</div>

<div class="client-info">
  <strong>{{ $client->name }}</strong><br>
  {{ $client->company_name }}<br>
  {{ $client->billing_address }}<br>
  {{ $client->billing_city }}, {{ $client->billing_state }}<br>
  {{ $client->billing_country }}
</div>

<div class="statement-period">
  <strong>Statement Period:</strong><br>
  {{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} -
  {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}
</div>

<table>
  <thead>
  <tr>
    <th>Date</th>
    <th>Description</th>
    <th class="amount">Amount</th>
    <th class="amount">Balance</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>{{ $start_date }}</td>
    <td>Opening Balance</td>
    <td class="amount">{{ number_format($statement['opening_balance'], 2) }}</td>
    <td class="balance">{{ number_format($statement['opening_balance'], 2) }}</td>
  </tr>

  @php
    $running_balance = $statement['opening_balance'];
  @endphp

  @foreach ($statement['invoices'] as $invoice)
    @php
      $running_balance += $invoice->total;
    @endphp
    <tr>
      <td>{{ $invoice->date->format('Y-m-d') }}</td>
      <td>Invoice #{{ $invoice->number }}</td>
      <td class="amount debit">{{ number_format($invoice->total, 2) }}</td>
      <td class="balance">{{ number_format($running_balance, 2) }}</td>
    </tr>
  @endforeach

  @foreach ($statement['payments'] as $payment)
    @php
      $running_balance -= $payment->amount;
    @endphp
    <tr>
      <td>{{ $payment->date->format('Y-m-d') }}</td>
      <td>Payment for Invoice #{{ $payment->invoice->number }}</td>
      <td class="amount credit">({{ number_format($payment->amount, 2) }})</td>
      <td class="balance">{{ number_format($running_balance, 2) }}</td>
    </tr>
  @endforeach
  </tbody>
</table>

<div class="summary">
  <table>
    <tr>
      <td><strong>Opening Balance:</strong></td>
      <td class="balance">{{ number_format($statement['opening_balance'], 2) }}</td>
    </tr>
    <tr>
      <td><strong>Total Invoiced:</strong></td>
      <td class="balance">{{ number_format($statement['invoices']->sum('total'), 2) }}</td>
    </tr>
    <tr>
      <td><strong>Total Payments:</strong></td>
      <td class="balance">{{ number_format($statement['payments']->sum('amount'), 2) }}</td>
    </tr>
    <tr>
      <td><strong>Closing Balance:</strong></td>
      <td class="balance">{{ number_format($statement['closing_balance'], 2) }}</td>
    </tr>
  </table>
</div>
</body>
</html>
