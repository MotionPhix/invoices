<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Checkbox } from '@/Components/ui/checkbox'
import { useToast } from '@/Components/ui/toast/use-toast'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'

const props = defineProps<{
  client: any
}>()

const { toast } = useToast()

const form = useForm({
  name: props.client.name,
  email: props.client.email,
  phone: props.client.phone,
  company_name: props.client.company_name,
  vat_number: props.client.vat_number,
  billing_address: props.client.billing_address,
  billing_city: props.client.billing_city,
  billing_state: props.client.billing_state,
  billing_postal_code: props.client.billing_postal_code,
  billing_country: props.client.billing_country,
  use_billing_for_shipping: props.client.use_billing_for_shipping,
  shipping_address: props.client.shipping_address,
  shipping_city: props.client.shipping_city,
  shipping_state: props.client.shipping_state,
  shipping_postal_code: props.client.shipping_postal_code,
  shipping_country: props.client.shipping_country,
})

const handleSubmit = () => {
  form.patch(route('client-portal.profile.update'), {
    onSuccess: () => {
      toast({
        title: 'Success',
        description: 'Profile updated successfully.',
      })
    },
  })
}
</script>

<template>
  <div class="container mx-auto py-8">
    <Card class="mx-auto max-w-2xl">
      <CardHeader>
        <CardTitle>Profile Settings</CardTitle>
        <CardDescription>
          Update your profile information
        </CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Basic Information -->
          <div class="space-y-4">
            <h3 class="text-lg font-medium">Basic Information</h3>

            <div class="grid gap-4 sm:grid-cols-2">
              <div class="space-y-2">
                <label for="name">Name</label>
                <Input
                  id="name"
                  v-model="form.name"
                  type="text"
                  :error="form.errors.name"
                />
              </div>

              <div class="space-y-2">
                <label for="email">Email</label>
                <Input
                  id="email"
                  v-model="form.email"
                  type="email"
                  :error="form.errors.email"
                />
              </div>

              <div class="space-y-2">
                <label for="phone">Phone</label>
                <Input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  :error="form.errors.phone"
                />
              </div>

              <div class="space-y-2">
                <label for="company_name">Company Name</label>
                <Input
                  id="company_name"
                  v-model="form.company_name"
                  type="text"
                  :error="form.errors.company_name"
                />
              </div>

              <div class="space-y-2">
                <label for="vat_number">VAT Number</label>
                <Input
                  id="vat_number"
                  v-model="form.vat_number"
                  type="text"
                  :error="form.errors.vat_number"
                />
              </div>
            </div>
          </div>

          <!-- Billing Address -->
          <div class="space-y-4">
            <h3 class="text-lg font-medium">Billing Address</h3>

            <div class="space-y-4">
              <div class="space-y-2">
                <label for="billing_address">Address</label>
                <Input
                  id="billing_address"
                  v-model="form.billing_address"
                  type="text"
                  :error="form.errors.billing_address"
                />
              </div>

              <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                  <label for="billing_city">City</label>
                  <Input
                    id="billing_city"
                    v-model="form.billing_city"
                    type="text"
                    :error="form.errors.billing_city"
                  />
                </div>

                <div class="space-y-2">
                  <label for="billing_state">State</label>
                  <Input
                    id="billing_state"
                    v-model="form.billing_state"
                    type="text"
                    :error="form.errors.billing_state"
                  />
                </div>

                <div class="space-y-2">
                  <label for="billing_postal_code">Postal Code</label>
                  <Input
                    id="billing_postal_code"
                    v-model="form.billing_postal_code"
                    type="text"
                    :error="form.errors.billing_postal_code"
                  />
                </div>

                <div class="space-y-2">
                  <label for="billing_country">Country</label>
                  <Input
                    id="billing_country"
                    v-model="form.billing_country"
                    type="text"
                    :error="form.errors.billing_country"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Shipping Address -->
          <div class="space-y-4">
            <div class="flex items-center space-x-2">
              <Checkbox
                id="use_billing_for_shipping"
                v-model:checked="form.use_billing_for_shipping"
              />
              <label
                for="use_billing_for_shipping"
                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
              >
                Use billing address for shipping
              </label>
            </div>

            <div v-if="!form.use_billing_for_shipping" class="space-y-4">
              <h3 class="text-lg font-medium">Shipping Address</h3>

              <div class="space-y-2">
                <label for="shipping_address">Address</label>
                <Input
                  id="shipping_address"
                  v-model="form.shipping_address"
                  type="text"
                  :error="form.errors.shipping_address"
                />
              </div>

              <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                  <label for="shipping_city">City</label>
                  <Input
                    id="shipping_city"
                    v-model="form.shipping_city"
                    type="text"
                    :error="form.errors.shipping_city"
                  />
                </div>

                <div class="space-y-2">
                  <label for="shipping_state">State</label>
                  <Input
                    id="shipping_state"
                    v-model="form.shipping_state"
                    type="text"
                    :error="form.errors.shipping_state"
                  />
                </div>

                <div class="space-y-2">
                  <label for="shipping_postal_code">Postal Code</label>
                  <Input
                    id="shipping_postal_code"
                    v-model="form.shipping_postal_code"
                    type="text"
                    :error="form.errors.shipping_postal_code"
                  />
                </div>

                <div class="space-y-2">
                  <label for="shipping_country">Country</label>
                  <Input
                    id="shipping_country"
                    v-model="form.shipping_country"
                    type="text"
                    :error="form.errors.shipping_country"
                  />
                </div>
              </div>
            </div>
          </div>

          <Button
            type="submit"
            class="w-full"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Saving...' : 'Save Changes' }}
          </Button>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
