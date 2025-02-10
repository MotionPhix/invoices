<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'

defineProps<{
  client: any
  recentInvoices: any[]
  recentPayments: any[]
}>()
</script>

<template>
  <div class="container mx-auto py-8">
    <h1 class="mb-8 text-3xl font-bold">Welcome, {{ client.name }}</h1>

    <div class="grid gap-6 md:grid-cols-2">
      <!-- Recent Invoices -->
      <Card>
        <CardHeader>
          <CardTitle>Recent Invoices</CardTitle>
          <CardDescription>Your latest invoices</CardDescription>
        </CardHeader>
        <CardContent>
          <div v-if="recentInvoices.length" class="space-y-4">
            <div
              v-for="invoice in recentInvoices"
              :key="invoice.id"
              class="flex items-center justify-between"
            >
              <div>
                <p class="font-medium">Invoice #{{ invoice.number }}</p>
                <p class="text-sm text-muted-foreground">
                  {{ new Date(invoice.date).toLocaleDateString() }}
                </p>
              </div>
              <Button
                variant="outline"
                size="sm"
                :href="route('client-portal.invoices.download', invoice.id)"
              >
                Download
              </Button>
            </div>
          </div>
          <p v-else class="text-muted-foreground">No recent invoices</p>
        </CardContent>
      </Card>

      <!-- Recent Payments -->
      <Card>
        <CardHeader>
          <CardTitle>Recent Payments</CardTitle>
          <CardDescription>Your latest payments</CardDescription>
        </CardHeader>
        <CardContent>
          <div v-if="recentPayments.length" class="space-y-4">
            <div
              v-for="payment in recentPayments"
              :key="payment.id"
              class="flex items-center justify-between"
            >
              <div>
                <p class="font-medium">{{ payment.amount }}</p>
                <p class="text-sm text-muted-foreground">
                  {{ new Date(payment.date).toLocaleDateString() }}
                </p>
              </div>
            </div>
          </div>
          <p v-else class="text-muted-foreground">No recent payments</p>
        </CardContent>
      </Card>
    </div>
  </div>
</template>
