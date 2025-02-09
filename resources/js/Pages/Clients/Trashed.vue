<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/Components/ui/dialog'
import { IconTrash, IconRefresh } from '@tabler/icons-vue'
import EmptyState from '@/Components/EmptyState.vue'
import Pagination from '@/Components/ui/pagination.vue'
import FilterBar from "@/Pages/Clients/Components/FilterBar.vue";

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

const handleRestore = (clientId) => {
  router.put(route('clients.restore', clientId))
}

const handleForceDelete = () => {
  router.delete(route('clients.force-delete', clientToDelete.value.id), {
    onSuccess: () => {
      showConfirmDelete.value = false
      clientToDelete.value = null
    },
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
            <h2 class="text-2xl font-bold text-gray-900">Trashed Clients</h2>
            <p class="mt-1 text-sm text-muted-foreground">
              View and manage deleted clients
            </p>
          </div>
          <Button
            :href="route('clients.index')"
            variant="outline"
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
          <div class="bg-white rounded-lg shadow-sm">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Company</TableHead>
                  <TableHead>Deleted At</TableHead>
                  <TableHead class="w-[100px]">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow
                  v-for="client in clients.data"
                  :key="client.id"
                >
                  <TableCell>{{ client.name }}</TableCell>
                  <TableCell>{{ client.email }}</TableCell>
                  <TableCell>{{ client.company_name }}</TableCell>
                  <TableCell>{{ new Date(client.deleted_at).toLocaleDateString() }}</TableCell>
                  <TableCell>
                    <div class="flex items-center gap-2">
                      <Button
                        variant="ghost"
                        size="icon"
                        @click="handleRestore(client.id)"
                        title="Restore"
                      >
                        <IconRefresh class="h-4 w-4" />
                      </Button>
                      <Button
                        variant="ghost"
                        size="icon"
                        @click="confirmForceDelete(client)"
                        title="Delete Permanently"
                      >
                        <IconTrash class="h-4 w-4 text-destructive" />
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
          <Pagination
            :links="clients.links"
            class="mt-6"
          />
        </div>
        <EmptyState
          v-else
          title="No trashed clients"
          description="There are no clients in the trash"
          :create-route="route('clients.index')"
          create-text="Back to Clients"
        />
      </div>
    </div>

    <!-- Permanent Delete Confirmation Dialog -->
    <Dialog v-model:open="showConfirmDelete">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Confirm Permanent Deletion</DialogTitle>
          <DialogDescription>
            Are you sure you want to permanently delete {{ clientToDelete?.name }}? This action cannot be undone.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button
            variant="outline"
            @click="showConfirmDelete = false"
          >
            Cancel
          </Button>
          <Button
            variant="destructive"
            @click="handleForceDelete"
          >
            Delete Permanently
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
