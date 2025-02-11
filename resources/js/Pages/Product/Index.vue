<script setup>
import {Head, Link, router} from '@inertiajs/vue3'
import { ref } from 'vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import { Button } from '@/Components/ui/button'
import {
  IconPlus,
  IconPencil,
  IconTrash,
  IconPackage,
  IconTools,
} from '@tabler/icons-vue'
import FilterBar from './Components/FilterBar.vue'
import Statistics from './Components/Statistics.vue'
import DeleteDialog from './Components/DeleteDialog.vue'

const props = defineProps({
  products: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({
      search: '',
      category: '',
      type: '',
      status: '',
      stock: '',
      sort: '',
    }),
  },
  statistics: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  sortOptions: {
    type: Array,
    default: () => [
      { value: 'name,asc', label: 'Name (A-Z)' },
      { value: 'name,desc', label: 'Name (Z-A)' },
      { value: 'created_at,desc', label: 'Newest First' },
      { value: 'created_at,asc', label: 'Oldest First' },
      { value: 'price,asc', label: 'Price (Low to High)' },
      { value: 'price,desc', label: 'Price (High to Low)' },
      { value: 'stock,asc', label: 'Stock (Low to High)' },
      { value: 'stock,desc', label: 'Stock (High to Low)' },
    ],
  },
})

const showDeleteDialog = ref(false)
const productToDelete = ref(null)

const confirmDelete = (product) => {
  productToDelete.value = product
  showDeleteDialog.value = true
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}

const getProductIcon = (type) => {
  return type === 'product' ? IconPackage : IconTools
}
</script>

<template>
  <Head title="Products & Services"/>

  <MainLayout>
    <template #header>
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight">
            Products & Services
          </h2>
          <p class="text-sm text-muted-foreground">
            Manage your products and services catalog
          </p>
        </div>

        <Button
          @click="router.get(route('products.create'), {}, {replace: true})"
          class="shrink-0">
          <IconPlus class="h-4 w-4 mr-2"/>
          Add Product
        </Button>
      </div>
    </template>

    <div class="space-y-6">
      <!-- Statistics Cards -->
      <Statistics :statistics="statistics"/>

      <!-- Filters -->
      <FilterBar
        :filters="filters"
        :categories="categories"
        :sort-options="sortOptions"
      />

      <!-- Products Table -->
      <Card>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Product</TableHead>
              <TableHead>Category</TableHead>
              <TableHead>Price</TableHead>
              <TableHead>Stock</TableHead>
              <TableHead>Status</TableHead>
              <TableHead class="w-[100px]">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="product in products.data"
              :key="product.id"
            >
              <TableCell>
                <div class="flex items-center gap-3">
                  <div v-if="product.media[0]" class="h-10 w-10">
                    <img
                      :src="product.media[0].preview_url"
                      :alt="product.name"
                      class="h-full w-full rounded-lg object-cover"
                    />
                  </div>
                  <div v-else class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted">
                    <component
                      :is="getProductIcon(product.type)"
                      class="h-5 w-5 text-muted-foreground"
                    />
                  </div>
                  <div>
                    <div class="font-medium">{{ product.name }}</div>
                    <div class="text-sm text-muted-foreground">
                      {{ product.sku }}
                    </div>
                  </div>
                </div>
              </TableCell>
              <TableCell>
                <div
                  v-if="product.category"
                  class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                  :style="{
                    backgroundColor: `${product.category.color}15`,
                    color: product.category.color
                  }"
                >
                  {{ product.category.name }}
                </div>
                <span v-else class="text-muted-foreground">
                  Uncategorized
                </span>
              </TableCell>
              <TableCell>
                {{ formatCurrency(product.price) }}
              </TableCell>
              <TableCell>
                <div v-if="product.track_inventory">
                  <span
                    :class="{
                      'text-destructive': product.stock === 0,
                      'text-warning': product.stock <= product.low_stock_threshold
                    }"
                  >
                    {{ product.stock }}
                  </span>
                  <span class="text-sm text-muted-foreground">
                    {{ product.unit }}s
                  </span>
                </div>
                <span v-else class="text-muted-foreground">
                  N/A
                </span>
              </TableCell>
              <TableCell>
                <span
                  class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                  :class="{
                    'bg-success/15 text-success': product.is_active,
                    'bg-destructive/15 text-destructive': !product.is_active
                  }">
                  {{ product.is_active ? 'Active' : 'Inactive' }}
                </span>
              </TableCell>

              <TableCell>
                <div class="flex items-center gap-2">
                  <Button
                    variant="ghost"
                    size="icon"
                    @click="router.visit(route('products.edit', product.id), { replace: true, preserveScroll: true })">
                    <IconPencil class="h-4 w-4"/>
                  </Button>

                  <Button
                    variant="ghost"
                    size="icon"
                    @click="confirmDelete(product)">
                    <IconTrash class="h-4 w-4"/>
                  </Button>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </Card>
    </div>

    <DeleteDialog
      v-if="productToDelete"
      :show="showDeleteDialog"
      :product="productToDelete"
      @close="showDeleteDialog = false"
    />
  </MainLayout>
</template>
