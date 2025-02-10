<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { formatCurrency } from '@/lib/utils'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'

const props = defineProps<{
  statement: {
    start_date: string
    end_date: string
    opening_balance: number
    closing_balance: number
    invoices: any[]
    payments: any[]
  }
  filters: {
    start_date: string
    end_date: string
  }
}>()

const startDate = ref(props.filters.start_date)
const endDate = ref(props.filters.end_date)

const calculateRunningBalance = (transaction) => {
  let balance = props.statement.opening_balance
  const sortedTransactions = [...props.statement.invoices, ...props.statement.payments]
    .sort((a, b) => new Date(a.date) - new Date(b.date))

  for (const t of sortedTransactions) {
    if (t.id === transaction.id) break
    balance += 'invoice' in t ? t.total : -t.amount
  }

  balance += 'invoice' in transaction ? transaction.total : -transaction.amount
  return balance
}

watch([startDate, endDate], ([newStart, newEnd]) => {
  router.get(
    route('client-portal.statements.index'),
    { start_date: newStart, end_date: newEnd },
    { preserveState: true }
  )
})

const downloadStatement = () => {
  window.location.href = route('client-portal.statements.download', {
    start_date: startDate.value,
    end_date: endDate.value
  })
}
</script>

<template>
  <div class="container mx-auto py-8">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Account Statement</h1>

      <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
          <Input
            v-model="startDate"
            type="date"
            class="w-40"
          />
          <span>to</span>
          <Input
            v-model="endDate"
            type="date"
            class="w-40"
          />
        </div>

        <Button @click="downloadStatement">
          Download PDF
        </Button>
      </div>
    </div>

    <div class="mb-6 grid gap-6 md:grid-cols-3">
      <div class="rounded-lg border p-4">
        <div class="text-sm text-muted-foreground">Opening Balance</div>
        <div class="text-2xl font-bold">
          {{ formatCurrency(statement.opening_balance) }}
        </div>
      </div>

      <div class="rounded-lg border p-4">
        <div class="text-sm text-muted-foreground">Total Invoiced</div>
        <div class="text-2xl font-bold">
          {{ formatCurrency(statement.invoices.reduce((sum, inv) => sum + inv.total, 0)) }}
        </div>
      </div>

      <div class="rounded-lg border p-4">
        <div class="text-sm text-muted-foreground">Closing Balance</div>
        <div class="text-2xl font-bold">
          {{ formatCurrency(statement.closing_balance) }}
        </div>
      </div>
    </div>

    <div class="space-y-8">
      <!-- Transactions Table -->
      <div class="rounded-md border">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Date</TableHead>
              <TableHead>Description</TableHead>
              <TableHead class="text-right">Amount</TableHead>
              <TableHead class="text-right">Balance</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <!-- Opening Balance -->
            <TableRow>
              <TableCell>{{ statement.start_date }}</TableCell>
              <TableCell>Opening Balance</TableCell>
              <TableCell class="text-right">
                {{ formatCurrency(statement.opening_balance) }}
              </TableCell>
              <TableCell class="text-right">
                {{ formatCurrency(statement.opening_balance) }}
              </TableCell>
            </TableRow>

            <!-- Combined and sorted transactions -->
            <TableRow
              v-for="transaction in [...statement.invoices, ...statement.payments]
                .sort((a, b) => new Date(a.date) - new Date(b.date))"
              :key="transaction.id"
            >
              <TableCell>
                {{ new Date(transaction.date).toLocaleDateString() }}
              </TableCell>
              <TableCell>
                {{ 'invoice' in transaction
                ? `Invoice #${transaction.number}`
                : `Payment for Invoice #${transaction.invoice.number}`
                }}
              </TableCell>
              <TableCell
                class="text-right"
                :class="{'text-red-600': 'invoice' in transaction, 'text-green-600': !('invoice' in transaction)}"
              >
                {{ formatCurrency('invoice' in transaction ? transaction.total : -transaction.amount) }}
              </TableCell>
              <TableCell class="text-right">
                {{ formatCurrency(calculateRunningBalance(transaction)) }}
              </TableCell>
            </TableRow>

            <!-- Closing Balance -->
            <TableRow class="font-medium">
              <TableCell>{{ statement.end_date }}</TableCell>
              <TableCell>Closing Balance</TableCell>
              <TableCell></TableCell>
              <TableCell class="text-right">
                {{ formatCurrency(statement.closing_balance) }}
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </div>
  </div>
</template>
