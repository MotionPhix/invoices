@php use App\Models\Settings;use Illuminate\Support\Number; @endphp
<x-app-layout>

  <x-slot name="header">
    <h2 class="my-0 text-xl font-semibold leading-tight text-gray-800">
      {{ __('Invoices') }}
    </h2>
  </x-slot>

  <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">

    <!-- Card -->
    <div class="flex flex-col">

      <div class="-m-1.5 overflow-x-auto">

        <div class="p-1.5 min-w-full inline-block align-middle">

          <div
            class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700">

            <!-- Header -->
            <div
              class="grid gap-3 px-6 py-4 border-b border-gray-200 md:flex md:justify-between md:items-center dark:border-neutral-700">

              <div>
                <h2
                  class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                  Invoices
                </h2>

                <p class="text-sm text-gray-600 dark:text-neutral-400">
                  Create invoices, edit, download and more.
                </p>
              </div>

              <div>

                <div class="inline-flex gap-x-2">

                  <x-splade-link
                    class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded gap-x-2 hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                    href="{{ route('invoices.create') }}"
                    preserve-scroll>
                    <x-tabler-plus class="flex-shrink-0 size-5"/>
                    <span>Create</span>
                  </x-splade-link>

                </div>

              </div>

            </div>
            <!-- End Header -->

            <!-- Table -->
            <table
              class="w-full divide-y divide-gray-200 dark:divide-neutral-700">

              <thead class="bg-gray-50 dark:bg-neutral-900">

              <tr>
                <th scope="col" class="px-6 py-3 text-start">

                  <div class="flex items-center gap-x-2">

                      <span
                        class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                        Invoice #
                      </span>

                  </div>

                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                        Customer
                      </span>
                  </div>
                </th>

                {{-- <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                        Due
                      </span>
                  </div>
                </th> --}}

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                        Status
                      </span>
                  </div>
                </th>

                {{-- <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                        VAT
                      </span>
                  </div>
                </th> --}}

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                      <span
                        class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                        Amount
                      </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-end"></th>
              </tr>

              </thead>

              <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">

              @foreach ($invoices as $invoice)

                <tr class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800">

                  <td class="size-px whitespace-nowrap">
                    <button type="button" class="block">
                        <span class="block px-6 py-2">
                          <span class="font-mono text-sm text-blue-600 dark:text-blue-500">

                            {{ $invoice->invoice_number }}

                          </span>
                        </span>
                    </button>
                  </td>

                  <td class="size-px whitespace-nowrap">
                    <button type="button" class="block">
                        <span class="block px-6 py-2">
                          <span class="text-sm text-gray-600 dark:text-neutral-400">

                            {{ $invoice->contact->fullname }}

                          </span>
                        </span>
                    </button>
                  </td>

                  {{-- <td class="size-px whitespace-nowrap">
                    <button type="button" class="block">
                        <span class="block px-6 py-2">
                          <span class="text-sm text-gray-600 dark:text-neutral-400">

                            {{ $invoice->invoice_date }}

                          </span>
                        </span>
                    </button>
                  </td> --}}

                  <td class="size-px whitespace-nowrap">

                    <button type="button" class="block">

                        <span class="block px-6 py-2">

                          <span
                            class="capitalize py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded"
                            @class([
                              'bg-rose-100 text-rose-800  dark:bg-rose-500/10 dark:text-rose-500' => $invoice->status == 'canceled',
                              'bg-blue-100 text-blue-800  dark:bg-blue-500/10 dark:text-blue-500' => $invoice->status == 'partial',
                              'bg-gray-100 text-gray-800  dark:bg-gray-500/10 dark:text-gray-500' => $invoice->status == 'draft',
                              'bg-teal-100 text-teal-800  dark:bg-teal-500/10 dark:text-teal-500' => $invoice->status == 'paid',
                            ])
                          >

                            {{ $invoice->status }}

                          </span>

                        </span>

                    </button>

                  </td>

                  {{-- <td class="size-px whitespace-nowrap">
                    <button type="button" class="block">
                        <span class="block px-6 py-2">
                          <span class="text-sm text-gray-600 dark:text-neutral-400">

                            {{ Number::currency($invoice->vat_amount, in: $invoice->currency ?? Settings::first()->currency) }}

                          </span>
                        </span>
                    </button>
                  </td> --}}

                  <td class="size-px whitespace-nowrap">
                    <button type="button" class="block">
                        <span class="block px-6 py-2">
                          <span class="text-sm text-gray-600 dark:text-neutral-400">

                            {{ Number::currency($invoice->total_amount, in: $invoice->currency ?? Settings::first()->currency) }}

                          </span>
                        </span>
                    </button>
                  </td>

                  <td class="px-6 size-px whitespace-nowrap">

                    <div class="flex items-center gap-2">

                      <x-splade-link
                        preserve-scroll
                        href="{{ route('invoices.show', $invoice) }}"
                        class="flex items-center gap-1 text-sm hover:text-blue-500">
                        <x-tabler-file-invoice class="w-5 h-5"/>
                      </x-splade-link>

                      <x-splade-link
                        preserve-scroll
                        href="{{ route('invoices.edit', $invoice) }}"
                        class="flex items-center gap-1 ml-2 text-sm hover:text-green-500">
                        <x-tabler-pencil class="w-5 h-5"/>
                      </x-splade-link>

                      <x-splade-link
                        preserve-scroll
                        href="{{ route('invoices.destroy', $invoice) }}"
                        class="flex items-center gap-1 text-sm dark:text-white hover:text-red-500"
                        method="delete">
                        <x-tabler-trash class="w-5 h-5"/>
                      </x-splade-link>

                    </div>

                  </td>

                </tr>

              @endforeach

              </tbody>

            </table>
            <!-- End Table -->

            <!-- Footer -->
            <div
              class="grid gap-3 px-6 py-4 border-t border-gray-200 md:flex md:justify-between md:items-center dark:border-neutral-700">
              <div>
                <p class="text-sm text-gray-600 dark:text-neutral-400">
                  <span class="font-semibold text-gray-800 dark:text-neutral-200">

                    {{ count($invoices) }}

                  </span> results
                </p>
              </div>

              <div>
                <div class="inline-flex gap-x-2">
                  <button type="button"
                          class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                    <svg class="size-3" width="16" height="16" viewBox="0 0 16 15" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M10.506 1.64001L4.85953 7.28646C4.66427 7.48172 4.66427 7.79831 4.85953 7.99357L10.506 13.64"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Prev
                  </button>

                  <button type="button"
                          class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                    Next
                    <svg class="size-3" width="16" height="16" viewBox="0 0 16 16" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M4.50598 2L10.1524 7.64645C10.3477 7.84171 10.3477 8.15829 10.1524 8.35355L4.50598 14"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            <!-- End Footer -->
          </div>

        </div>

      </div>

    </div>
    <!-- End Card -->

  </div>
</x-app-layout>
