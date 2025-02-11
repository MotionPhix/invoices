<script setup>
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
  filters: Object,
  categories: Array,
  sortOptions: Array,
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
        :value="filters.category"
        @update:value="value => updateFilter('category', value)"
      >
        <SelectTrigger class="w-[180px]">
          <SelectValue placeholder="Category" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="">All Categories</SelectItem>
          <SelectItem
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </SelectItem>
        </SelectContent>
      </Select>

      <Select
        :value="filters.type"
        @update:value="value => updateFilter('type', value)"
      >
        <SelectTrigger class="w-[180px]">
          <SelectValue placeholder="Type" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="">All Types</SelectItem>
          <SelectItem value="product">Products</SelectItem>
          <SelectItem value="service">Services</SelectItem>
        </SelectContent>
      </Select>
    </div>

    <Select
      :value="filters.sort"
      @update:value="value => updateFilter('sort', value)"
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
