<x-app-layout>

  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Settings') }}
    </h2>
  </x-slot>

  <div class="py-12">

    <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

      <x-splade-form
        action="{{ route('settings.update', $settings) }}"
        preserve-scroll
        method="patch"
        class="space-y-6"
        default="{
          ...{{ $settings }}
        }">

        <div class="bg-white p-4 rounded-lg shadow-sm space-y-4">

          <h2 class="text-xl font-medium">Invoice settings</h2>

          <x-splade-input
            type="text"
            name="invoice_prefix"
            label="Invoice prefix"
            help="Sets the initial part of the invoice before the invoice number, e.g. 'INV-'"
            required />

          <x-splade-input
            type="text"
            name="invoice_suffix"
            label="Invoice suffix"
            placeholder="This sets the end part of the invoice."
            help="Could be anything, e.g. a year like INV-00000-2024" />

          <x-splade-input
            type="number"
            name="invoice_start_number"
            label="Invoice start number"
            placeholder="This sets the starting number for invoices."
            help="Only works when you haven't created any invoices"
          />

          <x-splade-input
            type="number"
            name="invoice_number_length"
            label="Invoice number length"
            placeholder="This sets the number of digits for invoice number."
            help="Not recommended to change this after you have created some invoices"
          />

          <x-splade-select
            name="currency"
            label="Default currency"
            help="For invoices where currency is not set, this currency will be used">

            <option value="" disabled>Pick a currency to be used system wide</option>

            @foreach(\App\Models\Settings::CURRENCIES as $currency)

              <option value="{{ $currency }}">
                {{ $currency }}
              </option>

            @endforeach

          </x-splade-select>

          <x-splade-input
            type="number"
            step="0.01"
            name="vat_rate"
            label="VAT Rate (%)"
            required />
        </div>

        <!-- Company Information -->
        <div class="bg-white p-4 rounded-lg shadow-sm space-y-4">
          <h2 class="text-xl font-medium">Company information</h2>

          <x-splade-input
            type="text"
            name="company_name"
            label="Company name"
            required />

          <!-- Start Address -->
          <section>

            <x-splade-data>

              <AddressFieldGroup
                v-model:street="form.address.street"
                v-model:city="form.address.city"
                v-model:state="form.address.state"
                v-model:country="form.address.country"
                type="work" />

            </x-splade-data>

          </section>

          <!-- End Address -->

          <x-splade-input
            type="email"
            name="company_email"
            label="Company email"
            required />

          <x-splade-input
            type="text"
            name="company_phone"
            label="Company phone" />
        </div>

        <!-- Notification Settings -->
        <div class="bg-white p-4 rounded-lg shadow-sm space-y-4">
          <h2 class="text-xl font-medium">Notifications</h2>

          <x-splade-checkbox
            name="email_notifications"
            label="Enable Email Notifications" />

          <x-splade-checkbox
            name="sms_notifications"
            label="Enable SMS Notifications" />
        </div>

        <x-splade-submit class="btn btn-primary">
          Update settings
        </x-splade-submit>

      </x-splade-form>

    </div>

  </div>

</x-app-layout>
