<html lang="en">
  <head>
      <title>Invoice</title>
      <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body class="min-h-screen bg-gray-100 dark:bg-neutral-800 print:bg-transparent">

    <div class="py-12 print:p-0">

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:p-0 print:m-0">

        <!-- Invoice -->
        <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10 print:p-0 print:m-0">
          <div class="sm:w-11/12 lg:w-3/4 mx-auto print:w-full">
            <!-- Card -->
            <div class="flex flex-col p-4 sm:p-10 bg-white shadow-md rounded-xl print:shadow-none print:rounded-none dark:bg-neutral-800">
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

              </div>

              <p class="text-gray-500 text-xs dark:text-neutral-500 mt-10">
                This is a <strong>computer-generated</strong> invoice. Should you have any questions concerning this invoice,
                please use the following contact information:
              </p>

              <section class="flex items-center gap-4 mt-5 text-sm text-gray-500 dark:text-neutral-500 border-t pt-4">

                <div class="flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-neutral-200">

                  <x-tabler-phone class="flex-shrink-0 size-4"/>

                  <span>
                    {{ $settings->company_phone }}
                  </span>

                </div>

                @if($settings->company_phone)

                  <span class="w-px bg-gray-700 h-6"></span>

                @endif

                <div class="flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-neutral-200">

                  <x-tabler-mail class="flex-shrink-0 size-4" />

                  <span>
                    {{ $settings->company_email }}
                  </span>

                </div>

                <span class="flex-1"></span>

                <span class="text-sm">© {{ date('Y') }} {{ $settings->company_name }}.</span>

              </section>

            </div>
            <!-- End Card -->

          </div>

        </div>
        <!-- End Invoice -->

      </div>

    </div>

  </body>
</html>
