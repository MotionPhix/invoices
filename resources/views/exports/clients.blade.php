<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Clients Export</title>
  <style>
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px; border: 1px solid #ddd; }
    th { background-color: #f5f5f5; }
  </style>
</head>
<body>
<h1>Clients List</h1>
<table>
  <thead>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Company</th>
    <th>Status</th>
  </tr>
  </thead>
  <tbody>
  @foreach($clients as $client)
    <tr>
      <td>{{ $client->name }}</td>
      <td>{{ $client->email }}</td>
      <td>{{ $client->phone }}</td>
      <td>{{ $client->company_name }}</td>
      <td>{{ $client->status }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body>
</html>
