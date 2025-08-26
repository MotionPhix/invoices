<script setup lang="ts">
import { Card } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Textarea } from '@/components/ui/textarea'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Switch } from '@/components/ui/switch'
import { useFileUpload } from '@/composables/useFileUpload'
import ImageUpload from '@/components/ImageUpload.vue'
import {onBeforeUnmount} from "vue";

const props = defineProps<{
  form: Object,
  product?: Object,
  categories: Array,
  units: Object,
}>()

const emit = defineEmits(['submit'])

const { preview, handleFileChange, clearFile, cleanup } = useFileUpload(props.form, 'image')

const handleTypeChange = (type) => {
  props.form.type = type
  props.form.unit = props.units[type][0]

  if (type === 'service') {
    props.form.track_inventory = false
    props.form.stock = 0
    props.form.low_stock_threshold = null
  }
}

onBeforeUnmount(cleanup)
</script>

<template>
  <form @submit.prevent="$emit('submit')" class="space-y-6">
    <!-- Basic Information -->
    <Card class="p-6">
      <div class="space-y-6">
        <div class="flex items-start gap-6">
          <div class="w-[200px] shrink-0">
            <ImageUpload
              v-model="form.image"
              :existing="product?.media?.[0]?.preview_url"
              @change="handleFileChange"
              @clear="clearFile"
            />
          </div>

          <div class="flex-1 space-y-4">
            <div>
              <Label for="name">Name</Label>
              <Input
                id="name"
                v-model="form.name"
                :error="form.errors.name"
              />
            </div>

            <div>
              <Label for="sku">SKU</Label>
              <Input
                id="sku"
                v-model="form.sku"
                :error="form.errors.sku"
                placeholder="Auto-generated if left empty"
              />
            </div>

            <div>
              <Label for="description">Description</Label>
              <Textarea
                id="description"
                v-model="form.description"
                :error="form.errors.description"
                rows="3"
              />
            </div>
          </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <Label for="type">Type</Label>
            <Select
              v-model="form.type"
              @update:value="handleTypeChange">
              <SelectTrigger :error="form.errors.type">
                <SelectValue />
              </SelectTrigger>

              <SelectContent>
                <SelectItem value="product">Product</SelectItem>
                <SelectItem value="service">Service</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div>
            <Label for="category">Category</Label>
            <Select
              v-model="form.category_id"
              :error="form.errors.category_id">
              <SelectTrigger>
                <SelectValue placeholder="Select category" />
              </SelectTrigger>

              <SelectContent>
                <SelectItem value="null">Uncategorized</SelectItem>
                <SelectItem
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id.toString()"
                >
                  {{ category.name }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>
      </div>
    </Card>

    <!-- Pricing -->
    <Card class="p-6">
      <h3 class="text-lg font-medium mb-4">Pricing</h3>
      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <Label for="price">Price</Label>
          <Input
            id="price"
            type="number"
            step="0.01"
            v-model="form.price"
            :error="form.errors.price"
          />
        </div>

        <div>
          <Label for="cost">Cost (Optional)</Label>
          <Input
            id="cost"
            type="number"
            step="0.01"
            v-model="form.cost"
            :error="form.errors.cost"
          />
        </div>
      </div>
    </Card>

    <!-- Inventory -->
    <Card class="p-6">
      <h3 class="text-lg font-medium mb-4">Inventory</h3>
      <div class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <Label for="unit">Unit</Label>
            <Select
              v-model="form.unit"
              :error="form.errors.unit">
              <SelectTrigger>
                <SelectValue />
              </SelectTrigger>

              <SelectContent>
                <SelectItem
                  v-for="unit in units[form.type]"
                  :key="unit"
                  :value="unit">
                  {{ unit }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>

        <div v-if="form.type === 'product'" class="space-y-4">
          <div class="flex items-center space-x-2">
            <Switch
              id="track_inventory"
              v-model="form.track_inventory"
            />
            <Label for="track_inventory">Track Inventory</Label>
          </div>

          <div v-if="form.track_inventory" class="grid gap-4 sm:grid-cols-2">
            <div>
              <Label for="stock">Current Stock</Label>
              <Input
                id="stock"
                type="number"
                v-model="form.stock"
                :error="form.errors.stock"
              />
            </div>

            <div>
              <Label for="low_stock_threshold">Low Stock Alert</Label>
              <Input
                id="low_stock_threshold"
                type="number"
                v-model="form.low_stock_threshold"
                :error="form.errors.low_stock_threshold"
              />
            </div>
          </div>
        </div>
      </div>
    </Card>

    <!-- Status -->
    <Card class="p-6">
      <div class="flex items-center space-x-2">
        <Switch
          id="is_active"
          v-model="form.is_active"
        />
        <Label for="is_active">Product is active</Label>
      </div>
    </Card>

    <!-- Form Actions -->
    <div class="flex items-center justify-end gap-4">
      <Button
        type="button"
        variant="outline"
        :href="route('products.index')"
      >
        Cancel
      </Button>
      <Button
        type="submit"
        :disabled="form.processing"
      >
        {{ product ? 'Update Product' : 'Create Product' }}
      </Button>
    </div>
  </form>
</template>
