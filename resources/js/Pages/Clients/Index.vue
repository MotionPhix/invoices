<script setup lang="ts">
import {Head, Link, router} from '@inertiajs/vue3'
import {useBreakpoints} from '@vueuse/core'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from '@/Components/ui/table'
import {Button} from '@/Components/ui/button'
import {
  Card,
  CardContent,
} from '@/Components/ui/card'
import {
  IconPlus,
  IconPencil,
  IconTrash,
  IconMail,
  IconPhone,
  IconBuilding,
  IconUsers
} from '@tabler/icons-vue'
import EmptyState from '@/Components/EmptyState.vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import {emptyStates} from "@/Config/EmptyState"
import FilterBar from "@/Pages/Clients/Components/FilterBar.vue";

const props = defineProps({
  clients: Object,
  filters: Object,
  sortOptions: Array,
  statusOptions: Array,
})

const breakpoints = useBreakpoints({
  mobile: 640,
  tablet: 768,
  desktop: 1024,
})

const isMobile = breakpoints.smaller('tablet')

const headers = [
  {text: 'Name', value: 'name'},
  {text: 'Email', value: 'email'},
  {text: 'Phone', value: 'phone'},
  {text: 'Company', value: 'company_name'},
  {text: 'Actions', value: 'actions'},
]
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
                  <TableHead
                    v-for="header in headers"
                    :key="header.value"
                    :class="{'text-right': header.value === 'actions'}"
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
                  <TableCell class="font-medium">{{ client.name }}</TableCell>
                  <TableCell>{{ client.email }}</TableCell>
                  <TableCell>{{ client.phone }}</TableCell>
                  <TableCell>{{ client.company_name }}</TableCell>
                  <TableCell class="text-right">
                    <div class="flex items-center justify-end space-x-2">
                      <Button
                        variant="ghost"
                        size="icon"
                        :href="route('clients.edit', client.id)"
                      >
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
                        preserve-scroll
                      >
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
          :key="client.id">
          <CardContent class="p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="font-semibold">{{ client.name }}</h3>
              <div class="flex items-center space-x-2">
                <Button
                  variant="ghost"
                  size="icon"
                  :href="route('clients.edit', client.id)"
                >
                  <IconPencil class="h-4 w-4"/>
                </Button>

                <Button
                  variant="ghost"
                  size="icon"
                  :href="route('clients.destroy', client.id)"
                  method="delete"
                  as="button"
                  class="text-destructive hover:text-destructive"
                  preserve-scroll
                >
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
