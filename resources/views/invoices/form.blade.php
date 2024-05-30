<x-app-layout>

  <x-slot name="header">

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">

      {{ $invoice->iid ? 'Edit invoice # ' . $invoice->invoice_number : 'New invoice' }}

    </h2>

  </x-slot>

  <div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

{{--      <x-splade-form--}}
{{--        action="{{ route('invoices.store') }}"--}}
{{--        class="flex flex-col gap-6"--}}
{{--        :default="$invoice">--}}

{{--        <x-splade-input type="date" date name="invoice_date" label="Invoice date" class="form-control" required />--}}

{{--        <x-splade-textarea name="description" label="Description" class="form-control" />--}}

{{--        <div class="form-group">--}}

{{--          <h2 class="text-xl mb-4">Invoice items</h2>--}}

{{--          <x-splade-data--}}
{{--            :default="['items' => $invoice->items]">--}}
{{--            <pre v-text="data"></pre>--}}
{{--            <div--}}
{{--              v-for="(item, index) in data.items"--}}
{{--              class="item mb-2 flex items-center"--}}
{{--              :key="index" >--}}

{{--              <x-splade-input type="text" v-model="item.description" placeholder="Description" class="w-full border-gray-300 rounded-s-md shadow-sm" required />--}}
{{--              :name="`items[${index}][description]`"--}}
{{--              <input type="number" v-model="item.quantity" :name="`items[${index}][quantity]`" placeholder="Quantity" class="w-full border-gray-300 shadow-sm" required>--}}

{{--              <input type="number" v-model="item.unit_price" :name="`items[${index}][unit_price]`" placeholder="Unit Price" class="w-full border-gray-300 rounded-e-md shadow-sm" required>--}}

{{--              <button--}}
{{--                type="button"--}}
{{--                @click="data.items.splice(index, 1)"--}}
{{--                class="ml-2 px-4 py-2 bg-red-500 text-white rounded-md">--}}
{{--                Remove--}}
{{--              </button>--}}

{{--            </div>--}}

{{--            <button--}}
{{--              type="button"--}}
{{--              @click="data.items.push({ description: '', quantity: 1, unit_price: 0 })" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md">--}}
{{--              Add Item--}}
{{--            </button>--}}

{{--          </x-splade-data>--}}

{{--          <button--}}
{{--            type="button"--}}
{{--            onclick="addItem()"--}}
{{--            class="mt-4 flex flex-shrink-0 justify-center items-center gap-2 size-[38px] text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">--}}
{{--            <x-tabler-plus class="flex-shrink-0 size-6" />--}}
{{--          </button>--}}
{{--        </div>--}}

{{--        <x-splade-submit--}}
{{--          class="btn btn-primary">--}}
{{--          Create Invoice--}}
{{--        </x-splade-submit>--}}

{{--      </x-splade-form>--}}



      <x-splade-form
        action="{{ $invoice->iid ? route('invoices.update', $invoice) : route('invoices.store') }}"
        class="flex flex-col gap-6"
        default="{
          ...{{ $invoice }},
        }"
        method="{{ $invoice->iid ? 'patch': 'post' }}">

        <x-splade-input
          name="invoice_date"
          label="Invoice Date"
          required
          date />

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

        <x-splade-textarea
          name="description"
          label="Description" />

        <div class="form-group">

          <section class="flex items-center gap-6 mb-4">

            <h2 class="text-xl">Invoice Items</h2>

            <button
              type="button"
              @click="data.items.push({ description: '', quantity: 1, unit_price: 0 })"
              class="size-8 bg-blue-500 text-white rounded-md flex items-center justify-center">
              <x-tabler-plus class="size-5" stroke-width="3" />
            </button>

          </section>

          <x-splade-data default="{ items: form.items }">

            <div v-for="(item, index) in data.items" :key="index" class="item mb-2 flex items-center">

              <input
                type="text"
                v-model="item.description"
                :name="'items[' + index + '][description]'"
                placeholder="Description"
                class="w-full border-none rounded-s-md shadow-sm"
                required />

              <input
                type="number"
                v-model="item.quantity"
                :name="'items[' + index + '][quantity]'"
                placeholder="Quantity"
                class="border-none shadow-sm"
                required />

              <input
                type="number"
                v-model="item.unit_price"
                :name="'items[' + index + '][unit_price]'"
                placeholder="Unit Price"
                class="border-none rounded-e-md shadow-sm"
                required />

              <article>
                <button
                  type="button"
                  @click="data.items.splice(index, 1)"
                  class="ml-2 size-8 bg-red-500 text-white rounded-md flex items-center justify-center">
                  <x-tabler-x class="size-5" stroke-width="3" />
                </button>
              </article>

            </div>

          </x-splade-data>

        </div>

        <x-splade-submit>
          {{ $invoice->iid ? 'Update' : 'Create' }} Invoice
        </x-splade-submit>

      </x-splade-form>

    </div>

  </div>

</x-app-layout>
