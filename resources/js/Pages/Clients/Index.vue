<script setup lang="ts">
import {Head, router} from '@inertiajs/vue3'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from '@/components/ui/table'
import {Button} from '@/components/ui/button'
import {
  Card,
  CardContent,
} from '@/components/ui/card'
import {
  IconPlus,
  IconPencil,
  IconTrash,
  IconMail,
  IconPhone,
  IconBuilding,
} from '@tabler/icons-vue'
import EmptyState from '@/components/EmptyState.vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import {emptyStates} from "@/config/EmptyState"
import FilterBar from "@/pages/clients/components/FilterBar.vue";
import Statistics from "@/pages/clients/components/Statistics.vue";
import ImportExport from "@/pages/clients/components/ImportExport.vue";
import {Checkbox} from "@/components/ui/checkbox";
import BulkActions from "@/pages/clients/components/BulkActions.vue";
import VerificationStatus from "@/pages/clients/components/VerificationStatus.vue";
import {toast} from "vue-sonner";
import {ref} from "vue";

const props = defineProps({
  clients: Object,
  filters: Object,
  sortOptions: Array,
  statusOptions: Array,
  statistics: Object
})

const selectedClients = ref([])

const headers = [
  {text: 'Name', value: 'name'},
  {text: 'Phone', value: 'phone'},
  {text: 'Company', value: 'company_name'},
  {text: 'Status', value: 'actions'},
]

const toggleSelection = (clientId) => {
  const index = selectedClients.value.indexOf(clientId)
  if (index === -1) {
    selectedClients.value.push(clientId)
  } else {
    selectedClients.value.splice(index, 1)
  }
}

const selectAll = () => {
  if (selectedClients.value.length === props.clients.data.length) {
    selectedClients.value = []
  } else {
    selectedClients.value = props.clients.data.map(client => client.id)
  }
}

const isRecentlySent = (sentAt) => {
  if (!sentAt) return false
  const cooldownPeriod = 5 * 60 * 1000 // 5 minutes
  return (new Date() - new Date(sentAt)) < cooldownPeriod
}

const resendVerification = async (client) => {
  try {
    await router.post(route('client.verification.resend', client.id))
    toast({
      title: 'Success',
      description: 'Verification email has been sent',
    })
  } catch (error) {
    toast({
      title: 'Error',
      description: 'Failed to send verification email',
      variant: 'destructive',
    })
  }
}
</script>

