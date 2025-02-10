<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import { Button } from '@/Components/ui/button'

const props = defineProps<{
  requests: {
    data: any[]
    links: any[]
  }
}>()

const getPriorityColor = (priority: string) => {
  const colors = {
    low: 'bg-blue-100 text-blue-800',
    medium: 'bg-yellow-100 text-yellow-800',
    high: 'bg-red-100 text-red-800',
  }
  return colors[priority] || 'bg-gray-100 text-gray-800'
}

const getStatusColor = (status: string) => {
  const colors = {
    open: 'bg-green-100 text-green-800',
    in_progress: 'bg-blue-100 text-blue-800',
    resolved: 'bg-purple-100 text-purple-800',
    closed: 'bg-gray-100 text-gray-800',
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
  <div class="container mx-auto py-8">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Support Requests</h1>

      <Button :href="route('client-portal.support.create')">
        New Support Request
      </Button>
    </div>

    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Subject</TableHead>
            <TableHead>Priority</TableHead>
            <TableHead>Status</TableHead>
            <TableHead>Created</TableHead>
            <TableHead>Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow
            v-for="request in requests.data"
            :key="request.id"
          >
            <TableCell>{{ request.subject }}</TableCell>
            <TableCell>
              <span
                :class="[
                  'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                  getPriorityColor(request.priority)
                ]"
              >
                {{ request.priority }}
              </span>
            </TableCell>
            <TableCell>
              <span
                :class="[
                  'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                  getStatusColor(request.status)
                ]"
              >
                {{ request.status }}
              </span>
            </TableCell>
            <TableCell>
              {{ new Date(request.created_at).toLocaleDateString() }}
            </TableCell>
            <TableCell>
              <Button
                variant="outline"
                size="sm"
                :href="route('client-portal.support.show', request.id)"
              >
                View
              </Button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </div>
</template>
