<script setup lang="ts">
import { reactive } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Textarea } from '@/Components/ui/textarea'

const form = useForm({
  name: '',
  email: '',
  phone: '',
  company_name: '',
  vat_number: '',
  billing_address: '',
  shipping_address: '',
  country: '',
  city: '',
  postal_code: '',
  notes: '',
  currency: 'USD'
})

const submit = () => {
  form.post(route('clients.store'))
}
</script>

<template>
  <Head title="Create Client" />

  <div class="container mx-auto py-6">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-2xl font-semibold text-gray-900 mb-6">Create Client</h1>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-2 gap-6">
          <div class="space-y-2">
            <Label for="name">Name</Label>
            <Input
              id="name"
              v-model="form.name"
              type="text"
              :error="form.errors.name"
            />
          </div>

          <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              v-model="form.email"
              type="email"
              :error="form.errors.email"
            />
          </div>

          <div class="space-y-2">
            <Label for="phone">Phone</Label>
            <Input
              id="phone"
              v-model="form.phone"
              type="tel"
              :error="form.errors.phone"
            />
          </div>

          <div class="space-y-2">
            <Label for="company_name">Company Name</Label>
            <Input
              id="company_name"
              v-model="form.company_name"
              type="text"
              :error="form.errors.company_name"
            />
          </div>

          <div class="space-y-2">
            <Label for="vat_number">VAT Number</Label>
            <Input
              id="vat_number"
              v-model="form.vat_number"
              type="text"
              :error="form.errors.vat_number"
            />
          </div>

          <div class="space-y-2">
            <Label for="currency">Currency</Label>
            <Input
              id="currency"
              v-model="form.currency"
              type="text"
              :error="form.errors.currency"
            />
          </div>
        </div>

        <div class="space-y-2">
          <Label for="billing_address">Billing Address</Label>
          <Textarea
            id="billing_address"
            v-model="form.billing_address"
            :error="form.errors.billing_address"
          />
        </div>

        <div class="space-y-2">
          <Label for="shipping_address">Shipping Address</Label>
          <Textarea
            id="shipping_address"
            v-model="form.shipping_address"
            :error="form.errors.shipping_address"
          />
        </div>

        <div class="grid grid-cols-3 gap-6">
          <div class="space-y-2">
            <Label for="country">Country</Label>
            <Input
              id="country"
              v-model="form.country"
              type="text"
              :error="form.errors.country"
            />
          </div>

          <div class="space-y-2">
            <Label for="city">City</Label>
            <Input
              id="city"
              v-model="form.city"
              type="text"
              :error="form.errors.city"
            />
          </div>

          <div class="space-y-2">
            <Label for="postal_code">Postal Code</Label>
            <Input
              id="postal_code"
              v-model="form.postal_code"
              type="text"
              :error="form.errors.postal_code"
            />
          </div>
        </div>

        <div class="space-y-2">
          <Label for="notes">Notes</Label>
          <Textarea
            id="notes"
            v-model="form.notes"
            :error="form.errors.notes"
          />
        </div>

        <div class="flex justify-end space-x-4">
          <Button
            type="button"
            variant="outline"
            :href="route('clients.index')"
          >
            Cancel
          </Button>
          <Button
            type="submit"
            :disabled="form.processing"
          >
            Create Client
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>
