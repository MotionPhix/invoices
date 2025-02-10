<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import { formatCurrency } from '@/lib/utils'

const props = defineProps<{
  invoices: {
    data: any[]
    links: any[]
  }
}>()

const search = ref('')

const getStatusColor = (status: string) => {
  const colors = {
    paid: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    overdue: 'bg-red-100 text-red-800',
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
  <div class="container mx-auto py-8">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Invoices</h1>

      <div class="w-64">
        <Input
          v-model="search"
          type="search"
          placeholder="Search invoices..."
        />
      </div>
    </div>

    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Invoice Number</TableHead>
            <TableHead>Date</TableHead>
            <TableHead>Amount</TableHead>
            <TableHead>Status</TableHead>
            <TableHead>Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow
            v-for="invoice in invoices.data"
            :key="invoice.id"
          >
            <TableCell>{{ invoice.number }}</TableCell>
            <TableCell>
              {{ new Date(invoice.date).toLocaleDateString() }}
            </TableCell>
            <TableCell>
              {{ formatCurrency(invoice.total, invoice.currency) }}
            </TableCell>
            <TableCell>
              <span
                :class="[
                  'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                  getStatusColor(invoice.status)
                ]"
              >
                {{ invoice.status }}
              </span>
            </TableCell>
            <TableCell>
              <div class="flex space-x-2">
                <Button
                  variant="outline"
                  size="sm"
                  :href="route('client-portal.invoices.show', invoice.id)"
                >
                  View
                </Button>
                <Button
                  variant="outline"
                  size="sm"
                  :href="route('client-portal.invoices.download', invoice.id)"
                >
                  Download
                </Button>
                <Button
                  v-if="invoice.status === 'pending'"
                  variant="default"
                  size="sm"
                  :href="route('client-portal.payments.create', { invoice: invoice.id })"
                >
                  Pay Now
                </Button>
              </div>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between">
      <div class="text-sm text-gray-500">
        Showing {{ invoices.data.length }} of {{ invoices.total }} invoices
      </div>

      <div class="flex space-x-2">
        <Link
          v-for="link in invoices.links"
          :key="link.label"
          :href="link.url"
          :class="[
            'px-3 py-1 rounded',
            link.active
              ? 'bg-primary text-primary-foreground'
              : 'bg-secondary hover:bg-secondary/80'
          ]"
          v-html="link.label"
        />
      </div>
    </div>
  </div>
</template>
