<x-app-layout>

  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Invoices') }}
    </h2>
  </x-slot>

  <div class="py-12">

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

      <div class="w-full py-10 lg:py-14">
        <!-- Card -->
        <div class="flex flex-col">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700">
                <!-- Header -->
                <div class="grid gap-3 px-6 py-4 border-b border-gray-200 md:flex md:justify-between md:items-center dark:border-neutral-700">
                  <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                      Invoices
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                      Create invoices, edit, download and more.
                    </p>
                  </div>

                  <div>
                    <div class="inline-flex gap-x-2">
                      <a class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" href="#">
                        View all
                      </a>

                      <x-splade-link
                        class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg gap-x-2 hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        :href="route('invoices.create')"
                        preserve-scroll>
                        <x-tabler-plus
                          class="flex-shrink-0 size-4"
                          width="24" height="24"
                          stroke-width="2" />
                        Create
                      </x-splade-link>
                    </div>
                  </div>
                </div>
                <!-- End Header -->

                <!-- Table -->
                <table class="w-full divide-y divide-gray-200 dark:divide-neutral-700">
                  <thead class="bg-gray-50 dark:bg-neutral-900">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                      Invoice number
                    </span>
                        <div class="hs-tooltip">
                          <div class="hs-tooltip-toggle">
                            <svg class="flex-shrink-0 text-gray-500 size-4 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                            <span class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity bg-gray-900 rounded shadow-sm opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible dark:bg-neutral-700" role="tooltip">
                          Invoice number related popup
                        </span>
                          </div>
                        </div>
                      </div>
                    </th>

                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                      Amount
                    </span>
                      </div>
                    </th>

                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                      Customer
                    </span>
                      </div>
                    </th>

                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                        <span
                          class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                          VAT
                        </span>
                      </div>
                    </th>

                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                      Due
                    </span>
                      </div>
                    </th>

                    <th scope="col" class="px-6 py-3 text-start">
                      <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200">
                      Total
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
                      <button type="button" class="block" data-hs-overlay="#hs-ai-invoice-modal">
                    <span class="block px-6 py-2">
                      <span class="font-mono text-sm text-blue-600 dark:text-blue-500">
                        {{ $invoice->invoice_number }}
                      </span>
                    </span>
                      </button>
                    </td>
                    <td class="size-px whitespace-nowrap">
                      <button type="button" class="block" data-hs-overlay="#hs-ai-invoice-modal">
                    <span class="block px-6 py-2">
                      <span class="text-sm text-gray-600 dark:text-neutral-400">
                        {{ $invoice->amount }}
                      </span>
                    </span>
                      </button>
                    </td>
                    <td class="size-px whitespace-nowrap">
                      <button type="button" class="block">
                    <span class="block px-6 py-2">
                      <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded dark:bg-teal-500/10 dark:text-teal-500">
                        {{ $invoice->user->name }}
                      </span>
                    </span>
                      </button>
                    </td>
                    <td class="size-px whitespace-nowrap">
                      <button type="button" class="block" data-hs-overlay="#hs-ai-invoice-modal">
                    <span class="block px-6 py-2">
                      <span class="text-sm text-gray-600 dark:text-neutral-400">
                        {{ $invoice->vat_amount }}
                      </span>
                    </span>
                      </button>
                    </td>

                    <td class="size-px whitespace-nowrap">
                      <button type="button" class="block" data-hs-overlay="#hs-ai-invoice-modal">
                    <span class="block px-6 py-2">
                      <span class="text-sm text-gray-600 dark:text-neutral-400">
                        {{ $invoice->invoice_date }}
                      </span>
                    </span>
                      </button>
                    </td>

                      <td class="size-px whitespace-nowrap">
                        <button type="button" class="block" data-hs-overlay="#hs-ai-invoice-modal">
                    <span class="block px-6 py-2">
                      <span class="text-sm text-gray-600 dark:text-neutral-400">
                        {{ $invoice->total_amount }}
                      </span>
                    </span>
                        </button>
                      </td>

                    <td class="size-px whitespace-nowrap">

                      <div class="flex items-center gap-2">

                        <x-splade-link
                          href="{{ route('invoices.show', $invoice) }}"
                          class="flex items-center gap-1 text-sm dark:text-white">
                          <x-tabler-file class="w-5 h-5" />
                          <span>View</span>
                        </x-splade-link>

                        <x-splade-link
                          href="{{ route('invoices.edit', $invoice) }}" class="flex items-center gap-1 ml-2 text-sm text-yellow-500">
                          <x-tabler-pencil class="w-5 h-5" />
                          <span>Edit</span>
                        </x-splade-link>

                        <x-splade-link
                          preserve-scroll
                          href="{{ route('invoices.destroy', $invoice) }}"
                          class="flex items-center gap-1 text-sm dark:text-white hover:text-red-500"
                          method="delete">
                          <x-tabler-trash class="w-5 h-5" />
                          <span>Delete</span>
                        </x-splade-link>

                      </div>

                    </td>
                  </tr>

                  @endforeach

                  </tbody>

                </table>
                <!-- End Table -->

                <!-- Footer -->
                <div class="grid gap-3 px-6 py-4 border-t border-gray-200 md:flex md:justify-between md:items-center dark:border-neutral-700">
                  <div>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                      <span class="font-semibold text-gray-800 dark:text-neutral-200">
                        {{ count($invoices) }}
                      </span> results
                    </p>
                  </div>

                  <div>
                    <div class="inline-flex gap-x-2">
                      <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        <svg class="size-3" width="16" height="16" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.506 1.64001L4.85953 7.28646C4.66427 7.48172 4.66427 7.79831 4.85953 7.99357L10.506 13.64" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Prev
                      </button>

                      <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        Next
                        <svg class="size-3" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M4.50598 2L10.1524 7.64645C10.3477 7.84171 10.3477 8.15829 10.1524 8.35355L4.50598 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
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
      <!-- End Table Section -->

      <!-- Modal -->
      <div id="hs-ai-invoice-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
          <div class="relative flex flex-col bg-white shadow-lg pointer-events-auto rounded-xl dark:bg-neutral-800">
            <div class="relative overflow-hidden text-center bg-gray-900 min-h-32 rounded-t-xl">
              <!-- Close Button -->
              <div class="absolute top-2 end-2">
                <button type="button" class="inline-flex items-center justify-center flex-shrink-0 text-sm text-gray-500 transition-all rounded-lg size-8 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-900 dark:focus:ring-neutral-700 dark:focus:ring-offset-gray-800" data-hs-overlay="#hs-ai-invoice-modal">
                  <span class="sr-only">Close</span>
                  <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
              </div>
              <!-- End Close Button -->

              <!-- SVG Background Element -->
              <figure class="absolute inset-x-0 bottom-0">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                  <path fill="currentColor" class="fill-white dark:fill-neutral-800" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
                </svg>
              </figure>
              <!-- End SVG Background Element -->
            </div>

            <div class="relative z-10 -mt-12">
              <!-- Icon -->
              <span class="mx-auto flex justify-center items-center size-[62px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
          <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
            <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
          </svg>
        </span>
              <!-- End Icon -->
            </div>

            <!-- Body -->
            <div class="p-4 overflow-y-auto sm:p-7">
              <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                  Invoice from Preline
                </h3>
                <p class="text-sm text-gray-500 dark:text-neutral-500">
                  Invoice #3682303
                </p>
              </div>

              <!-- Grid -->
              <div class="grid grid-cols-2 gap-5 mt-5 sm:mt-10 sm:grid-cols-3">
                <div>
                  <span class="block text-xs text-gray-500 uppercase dark:text-neutral-500">Amount paid:</span>
                  <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">$316.8</span>
                </div>
                <!-- End Col -->

                <div>
                  <span class="block text-xs text-gray-500 uppercase dark:text-neutral-500">Date paid:</span>
                  <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">April 22, 2020</span>
                </div>
                <!-- End Col -->

                <div>
                  <span class="block text-xs text-gray-500 uppercase dark:text-neutral-500">Payment method:</span>
                  <div class="flex items-center gap-x-2">
                    <svg class="size-5" width="400" height="248" viewBox="0 0 400 248" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g clip-path="url(#clip0)">
                        <path d="M254 220.8H146V26.4H254V220.8Z" fill="#FF5F00"/>
                        <path d="M152.8 123.6C152.8 84.2 171.2 49 200 26.4C178.2 9.2 151.4 0 123.6 0C55.4 0 0 55.4 0 123.6C0 191.8 55.4 247.2 123.6 247.2C151.4 247.2 178.2 238 200 220.8C171.2 198.2 152.8 163 152.8 123.6Z" fill="#EB001B"/>
                        <path d="M400 123.6C400 191.8 344.6 247.2 276.4 247.2C248.6 247.2 221.8 238 200 220.8C228.8 198.2 247.2 163 247.2 123.6C247.2 84.2 228.8 49 200 26.4C221.8 9.2 248.6 0 276.4 0C344.6 0 400 55.4 400 123.6Z" fill="#F79E1B"/>
                      </g>
                      <defs>
                        <clipPath id="clip0">
                          <rect width="400" height="247.2" fill="white"/>
                        </clipPath>
                      </defs>
                    </svg>
                    <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">•••• 4242</span>
                  </div>
                </div>
                <!-- End Col -->
              </div>
              <!-- End Grid -->

              <div class="mt-5 sm:mt-10">
                <h4 class="text-xs font-semibold text-gray-800 uppercase dark:text-neutral-200">Summary</h4>

                <ul class="flex flex-col mt-3">
                  <li class="inline-flex items-center px-4 py-3 -mt-px text-sm text-gray-800 border gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                      <span>Payment to Front</span>
                      <span>$264.00</span>
                    </div>
                  </li>
                  <li class="inline-flex items-center px-4 py-3 -mt-px text-sm text-gray-800 border gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                      <span>Tax fee</span>
                      <span>$52.8</span>
                    </div>
                  </li>
                  <li class="inline-flex items-center px-4 py-3 -mt-px text-sm font-semibold text-gray-800 border gap-x-2 bg-gray-50 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                      <span>Amount paid</span>
                      <span>$316.8</span>
                    </div>
                  </li>
                </ul>
              </div>

              <!-- Button -->
              <div class="flex justify-end mt-5 gap-x-2">
                <a class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 align-middle transition-all bg-white border rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" href="#">
                  <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                  Invoice PDF
                </a>
                <a class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg gap-x-2 hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                  Print
                </a>
              </div>
              <!-- End Buttons -->

              <div class="mt-5 sm:mt-10">
                <p class="text-sm text-gray-500 dark:text-neutral-500">If you have any questions, please contact us at <a class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline font-medium dark:text-blue-500" href="#">example@site.com</a> or call at <a class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline font-medium dark:text-blue-500" href="tel:+1898345492">+1 898-34-5492</a></p>
              </div>
            </div>
            <!-- End Body -->
          </div>
        </div>
      </div>
      <!-- End Modal -->

    </div>

  </div>
</x-app-layout>