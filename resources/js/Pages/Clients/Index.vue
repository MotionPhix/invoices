<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from '@/Components/ui/table'
import { Button } from '@/Components/ui/button'
import {
  IconPlus,
  IconPencil,
  IconTrash
} from '@tabler/icons-vue'

defineProps({
  clients: {
    type: Object,
    required: true
  }
})

const headers = [
  { text: 'Name', value: 'name' },
  { text: 'Email', value: 'email' },
  { text: 'Phone', value: 'phone' },
  { text: 'Company', value: 'company_name' },
  { text: 'Actions', value: 'actions' },
]
</script>

<template>
  <Head title="Clients" />

  <div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Clients</h1>

      <Link
        :href="route('clients.create')"
        class="inline-flex items-center"
      >
        <Button>
          <IconPlus class="mr-2 h-4 w-4" />
          Add Client
        </Button>
      </Link>
    </div>

    <div class="bg-white rounded-lg shadow">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead
              v-for="header in headers"
              :key="header.value"
            >
              {{ header.text }}
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow
            v-for="client in clients.data"
            :key="client.id"
          >
            <TableCell>{{ client.name }}</TableCell>
            <TableCell>{{ client.email }}</TableCell>
            <TableCell>{{ client.phone }}</TableCell>
            <TableCell>{{ client.company_name }}</TableCell>
            <TableCell>
              <div class="flex items-center space-x-2">
                <Link
                  :href="route('clients.edit', client.id)"
                  class="text-blue-600 hover:text-blue-800"
                >
                  <IconPencil class="h-5 w-5" />
                </Link>

                <Link
                  :href="route('clients.destroy', client.id)"
                  method="delete"
                  as="button"
                  class="text-red-600 hover:text-red-800"
                  preserve-scroll
                >
                  <IconTrash class="h-5 w-5" />
                </Link>
              </div>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </div>
</template>