<template>
  <Head title="Clients"/>

  <MainLayout>
    <template #header>
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-foreground">
            Clients
          </h2>

          <p class="text-sm text-muted-foreground">
            Manage your client relationships and information
          </p>
        </div>

        <div class="mt-4 sm:mt-0 flex items-center gap-4">
          <BulkActions
            :selected="selectedClients"
          />

          <ImportExport
            :filters="filters"
            :selected="selectedClients"
          />

          <Button
            v-if="clients.data.length > 0"
            @click="router.get(route('clients.create'), {}, { replace: true })">
            <IconPlus class="h-4 w-4"/>
            Add Client
          </Button>

          <Button
            variant="outline"
            @click="router.get(route('clients.trashed'), {}, { replace: true })"
            class="gap-2">
            <IconTrash class="h-4 w-4"/>
            Trashed
          </Button>
        </div>
      </div>
    </template>

    <Statistics
      v-if="clients.data.length > 0"
      :statistics="statistics"
    />

    <!-- Filters -->
    <FilterBar
      :filters="filters"
      :sort-options="sortOptions"
      :status-options="statusOptions"
    />

    <Card v-if="clients.data.length === 0">
      <CardContent class="p-0">
        <EmptyState
          v-bind="emptyStates.clients"
          :create-route="route('clients.create')"
          create-label="Add Your First Client"
        />
      </CardContent>
    </Card>

    <div v-else>
      <!-- Desktop Table View -->
      <div class="hidden md:block">
        <Card>
          <CardContent class="p-0">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead class="w-12">
                    <Checkbox
                      :checked="selectedClients.length === clients.data.length"
                      :indeterminate="selectedClients.length > 0 && selectedClients.length < clients.data.length"
                      @update:checked="selectAll"
                    />
                  </TableHead>

                  <TableHead
                    v-for="header in headers"
                    :key="header.value">
                    {{ header.text }}
                  </TableHead>

                  <TableHead />
                </TableRow>
              </TableHeader>

              <TableBody>
                <TableRow
                  v-for="client in clients.data"
                  :key="client.id" >
                  <TableCell>
                    <Checkbox
                      :checked="selectedClients.includes(client.id)"
                      @update:checked="toggleSelection(client.id)"
                    />
                  </TableCell>

                  <TableCell
                    class="font-medium cursor-pointer"
                    @click="router.get(route('clients.show', client.id), {}, {replace: true})">
                    <h5 class="font-semibold dark:text-white">
                      {{ client.name }}
                    </h5>
                    <p class="text-gray-500 dark:text-gray-400">
                      {{ client.email }}
                    </p>
                  </TableCell>

                  <TableCell>{{ client.phone }}</TableCell>

                  <TableCell>{{ client.company_name }}</TableCell>

                  <!-- Add to your table row -->
                  <TableCell>
                    <div class="flex items-center gap-2">
                      <VerificationStatus :verified="client.email_verified_at !== null" />
                      <Button
                        v-if="!client.email_verified_at"
                        variant="ghost"
                        size="sm"
                        @click="resendVerification(client)"
                        :disabled="client.email_verification_sent_at && isRecentlySent(client.email_verification_sent_at)"
                      >
                        Resend
                      </Button>
                    </div>
                  </TableCell>

                  <TableCell class="text-right">
                    <div class="flex items-center justify-end space-x-2">
                      <Button
                        variant="ghost"
                        size="icon"
                        :href="route('clients.edit', client.id)">
                        <IconPencil class="h-4 w-4"/>
                        <span class="sr-only">Edit client</span>
                      </Button>

                      <Button
                        variant="ghost"
                        size="icon"
                        :href="route('clients.destroy', client.id)"
                        method="delete"
                        as="button"
                        class="text-destructive hover:text-destructive"
                        preserve-scroll>
                        <IconTrash class="h-4 w-4"/>
                        <span class="sr-only">Delete client</span>
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </CardContent>
        </Card>
      </div>

      <!-- Mobile Card View -->
      <div class="grid grid-cols-1 gap-4 md:hidden">
        <Card
          v-for="client in clients.data"
          @click="router.get(route('clients.show', client.id))"
          :key="client.id">
          <CardContent class="p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="font-semibold">{{ client.name }}</h3>
              <div class="flex items-center space-x-2">
                <Button
                  variant="ghost"
                  size="icon"
                  :href="route('clients.edit', client.id)">
                  <IconPencil class="h-4 w-4"/>
                </Button>

                <Button
                  variant="ghost"
                  size="icon"
                  :href="route('clients.destroy', client.id)"
                  method="delete"
                  as="button"
                  class="text-destructive hover:text-destructive"
                  preserve-scroll>
                  <IconTrash class="h-4 w-4"/>
                </Button>
              </div>
            </div>

            <div class="space-y-2">
              <div class="flex items-center text-sm">
                <IconMail class="mr-2 h-4 w-4 text-muted-foreground"/>
                {{ client.email }}
              </div>
              <div class="flex items-center text-sm">
                <IconPhone class="mr-2 h-4 w-4 text-muted-foreground"/>
                {{ client.phone }}
              </div>
              <div class="flex items-center text-sm">
                <IconBuilding class="mr-2 h-4 w-4 text-muted-foreground"/>
                {{ client.company_name }}
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="clients.data.length > 0" class="mt-6">
      <!-- Add pagination component here -->
    </div>
  </MainLayout>
</template>
