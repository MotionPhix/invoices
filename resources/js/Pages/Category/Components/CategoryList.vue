<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'
import { Input } from '@/components/ui/input'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { IconPencil, IconTrash } from '@tabler/icons-vue'

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
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

const updateFilter = debounce((key, value) => {
  router.get(
    route('categories.index'),
    { ...props.filters, [key]: value },
    { preserveState: true, preserveScroll: true }
  )
}, 300)
</script>

<template>
  <div class="space-y-4">
    <!-- Filters -->
    <div class="flex items-center gap-4">
      <div class="flex-1">
        <Input
          type="search"
          placeholder="Search categories..."
          :value="filters.search"
          @input="e => updateFilter('search', e.target.value)"
        />
      </div>

      <Select
        :model-value="filters.sort"
        @update:model-value="value => updateFilter('sort', value)"
      >
        <SelectTrigger class="w-[200px]">
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

    <!-- Categories Table -->
    <Card>
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Category</TableHead>
            <TableHead>Products</TableHead>
            <TableHead>Description</TableHead>
            <TableHead class="w-[100px]">Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow
            v-for="category in categories.data"
            :key="category.id"
          >
            <TableCell>
              <div class="flex items-center gap-2">
                <div
                  class="h-3 w-3 rounded-full"
                  :style="{ backgroundColor: category.color }"
                />
                <span class="font-medium">{{ category.name }}</span>
              </div>
            </TableCell>
            <TableCell>
              {{ category.products_count || 0 }}
            </TableCell>
            <TableCell class="max-w-[300px] truncate">
              {{ category.description || 'No description' }}
            </TableCell>
            <TableCell>
              <div class="flex items-center gap-2">
                <Button
                  variant="ghost"
                  size="icon"
                  @click="$emit('edit', category)"
                >
                  <IconPencil class="h-4 w-4" />
                </Button>
                <Button
                  variant="ghost"
                  size="icon"
                  @click="$emit('delete', category)"
                >
                  <IconTrash class="h-4 w-4" />
                </Button>
              </div>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </Card>
  </div>
</template>
