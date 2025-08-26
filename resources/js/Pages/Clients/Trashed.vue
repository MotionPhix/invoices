<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Modal, ModalLink } from '@inertiaui/modal-vue'
import { IconTrash, IconRefresh } from '@tabler/icons-vue'
import EmptyState from '@/components/EmptyState.vue'
import Pagination from '@/components/Pagination.vue'
import FilterBar from "@/pages/clients/components/FilterBar.vue";
import { router } from '@inertiajs/vue3'
import { AlertCircle } from 'lucide-vue-next'

const props = defineProps({
  clients: Object,
  filters: Object,
})

const showConfirmDelete = ref(false)
const clientToDelete = ref(null)

const confirmForceDelete = (client) => {
  clientToDelete.value = client
  showConfirmDelete.value = true
}

function restoreClient(clientId) {
  router.put(route('clients.restore', clientId))
}

function forceDeleteClient(clientToDelete) {
  router.delete(route('clients.force-delete', clientToDelete.id), {
    onFinish: () => {/* handle finish */}
  })
}
</script>

<template>
  <AppLayout>
    <Head title="Trashed Clients" />
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="space-y-6">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Trashed Clients</h2>
            <p class="mt-1 text-sm text-muted-foreground dark:text-gray-400">
              View and manage deleted clients
            </p>
          </div>
          <Button
            :href="route('clients.index')"
            variant="outline"
            class="dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
          >
            Back to Clients
          </Button>
        </div>

        <!-- Filters -->
        <FilterBar
          :filters="filters"
          placeholder="Search trashed clients..."
        />

        <!-- Content -->
        <div v-if="clients.data.length > 0">
          <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead class="text-gray-700 dark:text-gray-200">Name</TableHead>
                  <TableHead class="text-gray-700 dark:text-gray-200">Email</TableHead>
                  <TableHead class="text-gray-700 dark:text-gray-200">Company</TableHead>
                  <TableHead class="text-gray-700 dark:text-gray-200">Deleted At</TableHead>
                  <TableHead class="w-[100px] text-gray-700 dark:text-gray-200">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="client in clients.data"
                  :key="client.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                  <TableCell class="dark:text-gray-100">{{ client.name }}</TableCell>
                  <TableCell class="dark:text-gray-100">{{ client.email }}</TableCell>
                  <TableCell class="dark:text-gray-100">{{ client.company_name }}</TableCell>
                  <TableCell class="dark:text-gray-100">{{ new Date(client.deleted_at).toLocaleDateString() }}</TableCell>
                  <TableCell>
                    <div class="flex items-center gap-2">
                      <Button
                        variant="ghost"
                        size="icon"
                        @click="restoreClient(client.id)"
                        title="Restore"
                        class="dark:hover:bg-gray-700"
                      >
                        <IconRefresh class="h-4 w-4 dark:text-gray-300" />
                      </Button>
                      <ModalLink :href="'#confirm-delete'" as="button" class="dark:hover:bg-gray-700">
                        <IconTrash class="h-4 w-4 text-destructive dark:text-red-400" />
                      </ModalLink>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
          <Pagination
            :current-page="clients.current_page"
            :total-pages="clients.last_page"
            @page-change="page => router.get(route('clients.trashed', { page }))"
            class="mt-6"
          />
        </div>
        <EmptyState
          v-else
          title="No trashed clients"
          description="There are no clients in the trash"
          :icon="AlertCircle"
          :create-route="route('clients.index')"
          create-text="Back to Clients"
          class="dark:bg-gray-900"
        />
      </div>
    </div>

    <!-- Permanent Delete Confirmation Modal (Local) -->
    <Modal name="confirm-delete" v-slot="{ close }">
      <div class="p-6 dark:bg-gray-900">
        <h2 class="text-lg font-bold mb-2 dark:text-gray-100">
          Confirm Permanent Deletion
        </h2>

        <p class="mb-4 text-gray-700 dark:text-gray-400">
          Are you sure you want to permanently delete this client? This action cannot be undone.
        </p>
        
        <div class="flex justify-end gap-2">
          <Button
            variant="outline"
            @click="close"
            class="dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
            Cancel
          </Button>

          <Button
            variant="destructive"
            @click="() => { forceDeleteClient(clientToDelete); close(); }"
            class="dark:bg-red-600 dark:text-white">
            Delete Permanently
          </Button>
        </div>
      </div>
    </Modal>
  </AppLayout>
</template>
