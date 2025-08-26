<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { formatCurrency } from '@/lib/utils'

const props = defineProps<{
  invoice: any
  paymentUrl: string
  reference: string
}>()

const processing = ref(false)

const initiatePayment = () => {
  processing.value = true
  window.location.href = props.paymentUrl
}
</script>

<template>
  <div class="container mx-auto py-8">
    <Card class="mx-auto max-w-lg">
      <CardHeader>
        <CardTitle>Make Payment</CardTitle>
        <CardDescription>
          Pay Invoice #{{ invoice.number }}
        </CardDescription>
      </CardHeader>

      <CardContent>
        <div class="mb-6 space-y-4">
          <div class="flex justify-between text-sm">
            <span>Amount to Pay:</span>
            <span class="font-medium">
              {{ formatCurrency(invoice.total, invoice.currency) }}
            </span>
          </div>

          <div class="flex justify-between text-sm">
            <span>Reference:</span>
            <span class="font-medium">{{ reference }}</span>
          </div>

          <div class="text-sm text-muted-foreground">
            You will be redirected to PayChangu to complete your payment.
          </div>
        </div>

        <Button
          @click="initiatePayment"
          class="w-full"
          :disabled="processing"
        >
          {{ processing ? 'Redirecting...' : 'Proceed to Payment' }}
        </Button>
      </CardContent>
    </Card>
  </div>
</template>
