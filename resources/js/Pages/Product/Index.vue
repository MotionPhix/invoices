<script setup lang="ts">
import {Head, Link, router} from '@inertiajs/vue3'
import {ref, watch} from 'vue'
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
import {Button} from '@/Components/ui/button'
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
import Pagination from "@/Components/Pagination.vue";

const props = withDefaults(
  defineProps<{
    products: {}
    filters?: {}
    statistics: {}
    categories: Array<{}>
    sortOptions: Array<{}>
  }>(), {
    filters: () => ({
      search: '',
      category: '',
      type: '',
      status: '',
      stock: '',
      sort: '',
    }),
    categories: () => [],
    sortOptions: () => [
      {value: 'name,asc', label: 'Name (A-Z)'},
      {value: 'name,desc', label: 'Name (Z-A)'},
      {value: 'created_at,desc', label: 'Newest First'},
      {value: 'created_at,asc', label: 'Oldest First'},
      {value: 'price,asc', label: 'Price (Low to High)'},
      {value: 'price,desc', label: 'Price (High to Low)'},
      {value: 'stock,asc', label: 'Stock (Low to High)'},
      {value: 'stock,desc', label: 'Stock (High to Low)'},
    ],
  }
)

// Add loading state
const isLoading = ref(false)
const showDeleteDialog = ref(false)
const productToDelete = ref(null)

const confirmDelete = (product) => {
  productToDelete.value = product
  showDeleteDialog.value = true
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-MW', {
    style: 'currency',
    currency: 'MWK'
  }).format(amount)
}

const getProductIcon = (type) => {
  return type === 'product' ? IconPackage : IconTools
}

// Watch for route changes
watch(() => router.active, (active) => {
  isLoading.value = active
})
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
        <div class="overflow-hidden rounded-lg border">
          <div class="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead class="min-w-[250px]">Product</TableHead>
                  <TableHead class="hidden md:table-cell">Category</TableHead>
                  <TableHead class="hidden sm:table-cell">Price</TableHead>
                  <TableHead class="hidden lg:table-cell">Stock</TableHead>
                  <TableHead class="hidden sm:table-cell">Status</TableHead>
                  <TableHead class="w-[100px]">Actions</TableHead>
                </TableRow>
              </TableHeader>

              <!-- Loading State -->
              <TableBody v-if="isLoading">
                <TableRow v-for="i in 5" :key="i">
                  <TableCell v-for="j in 6" :key="j" :class="{'hidden md:table-cell': j === 2, 'hidden sm:table-cell': j === 3 || j === 5, 'hidden lg:table-cell': j === 4}">
                    <div class="h-4 w-full animate-pulse rounded bg-muted"></div>
                  </TableCell>
                </TableRow>
              </TableBody>

              <!-- Empty State -->
              <TableBody v-else-if="products.data.length === 0">
                <TableRow>
                  <TableCell colspan="6" class="h-32 text-center">
                    <div class="flex flex-col items-center justify-center gap-2">
                      <IconPackage class="h-8 w-8 text-muted-foreground/60" />
                      <p class="text-muted-foreground">No products found</p>
                      <Button
                        variant="link"
                        @click="router.get(route('products.create'), {}, {replace: true})">
                        Add your first product
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>

              <!-- Data State -->
              <TableBody v-else>
                <TableRow
                  v-for="product in products.data"
                  :key="product.id">
                  <TableCell class="min-w-[250px]">
                    <div class="flex items-center gap-3">
                      <div v-if="product.media[0]" class="h-10 w-10 shrink-0">
                        <img
                          :src="product.media[0].preview_url"
                          :alt="product.name"
                          class="h-full w-full rounded-lg object-cover"
                        />
                      </div>

                      <div v-else class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-muted">
                        <component
                          :is="getProductIcon(product.type)"
                          class="h-5 w-5 text-muted-foreground"
                        />
                      </div>
                      <div class="min-w-0 flex-1">
                        <div class="font-medium truncate">{{ product.name }}</div>
                        <div class="text-sm text-muted-foreground truncate">
                          {{ product.sku }}
                        </div>
                      </div>
                    </div>
                  </TableCell>

                  <TableCell class="hidden md:table-cell">
                    <div
                      v-if="product.category"
                      class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                      :style="{
                        backgroundColor: `${product.category.color}15`,
                        color: product.category.color
                      }">
                      {{ product.category.name }}
                    </div>

                    <span v-else class="text-muted-foreground">
                      Uncategorized
                    </span>
                  </TableCell>

                  <TableCell class="hidden sm:table-cell">
                    {{ formatCurrency(product.price) }}
                  </TableCell>

                  <TableCell class="hidden lg:table-cell">
                    <div v-if="product.track_inventory">
                      <span
                        :class="{
                          'text-destructive': product.stock === 0,
                          'text-warning': product.stock <= product.low_stock_threshold
                        }">
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

                  <TableCell class="hidden sm:table-cell">
                    <span
                      class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                      :class="{
                        'bg-success/15 text-success': product.is_active,
                        'bg-destructive/15 text-destructive': !product.is_active
                      }" >
                      {{ product.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </TableCell>

                  <TableCell>
                    <div class="flex items-center gap-2">
                      <Button
                        variant="ghost"
                        size="icon"
                        @click="router.visit(route('products.edit', product.id), { replace: true })" >
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
          </div>

          <!-- Pagination -->
          <div class="border-t px-4 py-4">
            <Pagination :meta="products"/>
          </div>
        </div>
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
