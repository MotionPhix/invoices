<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Textarea } from '@/Components/ui/textarea'
import { Checkbox } from '@/Components/ui/checkbox'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
  CardFooter,
} from '@/Components/ui/card'
import {
  IconUser,
  IconMail,
  IconPhone,
  IconBuilding,
  IconReceipt2,
  IconMapPin,
  IconNotes,
  IconCreditCard,
} from '@tabler/icons-vue'
import MainLayout from "@/Layouts/MainLayout.vue"
import { watch } from "vue"
import PhoneInput from "@/Components/PhoneInput.vue";

const form = useForm({
  name: '',
  email: '',
  phone: '',
  company_name: '',
  vat_number: '',
  billing_address: '',
  billing_city: '',
  billing_state: '',
  billing_postal_code: '',
  billing_country: '',
  shipping_address: '',
  shipping_city: '',
  shipping_state: '',
  shipping_postal_code: '',
  shipping_country: '',
  use_billing_for_shipping: true,
  notes: '',
  currency: 'MWK'
})

const submit = () => {
  form.post(route('clients.store'))
}

watch(
  () => [
    form.use_billing_for_shipping,
    form.billing_address,
    form.billing_city,
    form.billing_state,
    form.billing_postal_code,
    form.billing_country
  ],
  ([useForShipping]) => {
    if (useForShipping) {
      // Copy billing address to shipping address
      form.shipping_address = form.billing_address
      form.shipping_city = form.billing_city
      form.shipping_state = form.billing_state
      form.shipping_postal_code = form.billing_postal_code
      form.shipping_country = form.billing_country
    }
  },
  { deep: true }
)
</script>

