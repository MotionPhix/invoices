<x-app-layout>

  <x-slot name="header">

    <h2 class="text-xl font-semibold leading-tight text-gray-800">

      {{ $invoice->iid ? 'Edit invoice # ' . $invoice->invoice_number : 'New invoice' }}

    </h2>

    @if ($invoice->iid)

      <span class="flex-1 mx-6"></span>

      <x-splade-link
        class="flex items-center gap-1.5 font-medium text-teal-800 bg-teal-100 rounded px-2 py-1"
        href="{{ route('invoices.show', $invoice) }}">
        <x-tabler-file-info class="size-5" />
        <span>Detail</span>
      </x-splade-link>

    @endif

  </x-slot>

  <div class="py-12">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

      <x-splade-form
        action="{{ $invoice->iid ? route('invoices.update', $invoice) : route('invoices.store') }}"
        class="flex flex-col gap-6"
        default="{
          ...{{ $invoice }},
        }"
        method="{{ $invoice->iid ? 'patch': 'post' }}">

        <section class="grid grid-cols-3 gap-6">

          <x-splade-input
            name="invoice_date"
            label="Invoice due date"
            placeholder="Enter payment due date"
            required
            date />

          <x-splade-select
            name="currency"
            label="Currency"
            choices="{ searchEnabled: false }">

            <option value="" disabled>Pick a currency</option>

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

            <option value="" disabled>Set an invoice status</option>

            @foreach(\App\Models\Invoice::STATUSES as $invoice_status)

              <option value="{{ $invoice_status['value'] }}">

                {{ $invoice_status['label'] }}

              </option>

            @endforeach

          </x-splade-select>

        </section>

        <x-splade-textarea
          name="description"
          label="Invoice terms"
          placeholder="Invoice terms or notes"
          autosize
        />

        <section>

          <x-splade-data default="{ items: form.items }">

            <header class="flex items-center gap-6 mb-6">

              <h2 class="flex-1 text-xl">Item summary</h2>

              <button
                type="button"
                @click="data.items.push({ description: 'Dummy item', quantity: 1, unit_price: 0 })"
                class="flex items-center justify-center text-white bg-blue-500 rounded size-7">
                <x-tabler-plus class="size-5" />
              </button>

            </header>

            <!-- Table -->
            <div class="mt-6">
              <div class="p-4 space-y-4 border border-gray-200 rounded-lg dark:border-neutral-700">

                <div class="hidden sm:grid sm:grid-cols-10">
                  <div class="text-xs font-medium text-gray-500 uppercase sm:col-span-7 dark:text-neutral-500">Item</div>
                  <div class="text-xs font-medium text-gray-500 uppercase text-start dark:text-neutral-500">Qty</div>
                  <div class="text-xs font-medium text-gray-500 uppercase text-start dark:text-neutral-500">Unit Price</div>
                  <div class="text-xs font-medium text-gray-500 uppercase text-end dark:text-neutral-500">Amount</div>
                </div>

                <div class="hidden border-b border-gray-200 sm:block dark:border-neutral-700"></div>

                <div
                  class="relative grid grid-cols-8 gap-2 sm:grid-cols-10"
                  v-for="(item, index) in data.items"
                  :class="{ 'grid-cols-8': data.items.length > 1, 'pb-3 border-b border-gray-200 dark:border-neutral-700': index !== data.items.length - 1 }"
                  :key="index">

                  <div class="col-span-full sm:col-span-7">
                    <CustomInput
                      v-model="item.description"
                      placeholder="Enter unit price" />
                  </div>

                  <div>
                    <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden dark:text-neutral-500">Qty</h5>

                    <CustomInput
                      v-model="item.quantity"
                      placeholder="Enter unit price"
                      type="number"
                      :step="1" />

                  </div>

                  <div>
                    <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden dark:text-neutral-500">Rate</h5>

                    <CustomInput
                      v-model="item.unit_price"
                      placeholder="Enter unit price"
                      type="number"/>

                  </div>

                  <div>
                    <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden dark:text-neutral-500">Line total</h5>
                    <p class="pt-2 text-gray-800 sm:text-end dark:text-neutral-200" v-text="item.unit_price * item.quantity" />
                  </div>

                  @if($invoice->status === 'draft')

                    <div v-if="data.items.length > 1" class="absolute -right-12 top-2">

                      <button
                        type="button"
                        @click="data.items.splice(index, 1)"
                        class="flex items-center justify-center ml-2 text-white bg-red-500 rounded-md size-6">

                        <x-tabler-x class="w-5 h-5" />

                      </button>

                    </div>

                  @endif

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
