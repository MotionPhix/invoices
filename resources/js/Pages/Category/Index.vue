<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import {
  IconPlus,
  IconCategory,
  IconChartBar,
  IconAlertCircle,
} from '@tabler/icons-vue'
import CategoryList from './Components/CategoryList.vue'
import CreateEditDialog from './Components/CreateEditDialog.vue'
import DeleteDialog from './Components/DeleteDialog.vue'

const props = defineProps({
  categories: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({
      search: '',
      sort: 'name,asc'
    })
  },
  sortOptions: {
    type: Array,
    default: () => [
      { value: 'name,asc', label: 'Name (A-Z)' },
      { value: 'name,desc', label: 'Name (Z-A)' },
      { value: 'products_count,desc', label: 'Most Products' },
      { value: 'products_count,asc', label: 'Least Products' },
      { value: 'created_at,desc', label: 'Newest First' },
      { value: 'created_at,asc', label: 'Oldest First' },
    ]
  }
})

const createDialogOpen = ref(false)
const editCategory = ref(null)
const categoryToDelete = ref(null)

const showEditDialog = (category) => {
  editCategory.value = category
}

const showDeleteDialog = (category) => {
  categoryToDelete.value = category
}
</script>

<template>
  <Head title="Categories" />

  <MainLayout>
    <template #header>
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight">
            Categories
          </h2>
          <p class="text-sm text-muted-foreground">
            Manage product and service categories
          </p>
        </div>

        <Button @click="createDialogOpen = true">
          <IconPlus class="h-4 w-4 mr-2" />
          Add Category
        </Button>
      </div>
    </template>

    <div class="space-y-6">
      <!-- Statistics -->
      <div class="grid gap-4 md:grid-cols-3">
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">
              Total Categories
            </CardTitle>
            <IconCategory class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ categories.total }}
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">
              Most Used
            </CardTitle>
            <IconChartBar class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ categories.data[0]?.name || 'None' }}
            </div>
            <p class="text-xs text-muted-foreground">
              {{ categories.data[0]?.products_count || 0 }} products
            </p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">
              Unused Categories
            </CardTitle>
            <IconAlertCircle class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ categories.data.filter(c => !c.products_count).length }}
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Category List -->
      <CategoryList
        :categories="categories"
        :filters="filters"
        :sort-options="sortOptions"
        @edit="showEditDialog"
        @delete="showDeleteDialog"
      />
    </div>

    <!-- Create/Edit Dialog -->
    <CreateEditDialog
      :show="createDialogOpen || !!editCategory"
      :category="editCategory"
      @close="createDialogOpen = false; editCategory = null"
      @updated="createDialogOpen = false; editCategory = null"
    />

    <!-- Delete Dialog -->
    <DeleteDialog
      v-if="categoryToDelete"
      :show="!!categoryToDelete"
      :category="categoryToDelete"
      @close="categoryToDelete = null"
    />
  </MainLayout>
</template>
