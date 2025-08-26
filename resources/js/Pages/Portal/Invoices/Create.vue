
<script setup lang="ts">
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/components/ui/number-field'
import { Select, SelectItem, SelectTrigger, SelectValue, SelectContent } from '@/components/ui/select'
import ProductForm from '@/pages/product/components/ProductForm.vue'

// Props: products (pre-fetched from backend)
const props = defineProps<{
  products: Array<{
    id: number
    name: string
    price: number
  }>
}>()

const form = useForm({
  client_id: null,
  items: [
    { product_id: null, quantity: 1, price: 0 }
  ],
  notes: '',
})

const showProductModal = ref(false)

function addProductRow() {
  form.items.push({ product_id: null, quantity: 1, price: 0 })
}

function removeProductRow(index: number) {
  if (form.items.length > 1) form.items.splice(index, 1)
}

function onProductAdded(product: any) {
  props.products.push(product)
  form.items[form.items.length - 1].product_id = product.id
  showProductModal.value = false
}

function submit() {
  router.post('/invoices', {
    client_id: form.client_id,
    items: form.items.map((item: any) => ({
      product_id: item.product_id,
      quantity: item.quantity,
      price: item.price,
    })),
    notes: form.notes,
  })
}
</script>

<template>
  <div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Create Invoice</h1>
    <form @submit.prevent="submit" class="space-y-6">
      <!-- Client selection (to be implemented) -->
      <div>
        <label class="block mb-1 font-medium">Client</label>
        <Input v-model="form.client_id" placeholder="Client ID" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Products/Services</label>
        <div v-for="(item, idx) in form.items" :key="idx" class="flex gap-2 mb-2 items-end">
          <Select v-model="item.product_id" class="w-64">
            <SelectTrigger>
              <SelectValue placeholder="Select product/service" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="product in props.products" :key="product.id" :value="String(product.id)">
                {{ product.name }} ({{ product.price }})
              </SelectItem>
            </SelectContent>
          </Select>
          <NumberField v-model="item.quantity" :min="1" :step="1" :format-options="{ maximumFractionDigits: 0 }" class="w-20">
            <Label>Qty</Label>
            <NumberFieldContent>
              <NumberFieldDecrement />
              <NumberFieldInput />
              <NumberFieldIncrement />
            </NumberFieldContent>
          </NumberField>
          <NumberField v-model="item.price" :min="0" :step="0.01" :format-options="{ style: 'currency', currency: 'USD', minimumFractionDigits: 2 }" class="w-28">
            <Label>Price</Label>
            <NumberFieldContent>
              <NumberFieldDecrement />
              <NumberFieldInput />
              <NumberFieldIncrement />
            </NumberFieldContent>
          </NumberField>
          <Button type="button" @click="removeProductRow(idx)" v-if="form.items.length > 1">Remove</Button>
        </div>
        <Button type="button" @click="addProductRow">Add Product/Service</Button>
        <Button type="button" class="ml-2" @click="showProductModal = true">Quick Add Product</Button>
      </div>
      <div>
        <label class="block mb-1 font-medium">Notes</label>
        <Input v-model="form.notes" placeholder="Additional notes" />
      </div>
      <Button type="submit">Create Invoice</Button>
    </form>
    <ProductForm
      v-if="showProductModal"
      :form="{}"
      :categories="[]"
      :units="{}"
      @saved="onProductAdded"
      @close="showProductModal = false"
    />
  </div>
</template>
