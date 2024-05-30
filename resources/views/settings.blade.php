<x-app-layout>

  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Settings') }}
    </h2>
  </x-slot>

  <div class="py-12">

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

      <x-splade-form
        action="{{ route('settings.update') }}"
        method="patch"
        class="space-y-6"
        default="{ ...$settings }">

        <!-- General Settings -->
        <div class="bg-white p-4 rounded-lg shadow-md">
          <h2 class="text-xl font-semibold mb-4">General Settings</h2>

          <x-splade-input
            type="text"
            name="site_name"
            label="Site Name"
            class="form-control"
            required />

          <x-splade-input
            type="email"
            name="admin_email"
            label="Admin Email"
            class="form-control"
            required />
        </div>

        <!-- Appearance Settings -->
        <div class="bg-white p-4 rounded-lg shadow-md">
          <h2 class="text-xl font-semibold mb-4">Appearance Settings</h2>

          <x-splade-input
            type="color"
            name="primary_color"
            label="Primary Color"
            class="form-control"
            required />

          <x-splade-input
            type="color"
            name="secondary_color"
            label="Secondary Color"
            class="form-control"
            required />
        </div>

        <!-- Notification Settings -->
        <div class="bg-white p-4 rounded-lg shadow-md">
          <h2 class="text-xl font-semibold mb-4">Notification Settings</h2>

          <x-splade-checkbox
            name="email_notifications"
            label="Enable Email Notifications"
            class="form-control" />

          <x-splade-checkbox
            name="sms_notifications"
            label="Enable SMS Notifications"
            class="form-control" />
        </div>

        <x-splade-submit class="btn btn-primary">
          Save Settings
        </x-splade-submit>

      </x-splade-form>

    </div>

  </div>

</x-app-layout>
