
<script setup lang="ts">
import { computed } from 'vue'
import { Label } from '@/components/ui/label'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldInput,
} from '@/components/ui/number-field'

const props = defineProps<{
  invoice: any
}>()

const client = computed(() => props.invoice.client || {})
const items = computed(() => props.invoice.items || [])
const payments = computed(() => props.invoice.payments || [])
</script>

<template>
  <div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Invoice #{{ props.invoice.number }}</h1>
    <div class="mb-4">
      <div><strong>Date:</strong> {{ new Date(props.invoice.date).toLocaleDateString() }}</div>
      <div><strong>Client:</strong> {{ client.name || 'N/A' }}</div>
      <div><strong>Status:</strong> {{ props.invoice.status }}</div>
      <div><strong>Notes:</strong> {{ props.invoice.notes || '-' }}</div>
    </div>
    <div class="mb-6">
      <h2 class="text-lg font-semibold mb-2">Items</h2>
      <table class="min-w-full border text-sm">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-2 py-1 border">Product/Service</th>
            <th class="px-2 py-1 border">Quantity</th>
            <th class="px-2 py-1 border">Price</th>
            <th class="px-2 py-1 border">Total</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id">
            <td class="px-2 py-1 border">{{ item.product?.name || '-' }}</td>
            <td class="px-2 py-1 border">
              <NumberField :model-value="item.quantity" :format-options="{ maximumFractionDigits: 0 }" readonly class="w-16">
                <NumberFieldContent>
                  <NumberFieldInput />
                </NumberFieldContent>
              </NumberField>
            </td>
            <td class="px-2 py-1 border">
              <NumberField :model-value="item.price" :format-options="{ style: 'currency', currency: props.invoice.currency || 'USD', minimumFractionDigits: 2 }" readonly class="w-24">
                <NumberFieldContent>
                  <NumberFieldInput />
                </NumberFieldContent>
              </NumberField>
            </td>
            <td class="px-2 py-1 border">
              <NumberField :model-value="item.price * item.quantity" :format-options="{ style: 'currency', currency: props.invoice.currency || 'USD', minimumFractionDigits: 2 }" readonly class="w-24">
                <NumberFieldContent>
                  <NumberFieldInput />
                </NumberFieldContent>
              </NumberField>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mb-6">
      <h2 class="text-lg font-semibold mb-2">Payments</h2>
      <table class="min-w-full border text-sm">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-2 py-1 border">Date</th>
            <th class="px-2 py-1 border">Amount</th>
            <th class="px-2 py-1 border">Method</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="payment in payments" :key="payment.id">
            <td class="px-2 py-1 border">{{ new Date(payment.date).toLocaleDateString() }}</td>
            <td class="px-2 py-1 border">
              <NumberField :model-value="payment.amount" :format-options="{ style: 'currency', currency: props.invoice.currency || 'USD', minimumFractionDigits: 2 }" readonly class="w-24">
                <NumberFieldContent>
                  <NumberFieldInput />
                </NumberFieldContent>
              </NumberField>
            </td>
            <td class="px-2 py-1 border">{{ payment.method || '-' }}</td>
          </tr>
          <tr v-if="payments.length === 0">
            <td colspan="3" class="text-center py-2">No payments recorded.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="text-right text-xl font-bold">
      Total:
      <NumberField :model-value="props.invoice.total" :format-options="{ style: 'currency', currency: props.invoice.currency || 'USD', minimumFractionDigits: 2 }" readonly class="w-32 inline-block">
        <NumberFieldContent>
          <NumberFieldInput />
        </NumberFieldContent>
      </NumberField>
    </div>
  </div>
</template>
