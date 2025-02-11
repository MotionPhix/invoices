<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import ProductForm from './Components/ProductForm.vue'

const props = defineProps({
  product: Object,
  categories: Array,
  units: Object,
})

const form = useForm({
  name: props.product.name,
  sku: props.product.sku,
  description: props.product.description,
  price: props.product.price,
  cost: props.product.cost,
  type: props.product.type,
  is_active: props.product.is_active,
  category_id: props.product.category_id,
  unit: props.product.unit,
  track_inventory: props.product.track_inventory,
  stock: props.product.stock,
  low_stock_threshold: props.product.low_stock_threshold,
  image: null,
  _method: 'PUT',
})

const handleSubmit = () => {
  form.post(route('products.update', props.product.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <Head title="Edit Product" />

  <MainLayout>
    <template #header>
      <div>
        <h2 class="text-xl font-semibold leading-tight">
          Edit Product
        </h2>
        <p class="text-sm text-muted-foreground">
          Update product information and settings
        </p>
      </div>
    </template>

    <ProductForm
      :form="form"
      :product="product"
      :categories="categories"
      :units="units"
      @submit="handleSubmit"
    />
  </MainLayout>
</template>
