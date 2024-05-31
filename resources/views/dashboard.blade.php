<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <div class="container mx-auto p-4">

        <!-- Statistics Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

          <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Total Invoices</h2>
            <p class="text-3xl">{{ $statistics['total_invoices'] }}</p>
          </div>

          <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Total Contacts</h2>
            <p class="text-3xl">{{ $statistics['total_contacts'] }}</p>
          </div>

          <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Total Companies</h2>
            <p class="text-3xl">{{ $statistics['total_companies'] }}</p>
          </div>

          <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Total Users</h2>
            <p class="text-3xl">{{ $statistics['total_users'] }}</p>
          </div>

          <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Total Revenue</h2>
            <p class="text-3xl">${{ number_format($statistics['total_revenue'], 2) }}</p>
          </div>

          <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Outstanding Invoices</h2>
            <p class="text-3xl">{{ $statistics['outstanding_invoices'] }}</p>
          </div>

        </div>

        <!-- Invoices Section -->
        <div class="mb-8">

          <h2 class="text-xl font-semibold mb-4">Recent Invoices</h2>

          <table class="min-w-full bg-white">

            <thead>

              <tr>

                <th class="py-2">Invoice #</th>

                <th class="py-2">Company</th>

                <th class="py-2">Total Amount</th>

                <th class="py-2">Status</th>

                <th class="py-2">Date</th>

              </tr>

            </thead>

            <tbody>

              @foreach($invoices as $invoice)

                <tr>

                  <td class="py-2">{{ $invoice->id }}</td>

                  <td class="py-2">{{ $invoice->contact->company->name }}</td>

                  <td class="py-2">${{ number_format($invoice->total_amount, 2) }}</td>

                  <td class="py-2">{{ $invoice->status }}</td>

                  <td class="py-2">{{ $invoice->created_at->format('Y-m-d') }}</td>

                </tr>

              @endforeach

            </tbody>

          </table>

        </div>

        <!-- Contacts Section -->
        <div class="mb-8">

          <h2 class="text-xl font-semibold mb-4">Recent Contacts</h2>

          <table class="min-w-full bg-white">

            <thead>

              <tr>
                <th class="py-2">Name</th>
                <th class="py-2">Email</th>
                <th class="py-2">Phone</th>
                <th class="py-2">Company</th>
              </tr>

            </thead>

            <tbody>

              @foreach($contacts as $contact)

                <tr>

                  <td class="py-2">{{ $contact->name }}</td>

                  <td class="py-2">{{ $contact->email }}</td>

                  <td class="py-2">{{ $contact->phone }}</td>

                  <td class="py-2">{{ $contact->company->name }}</td>

                </tr>

              @endforeach

            </tbody>

          </table>

        </div>

        <!-- Companies Section -->
        <div class="mb-8">

          <h2 class="text-xl font-semibold mb-4">Companies</h2>

          <table class="min-w-full bg-white">

            <thead>

              <tr>
                <th class="py-2">Name</th>
                <th class="py-2">Email</th>
                <th class="py-2">Phone</th>
              </tr>

            </thead>

            <tbody>

              @foreach($companies as $company)

                <tr>

                  <td class="py-2">{{ $company->name }}</td>

                  <td class="py-2">{{ $company->email }}</td>

                  <td class="py-2">{{ $company->phone }}</td>

                </tr>

              @endforeach

            </tbody>

          </table>

        </div>

        <!-- Users Section -->
        <div class="mb-8">

          <h2 class="text-xl font-semibold mb-4">Users</h2>

          <table class="min-w-full bg-white">

            <thead>

            <tr>
              <th class="py-2">Name</th>
              <th class="py-2">Email</th>
              <th class="py-2">Role</th>
            </tr>

            </thead>

            <tbody>

              @foreach($users as $user)
                <tr>
                  <td class="py-2">{{ $user->name }}</td>
                  <td class="py-2">{{ $user->email }}</td>
                  <td class="py-2">{{ $user->role }}</td>
                </tr>
              @endforeach

            </tbody>

          </table>

        </div>

      </div>

    </div>

  </div>

</x-app-layout>
