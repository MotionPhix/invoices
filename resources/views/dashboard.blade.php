<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  @php
    $currentHour = \Carbon\Carbon::now()->format('H');

    if ($currentHour < 12) {
        $greeting = 'Good morning';
    } elseif ($currentHour < 18) {
        $greeting = 'Good afternoon';
    } else {
        $greeting = 'Good evening';
    }
  @endphp

  <div class="py-12">

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

      <div class="container p-4 mx-auto">

        <div class="flex flex-wrap items-center m0bgt wn51l">
          <div>
            <h1 class="text-2xl font-bold dark:text-neutral-200">
              {{ $greeting }}, {{ auth()->user()->name }}.
            </h1>
            <p class="mb-6 text-gray-500 dark:text-neutral-400">
              Here's what's happening with your invoices today.
            </p>
          </div>
        </div>

        <!-- Statistics Section -->
        <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-3">

          <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm text-gray-500">Total invoices</span>
              <x-tabler-file-invoice class="text-gray-400 size-6" />
            </div>

            <div class="text-3xl font-bold">
              {{ $statistics['total_invoices'] }}
            </div>

            <div class="text-sm text-gray-500">21k orders</div>
            <div class="text-green-500">↗ 12.5%</div>
          </div>

          <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm text-gray-500">Total contacts</span>
              <x-tabler-users class="text-gray-400 size-6" />
            </div>

            <div class="text-3xl font-bold">{{ $statistics['total_contacts'] }}</div>
            <div class="text-sm text-gray-500">21k orders</div>
            <div class="text-green-500">↗ 12.5%</div>
          </div>

          <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm text-gray-500">Total companies</span>
              <x-tabler-building class="text-gray-400 size-6" />
            </div>

            <div class="text-3xl font-bold">{{ $statistics['total_companies'] }}</div>
            <div class="text-sm text-gray-500">5k orders</div>
            <div class="text-green-500">↗ 4.3%</div>
          </div>

          <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm text-gray-500">Outstanding balance</span>
              <x-tabler-currency-rupee class="text-gray-400 size-6" />
            </div>

            <div class="mb-2 text-3xl font-bold">
              {{ $statistics['outstanding_invoices'] }}
            </div>

            <div class="text-sm text-gray-500">6k orders</div>
          </div>

          <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm text-gray-500">Total revenue</span>
              <x-tabler-discount class="text-gray-400 size-6" />
            </div>

            <div class="mb-2 text-3xl font-bold">
              {{ number_format($statistics['total_revenue'], 2) }}
            </div>

            <div class="text-sm text-gray-500">6k orders</div>
          </div>

          <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm text-gray-500">Overdue invoices</span>
              <x-tabler-alert-circle class="text-red-400 size-6" />
            </div>

            <div class="mb-2 text-3xl font-bold">
              {{ $statistics['outstanding_invoices'] }}
            </div>

            <div class="text-sm text-gray-500">6k orders</div>
          </div>

        </div>

        <!-- Invoices Section -->
        <div class="mb-8">

          <h2 class="mb-4 text-xl font-semibold">Recently paid invoices</h2>

          <x-latest-invoice :invoices="$statistics['recently_paid_invoices']" />

        </div>

      </div>

    </div>

  </div>

</x-app-layout>
