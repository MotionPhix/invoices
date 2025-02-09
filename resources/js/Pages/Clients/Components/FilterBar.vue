<script setup lang="ts">
import {computed, ref, watch} from 'vue'
import {router} from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import {Input} from '@/Components/ui/input'
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from '@/Components/ui/select'
import {IconSearch, IconFilter, IconX} from '@tabler/icons-vue'
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/Components/ui/sheet'
import {Button} from '@/Components/ui/button'

const props = defineProps({
  filters: {
    type: Object,
    default: () => ({})
  },
  sortOptions: {
    type: Array,
    default: () => []
  },
  statusOptions: {
    type: Array,
    default: () => []
  }
})

const search = ref(props.filters.search || '')
const status = ref(props.filters.status || '')
const sort = ref(props.filters.sort || '')
const isMobileFiltersOpen = ref(false)

// Handle search with debounce
const debouncedSearch = debounce(() => {
  updateFilters({search: search.value})
}, 300)

watch(search, () => {
  debouncedSearch()
})

// Handle filter changes
const updateFilters = (updates) => {
  router.get(
    route('clients.index'),
    {...props.filters, ...updates},
    {preserveState: true, preserveScroll: true}
  )
}

// Reset all filters
const resetFilters = () => {
  search.value = ''
  status.value = ''
  sort.value = ''
  updateFilters({search: '', status: '', sort: ''})
}

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return search.value || status.value || sort.value
})
</script>

<template>
  <!-- Desktop Filter Bar -->
  <div class="hidden lg:flex items-center gap-4 mb-6">
    <div class="relative flex-1">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <IconSearch class="h-4 w-4 text-muted-foreground"/>
      </div>

      <Input
        v-model="search"
        type="search"
        placeholder="Search clients..."
        class="pl-10 flex-1"
      />
    </div>

    <div>
      <Select
        v-model="status"
        @update:model-value="updateFilters({ status: $event })">
        <SelectTrigger>
          <SelectValue class="w-[180px]" placeholder="Filter by status"/>
        </SelectTrigger>

        <SelectContent>
          <SelectItem
            v-for="option in statusOptions"
            :key="option.value"
            :value="option.value">
            {{ option.label }}
          </SelectItem>
        </SelectContent>
      </Select>
    </div>

    <div>
      <Select
        v-model="sort"
        @update:model-value="updateFilters({ sort: $event })">
        <SelectTrigger>
          <SelectValue class="w-[180px]" placeholder="Sort by"/>
        </SelectTrigger>

        <SelectContent>
          <SelectItem
            v-for="option in sortOptions"
            :key="option.value"
            :value="option.value">
            {{ option.label }}
          </SelectItem>
        </SelectContent>
      </Select>
    </div>

    <Button
      v-if="hasActiveFilters"
      variant="ghost"
      size="sm"
      @click="resetFilters"
      class="gap-2">
      <IconX class="h-4 w-4"/>
      Clear
    </Button>
  </div>

  <!-- Mobile Filter Bar -->
  <div class="lg:hidden flex items-center gap-2 mb-6">
    <div class="relative flex-1">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <IconSearch class="h-4 w-4 text-muted-foreground"/>
      </div>

      <Input
        v-model="search"
        type="search"
        placeholder="Search clients..."
        class="pl-10"
      />
    </div>

    <Sheet v-model:open="isMobileFiltersOpen">
      <SheetTrigger asChild>
        <Button variant="outline" size="icon" class="relative">
          <IconFilter class="h-4 w-4"/>
          <span
            v-if="hasActiveFilters"
            class="absolute -top-1 -right-1 h-3 w-3 bg-primary rounded-full"
          />
        </Button>
      </SheetTrigger>

      <SheetContent side="right">
        <SheetHeader>
          <SheetTitle>Filters</SheetTitle>
          <SheetDescription>
            Refine your client list using the filters below
          </SheetDescription>
        </SheetHeader>

        <div class="mt-6 grid gap-4 py-4">
          <div class="space-y-2">
            <h3 class="text-sm font-medium">Status</h3>
            <Select v-model="status" @update:model-value="updateFilters({ status: $event })">
              <SelectTrigger>
                <SelectValue placeholder="Filter by status"/>
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="option in statusOptions"
                  :key="option.value"
                  :value="option.value">
                  {{ option.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div class="space-y-2">
            <h3 class="text-sm font-medium">Sort By</h3>
            <Select v-model="sort" @update:model-value="updateFilters({ sort: $event })">
              <SelectTrigger>
                <SelectValue placeholder="Sort by"/>
              </SelectTrigger>

              <SelectContent>
                <SelectItem
                  v-for="option in sortOptions"
                  :key="option.value"
                  :value="option.value">
                  {{ option.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <Button
            v-if="hasActiveFilters"
            variant="ghost"
            class="w-full gap-2"
            @click="resetFilters">
            <IconX class="h-4 w-4"/>
            Clear All Filters
          </Button>
        </div>
      </SheetContent>
    </Sheet>
  </div>
</template>
