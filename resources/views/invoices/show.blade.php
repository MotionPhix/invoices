<x-app-layout>

  <x-slot name="header">

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">

      Invoice # {{ $invoice->invoice_number }}

    </h2>

    <span class="mx-6 flex-1"></span>

    <x-splade-link
      class="flex items-center gap-1.5 font-medium text-teal-800 bg-teal-100 rounded px-2 py-1"
      href="{{ route('invoices.edit', $invoice) }}">
      <x-tabler-pencil class="size-5" />
      <span>Edit</span>
    </x-splade-link>

  </x-slot>

  <div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <!-- Invoice -->
      <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
        <div class="sm:w-11/12 lg:w-3/4 mx-auto">
          <!-- Card -->
          <div class="flex flex-col p-4 sm:p-10 bg-white shadow-md rounded-xl dark:bg-neutral-800">
            <!-- Grid -->
            <div class="flex justify-between">
              <div>
                <svg class="size-10" width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1 26V13C1 6.37258 6.37258 1 13 1C19.6274 1 25 6.37258 25 13C25 19.6274 19.6274 25 13 25H12" class="stroke-blue-600 dark:stroke-white" stroke="currentColor" stroke-width="2"/>
                  <path d="M5 26V13.16C5 8.65336 8.58172 5 13 5C17.4183 5 21 8.65336 21 13.16C21 17.6666 17.4183 21.32 13 21.32H12" class="stroke-blue-600 dark:stroke-white" stroke="currentColor" stroke-width="2"/>
                  <circle cx="13" cy="13.0214" r="5" fill="currentColor" class="fill-blue-600 dark:fill-white"/>
                </svg>

                <h1 class="mt-2 text-lg md:text-xl font-semibold text-blue-600 dark:text-white">
                  {{ $settings->company_name }}
                </h1>
              </div>
              <!-- Col -->

              <div class="text-end">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 dark:text-neutral-200">Invoice #</h2>
                <span class="mt-1 block text-gray-500 dark:text-neutral-500">
                  {{ $invoice->invoice_number }}
                </span>

                <address class="mt-4 not-italic text-gray-800 dark:text-neutral-200">
                  {{ $settings->address->street }} <br />
                  {{ $settings->address->city }}, {{ $settings->address->state }} <br />
                  {{ $settings->address->country }}
                </address>
              </div>
              <!-- Col -->
            </div>
            <!-- End Grid -->

            <!-- Grid -->
            <div class="mt-8 grid sm:grid-cols-2 gap-3">
              <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Bill to:</h3>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                  {{ $invoice->contact->full_name }}
                </h3>
                <address class="mt-2 not-italic text-gray-500 dark:text-neutral-500">
                  {{ $invoice->contact->company->address->street }} <br />
                  {{ $invoice->contact->company->address->city }}, {{ $invoice->contact->company->address->state }} <br />
                  {{ $invoice->contact->company->address->country }}
                </address>
              </div>
              <!-- Col -->

              <div class="sm:text-end space-y-2">
                <!-- Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                  <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Invoice date:</dt>
                    <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                      {{ $invoice->created_at->format('j M, Y') }}
                    </dd>
                  </dl>

                  <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Due date:</dt>
                    <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                      {{ \Carbon\Carbon::createFromDate($invoice->invoice_date)->format('j M, Y') }}
                    </dd>
                  </dl>
                </div>
                <!-- End Grid -->
              </div>
              <!-- Col -->
            </div>
            <!-- End Grid -->

            <!-- Table -->
            <div class="mt-6">
              <div class="border border-gray-200 p-4 rounded-lg space-y-4 dark:border-neutral-700">
                <div class="hidden sm:grid sm:grid-cols-6">
                  <div class="sm:col-span-3 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Item</div>

                  <div class="text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Qty</div>

                  <div class="text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    Rate ({{ $invoice->currency ?? \App\Models\Settings::first()->currency }})
                  </div>

                  <div class="text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    Amount ({{ $invoice->currency ?? \App\Models\Settings::first()->currency }})
                  </div>
                </div>

                <div class="hidden sm:block border-b border-gray-200 dark:border-neutral-700"></div>

                @foreach ($invoice->items as $item)

                  <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">

                    <div class="col-span-full sm:col-span-3">
                      <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Item</h5>
                      <p class="font-medium text-gray-800 dark:text-neutral-200">
                        {{ $item->description }}
                      </p>
                    </div>

                    <div>
                      <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Qty</h5>
                      <p class="text-gray-800 dark:text-neutral-200">
                        {{ $item->quantity }}
                      </p>
                    </div>

                    <div>
                      <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Rate</h5>
                      <p class="text-gray-800 dark:text-neutral-200">
                        {{ \Illuminate\Support\Number::format($item->unit_price, precision: 2) }}
                      </p>
                    </div>

                    <div>
                      <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Amount</h5>
                      <p class="sm:text-end text-gray-800 dark:text-neutral-200">
                        {{ \Illuminate\Support\Number::format($item->unit_price * $item->quantity, precision: 2) }}
                      </p>
                    </div>
                  </div>

                @endforeach

                <div class="sm:hidden border-b border-gray-200 dark:border-neutral-700"></div>

              </div>
            </div>
            <!-- End Table -->

            <!-- Flex -->
            <div class="mt-8 flex sm:justify-end pr-4">
              <div class="w-full max-w-md sm:text-end space-y-2">
                <!-- Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                  <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Subtotal:</dt>
                    <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                      {{ \Illuminate\Support\Number::format($invoice->subtotal, precision: 2) }}
                    </dd>
                  </dl>

                  <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">
                      Tax ({{ $settings->vat_rate }}%):
                    </dt>

                    <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                      {{ \Illuminate\Support\Number::format($invoice->vatAmount, precision: 2) }}
                    </dd>
                  </dl>

                  <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Total:</dt>
                    <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                      {{ \Illuminate\Support\Number::format($invoice->totalAmount, precision: 2) }}
                    </dd>
                  </dl>

                  <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Amount paid:</dt>
                    <dd class="col-span-2 text-gray-500 dark:text-neutral-500">-</dd>
                  </dl>

                  <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Due balance:</dt>
                    <dd class="col-span-2 text-gray-500 dark:text-neutral-500">-</dd>
                  </dl>
                </div>
                <!-- End Grid -->
              </div>
            </div>
            <!-- End Flex -->

            <div class="mt-8 sm:mt-12 max-w-xl">
              <h4 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Thank you!</h4>

              <p class="text-gray-500 dark:text-neutral-500">
                {{ $invoice->description }}
              </p>

              <p class="text-gray-500 text-sm dark:text-neutral-500 mt-10">
                Should you have any questions concerning this invoice, <br> please use the following contact information:
              </p>

              <div class="mt-2">
                <p class="flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-neutral-200">
                  <x-tabler-mail class="flex-shrink-0 size-4"/>

                  <span>
                    {{ $settings->company_email }}
                  </span>
                </p>

                <p class="flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-neutral-200">
                  <x-tabler-phone class="flex-shrink-0 size-4"/>

                  <span>
                    {{ $settings->company_phone }}
                  </span>
                </p>
              </div>
            </div>

            <p class="mt-5 text-sm text-gray-500 dark:text-neutral-500">
              © {{ date('Y') }} {{ $settings->company_name }}.
            </p>

          </div>
          <!-- End Card -->

          <!-- Buttons -->
          <div class="mt-6 flex justify-end gap-x-3">

            <a class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" download
              href="{{ route('invoices.print', $invoice) }}">
              <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
              Invoice PDF
            </a>

            <x-splade-link
              class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
              href="{{ route('invoices.print', $invoice) }}">
              <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
              Print
            </x-splade-link>
          </div>
          <!-- End Buttons -->
        </div>
      </div>
      <!-- End Invoice -->

    </div>

  </div>

</x-app-layout>