<template>
  <Head title="Create Client" />

  <MainLayout>
    <template #header>
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-foreground">
            Create Client
          </h2>
          <p class="text-sm text-muted-foreground">
            Add a new client to your system
          </p>
        </div>
      </div>
    </template>

    <form @submit.prevent="submit">
      <div class="space-y-6">
        <!-- Basic Information -->
        <Card>
          <CardHeader>
            <CardTitle>Basic Information</CardTitle>
            <CardDescription>
              Enter the client's basic contact information
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div class="space-y-2">
                <Label for="name" class="flex items-center gap-2">
                  <IconUser class="h-4 w-4" />
                  Name
                </Label>
                <Input
                  id="name"
                  v-model="form.name"
                  type="text"
                  :class="{ 'border-destructive': form.errors.name }"
                />
                <span v-if="form.errors.name" class="text-sm text-destructive">
                  {{ form.errors.name }}
                </span>
              </div>

              <div class="space-y-2">
                <Label for="email" class="flex items-center gap-2">
                  <IconMail class="h-4 w-4" />
                  Email
                </Label>
                <Input
                  id="email"
                  v-model="form.email"
                  type="email"
                  :class="{ 'border-destructive': form.errors.email }"
                />
                <span v-if="form.errors.email" class="text-sm text-destructive">
                  {{ form.errors.email }}
                </span>
              </div>

              <div class="space-y-2">
                <Label for="phone" class="flex items-center gap-2">
                  <IconPhone class="h-4 w-4" />
                  Phone
                </Label>
                <PhoneInput
                  v-model="form.phone"
                  :error="form.errors.phone"
                  default-country="MW"
                />
              </div>

              <div class="space-y-2">
                <Label for="company_name" class="flex items-center gap-2">
                  <IconBuilding class="h-4 w-4" />
                  Company Name
                </Label>
                <Input
                  id="company_name"
                  v-model="form.company_name"
                  type="text"
                />
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Business Details -->
        <Card>
          <CardHeader>
            <CardTitle>Business Details</CardTitle>
            <CardDescription>
              Enter additional business information
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div class="space-y-2">
                <Label for="vat_number" class="flex items-center gap-2">
                  <IconReceipt2 class="h-4 w-4" />
                  VAT Number
                </Label>
                <Input
                  id="vat_number"
                  v-model="form.vat_number"
                  type="text"
                />
              </div>

              <div class="space-y-2">
                <Label for="currency" class="flex items-center gap-2">
                  <IconCreditCard class="h-4 w-4" />
                  Currency
                </Label>
                <Input
                  id="currency"
                  v-model="form.currency"
                  type="text"
                />
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Address Information -->
        <Card>
          <CardHeader>
            <CardTitle>Address Information</CardTitle>
            <CardDescription>
              Enter the client's billing and shipping addresses
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-6">
              <!-- Billing Address -->
              <div>
                <h3 class="text-lg font-medium">Billing Address</h3>
                <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div class="col-span-full">
                    <Label for="billing_address" class="flex items-center gap-2">
                      <IconMapPin class="h-4 w-4" />
                      Street Address
                    </Label>
                    <Textarea
                      id="billing_address"
                      v-model="form.billing_address"
                      rows="3"
                      :class="{ 'border-destructive': form.errors.billing_address }"
                    />
                    <span v-if="form.errors.billing_address" class="text-sm text-destructive">
                      {{ form.errors.billing_address }}
                    </span>
                  </div>

                  <div>
                    <Label for="billing_city">City</Label>
                    <Input
                      id="billing_city"
                      v-model="form.billing_city"
                      type="text"
                      :class="{ 'border-destructive': form.errors.billing_city }"
                    />
                  </div>

                  <div>
                    <Label for="billing_state">State/Province</Label>
                    <Input
                      id="billing_state"
                      v-model="form.billing_state"
                      type="text"
                      :class="{ 'border-destructive': form.errors.billing_state }"
                    />
                  </div>

                  <div>
                    <Label for="billing_postal_code">Postal Code</Label>
                    <Input
                      id="billing_postal_code"
                      v-model="form.billing_postal_code"
                      type="text"
                      :class="{ 'border-destructive': form.errors.billing_postal_code }"
                    />
                  </div>

                  <div>
                    <Label for="billing_country">Country</Label>
                    <Input
                      id="billing_country"
                      v-model="form.billing_country"
                      type="text"
                      :class="{ 'border-destructive': form.errors.billing_country }"
                    />
                  </div>
                </div>
              </div>

              <!-- Shipping Address Toggle -->
              <div class="flex items-center space-x-2">
                <Checkbox
                  id="use_billing_for_shipping"
                  v-model:checked="form.use_billing_for_shipping"
                />
                <Label
                  for="use_billing_for_shipping"
                  class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                  Shipping address is the same as billing address
                </Label>
              </div>

              <!-- Shipping Address (conditional) -->
              <div v-if="!form.use_billing_for_shipping" class="border-t pt-6">
                <h3 class="text-lg font-medium">Shipping Address</h3>
                <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div class="col-span-full">
                    <Label for="shipping_address" class="flex items-center gap-2">
                      <IconMapPin class="h-4 w-4" />
                      Street Address
                    </Label>
                    <Textarea
                      id="shipping_address"
                      v-model="form.shipping_address"
                      rows="3"
                      :class="{ 'border-destructive': form.errors.shipping_address }"
                    />
                    <span v-if="form.errors.shipping_address" class="text-sm text-destructive">
                      {{ form.errors.shipping_address }}
                    </span>
                  </div>

                  <div>
                    <Label for="shipping_city">City</Label>
                    <Input
                      id="shipping_city"
                      v-model="form.shipping_city"
                      type="text"
                      :class="{ 'border-destructive': form.errors.shipping_city }"
                    />
                  </div>

                  <div>
                    <Label for="shipping_state">State/Province</Label>
                    <Input
                      id="shipping_state"
                      v-model="form.shipping_state"
                      type="text"
                      :class="{ 'border-destructive': form.errors.shipping_state }"
                    />
                  </div>

                  <div>
                    <Label for="shipping_postal_code">Postal Code</Label>
                    <Input
                      id="shipping_postal_code"
                      v-model="form.shipping_postal_code"
                      type="text"
                      :class="{ 'border-destructive': form.errors.shipping_postal_code }"
                    />
                  </div>

                  <div>
                    <Label for="shipping_country">Country</Label>
                    <Input
                      id="shipping_country"
                      v-model="form.shipping_country"
                      type="text"
                      :class="{ 'border-destructive': form.errors.shipping_country }"
                    />
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Additional Information -->
        <Card>
          <CardHeader>
            <CardTitle>Additional Information</CardTitle>
            <CardDescription>
              Add any additional notes about the client
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-2">
              <Label for="notes" class="flex items-center gap-2">
                <IconNotes class="h-4 w-4" />
                Notes
              </Label>
              <Textarea
                id="notes"
                v-model="form.notes"
                rows="4"
              />
            </div>
          </CardContent>

          <CardFooter class="justify-end space-x-4">
            <Button
              type="button"
              variant="outline"
              @click="router.get(route('clients.index'), {}, { replace: true })">
              Cancel
            </Button>

            <Button
              type="submit"
              :disabled="form.processing">
              Create Client
            </Button>
          </CardFooter>
        </Card>
      </div>
    </form>
  </MainLayout>
</template>
