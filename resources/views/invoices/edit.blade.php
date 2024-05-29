<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Edit Invoice
    </h2>
  </x-slot>

  <div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <x-splade-form
        action="{{ route('invoices.update', $invoice) }}"
        class="flex flex-col gap-6"
        default="{
          ...{{ $invoice }},
        }"
        method="patch">

        <x-splade-input date name="invoice_date" label="Invoice Date" required />

        <x-splade-textarea name="description" label="Description" />

        <div class="form-group">

          <h2 class="text-xl mb-4">Invoice Items</h2>

          <x-splade-data default="{ items: form.items }">

            <div v-for="(item, index) in data.items" :key="index" class="item mb-2 flex items-center">

              <input type="text" v-model="item.description" :name="`items[${index}][description]`" placeholder="Description" class="w-full border-gray-300 rounded-s-md shadow-sm" :readonly="{{ $invoice->status !== 'draft' }}" required />

              <input type="number" v-model="item.quantity" :name="`items[${index}][quantity]`" placeholder="Quantity" class="w-full border-gray-300 shadow-sm" :readonly="{{ $invoice->status !== 'draft' }}" required />

              <input type="number" v-model="item.unit_price" :name="`items[${index}][unit_price]`" placeholder="Unit Price" class="w-full border-gray-300 rounded-e-md shadow-sm" :readonly="{{ $invoice->status !== 'draft' }}" required />

              @if($invoice->status === 'draft')
                <button type="button" @click="data.items.splice(index, 1)" class="ml-2 px-4 py-2 bg-red-500 text-white rounded-md">Remove</button>
              @endif

            </div>

            @if($invoice->status === 'draft')
              <button
                type="button"
                @click="data.items.push({ description: '', quantity: 1, unit_price: 0 })"
                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md">
                Add Item
              </button>
            @endif

          </x-splade-data>

        </div>

        <x-splade-submit
          class="btn btn-primary">
          Update Invoice
        </x-splade-submit>

      </x-splade-form>

    </div>

  </div>

</x-app-layout>
