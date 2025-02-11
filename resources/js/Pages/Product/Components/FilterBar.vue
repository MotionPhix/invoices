<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'
import { Input } from '@/Components/ui/input'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'

const props = defineProps({
  filters: {
    type: Object,
    default: () => ({
      search: '',
      category: '',
      type: '',
      sort: '',
    }),
  },
  categories: {
    type: Array,
    default: () => [],
  },
  sortOptions: {
    type: Array,
    default: () => [],
  },
})

const updateFilter = debounce((key, value) => {
  router.get(
    route('products.index'),
    { ...props.filters, [key]: value },
    { preserveState: true, preserveScroll: true }
  )
}, 300)
</script>

<template>
  <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex flex-1 items-center gap-4">
      <div class="w-full max-w-[300px]">
        <Input
          type="search"
          placeholder="Search products..."
          :value="filters.search"
          @input="e => updateFilter('search', e.target.value)"
        />
      </div>

      <Select
        :value="filters.category || 'all'"
        @update:model-value="value => updateFilter('category', value)">
        <SelectTrigger class="w-[180px]">
          <SelectValue placeholder="Category" />
        </SelectTrigger>

        <SelectContent>
          <SelectItem value="all">All Categories</SelectItem>
          <SelectItem
            v-for="category in categories"
            :key="category.id"
            :value="category.id.toString()">
            {{ category.name }}
          </SelectItem>
        </SelectContent>
      </Select>

      <Select
        :value="filters.type || 'all'"
        @update:model-value="value => updateFilter('type', value)">
        <SelectTrigger class="w-[180px]">
          <SelectValue placeholder="Type" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="all">All Types</SelectItem>
          <SelectItem value="product">Products</SelectItem>
          <SelectItem value="service">Services</SelectItem>
        </SelectContent>
      </Select>
    </div>

    <Select
      :value="filters.sort || 'created_at,desc'"
      @update:model-value="value => updateFilter('sort', value)"
    >
      <SelectTrigger class="w-[180px]">
        <SelectValue placeholder="Sort by" />
      </SelectTrigger>
      <SelectContent>
        <SelectItem
          v-for="option in sortOptions"
          :key="option.value"
          :value="option.value"
        >
          {{ option.label }}
        </SelectItem>
      </SelectContent>
    </Select>
  </div>
</template>
