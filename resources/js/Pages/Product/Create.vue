<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import ProductForm from './Components/ProductForm.vue'

const props = defineProps({
  categories: Array,
  units: Object,
})

const form = useForm({
  name: '',
  sku: '',
  description: '',
  price: '',
  cost: '',
  type: 'product',
  is_active: true,
  category_id: '',
  unit: 'piece',
  track_inventory: false,
  stock: 0,
  low_stock_threshold: null,
  image: null,
})

const handleSubmit = () => {
  form.post(route('products.store'), {
    preserveScroll: true,
  })
}
</script>

<template>
  <Head title="Create Product" />

  <MainLayout>
    <template #header>
      <div>
        <h2 class="text-xl font-semibold leading-tight">
          Create Product
        </h2>
        <p class="text-sm text-muted-foreground">
          Add a new product or service to your catalog
        </p>
      </div>
    </template>

    <ProductForm
      :form="form"
      :categories="categories"
      :units="units"
      @submit="handleSubmit"
    />
  </MainLayout>
</template>
