<x-app-layout>

  <x-slot name="header">

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">

      {{ $invoice->iid ? 'Edit invoice # ' . $invoice->invoice_number : 'New invoice' }}

    </h2>

  </x-slot>

  <div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <x-splade-form
        action="{{ $invoice->iid ? route('invoices.update', $invoice) : route('invoices.store') }}"
        class="flex flex-col gap-6"
        default="{
          ...{{ $invoice }},
        }"
        method="{{ $invoice->iid ? 'patch': 'post' }}">

        <x-splade-input
          name="invoice_date"
          label="Invoice due date"
          required
          date />

        <section class="grid grid-cols-2 gap-6">

          <x-splade-select
            name="currency"
            label="Currency"
            choices="{ searchEnabled: false }">

            <option value="" disabled>Pick a currency...</option>

            @foreach(\App\Models\Settings::CURRENCIES as $invoice_currency)

              <option value="{{ $invoice_currency }}">

                {{ $invoice_currency }}

              </option>

            @endforeach

          </x-splade-select>

          <x-splade-select
            name="status"
            label="Status"
            choices="{ searchEnabled: false }">

            <option value="" disabled>Set an invoice status...</option>

            @foreach(\App\Models\Invoice::STATUSES as $invoice_status)

              <option value="{{ $invoice_status['value'] }}">

                {{ $invoice_status['label'] }}

              </option>

            @endforeach

          </x-splade-select>

        </section>

        <x-splade-textarea
          name="description"
          label="Invoice terms" />

        <section>

          <x-splade-data default="{ items: form.items }">

            <header class="flex items-center gap-6 mb-6">

              <h2 class="text-xl">Item summary</h2>

              <button
                type="button"
                @click="data.items.push({ description: '', quantity: 1, unit_price: 0 })"
                class="size-8 bg-blue-500 text-white rounded-md flex items-center justify-center">
                <x-tabler-plus class="size-5" />
              </button>

            </header>

            <!-- Table -->
            <div class="mt-6">
              <div class="border border-gray-200 p-4 rounded-lg space-y-4 dark:border-neutral-700">

                <div class="hidden sm:grid sm:grid-cols-10">
                  <div class="sm:col-span-7 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Item</div>
                  <div class="text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Qty</div>
                  <div class="text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Unit Price</div>
                  <div class="text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Amount</div>
                </div>

                <div class="hidden sm:block border-b border-gray-200 dark:border-neutral-700"></div>

                <div
                  class="grid grid-cols-8 sm:grid-cols-10 gap-2"
                  v-for="(item, index) in data.items"
                  :class="{ 'grid-cols-8': data.items.length > 1, 'pb-3 border-b border-gray-200 dark:border-neutral-700': index !== data.items.length - 1 }"
                  :key="index">

                  <div class="col-span-full sm:col-span-7">
                    <CustomInput v-model="item.description" placeholder="Enter unit price" />
                  </div>

                  <div>
                    <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Qty</h5>

                    <CustomInput
                      v-model="item.quantity"
                      placeholder="Enter unit price"
                      type="number"
                      :step="1" />

                  </div>

                  <div>
                    <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Rate</h5>

                    <CustomInput
                      v-model="item.unit_price"
                      placeholder="Enter unit price"
                      type="number"/>

                  </div>

                  <div>
                    <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Line total</h5>
                    <p class="sm:text-end text-gray-800 dark:text-neutral-200" v-text="item.unit_price * item.quantity" />
                  </div>

                </div>

              </div>
            </div>
            <!-- End Table -->

          </x-splade-data>

        </section>

        <x-splade-submit>
          {{ $invoice->iid ? 'Update' : 'Create' }} Invoice
        </x-splade-submit>

      </x-splade-form>

    </div>

  </div>

</x-app-layout>
