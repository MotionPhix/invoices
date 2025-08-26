<<<<<<< HEAD
<template>
  <nav class="flex justify-center mt-6" v-if="totalPages > 1">
    <ul class="inline-flex -space-x-px">
      <li>
        <button
          class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700"
          :disabled="currentPage === 1"
          @click="$emit('page-change', currentPage - 1)"
        >
          Prev
        </button>
      </li>
      <li v-for="page in pages" :key="page">
        <button
          class="px-3 py-2 leading-tight border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
          :class="{
            'bg-blue-500 text-white': page === currentPage,
            'bg-white text-gray-500': page !== currentPage
          }"
          @click="$emit('page-change', page)"
        >
          {{ page }}
        </button>
      </li>
      <li>
        <button
          class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700"
          :disabled="currentPage === totalPages"
          @click="$emit('page-change', currentPage + 1)"
        >
          Next
        </button>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  currentPage: { type: Number, required: true },
  totalPages: { type: Number, required: true }
})
const pages = computed(() => {
  const arr = []
  for (let i = 1; i <= props.totalPages; i++) {
    arr.push(i)
  }
  return arr
})
</script>
=======
<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import {
  IconChevronLeft,
  IconChevronRight,
  IconDots,
} from '@tabler/icons-vue'

const props = defineProps({
  meta: {
    type: Object,
    required: true,
  },
})

const pages = computed(() => {
  const currentPage = props.meta.current_page
  const lastPage = props.meta.last_page

  let pages = []

  // Always show first page
  pages.push(1)

  if (currentPage > 3) {
    pages.push('...')
  }

  // Show pages around current page
  for (let i = Math.max(2, currentPage - 1); i <= Math.min(lastPage - 1, currentPage + 1); i++) {
    pages.push(i)
  }

  if (currentPage < lastPage - 2) {
    pages.push('...')
  }

  // Always show last page
  if (lastPage > 1) {
    pages.push(lastPage)
  }

  return pages
})

const pageLink = (page) => {
  const query = new URLSearchParams(window.location.search)
  query.set('page', page)
  return `${props.meta.path}?${query.toString()}`
}
</script>

<template>
  <div class="flex items-center justify-between px-2 w-full">
    <!-- Mobile pagination -->
    <div class="flex flex-1 justify-between sm:hidden">
      <Link
        preserve-scroll
        preserve-state
        :href="meta.prev_page_url ?? '#'"
        :class="[
          'relative inline-flex items-center rounded-md px-4 py-2 text-sm font-medium',
          !meta.prev_page_url
            ? 'pointer-events-none text-muted-foreground'
            : 'text-foreground hover:bg-accent'
        ]">
        Previous
      </Link>

      <Link
        preserve-scroll
        preserve-state
        :href="meta.next_page_url ?? '#'"
        :class="[
          'relative inline-flex items-center rounded-md px-4 py-2 text-sm font-medium',
          !meta.next_page_url
            ? 'pointer-events-none text-muted-foreground'
            : 'text-foreground hover:bg-accent'
        ]">
        Next
      </Link>
    </div>

    <!-- Desktop pagination -->
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <!-- Results info -->
      <div>
        <p class="text-sm text-muted-foreground">
          Showing
          <span class="font-medium">{{ meta.from }}</span>
          to
          <span class="font-medium">{{ meta.to }}</span>
          of
          <span class="font-medium">{{ meta.total }}</span>
          results
        </p>
      </div>

      <!-- Page numbers -->
      <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
        <!-- Previous page -->
        <Link
          preserve-state
          preserve-scroll
          :href="meta.prev_page_url ?? '#'"
          class="relative inline-flex items-center rounded-l-md px-2 py-2 ring-1 ring-inset ring-border hover:bg-accent"
          :class="!meta.prev_page_url && 'pointer-events-none opacity-50'">
          <span class="sr-only">Previous</span>
          <IconChevronLeft class="h-4 w-4" aria-hidden="true" />
        </Link>

        <!-- Page numbers -->
        <template v-for="page in pages" :key="page">
          <span
            v-if="page === '...'"
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-muted-foreground ring-1 ring-inset ring-border">
            <IconDots class="h-4 w-4" />
          </span>

          <Link
            v-else
            preserve-state
            preserve-scroll
            :href="pageLink(page)"
            :class="[
              'relative inline-flex items-center px-4 py-2 text-sm font-medium ring-1 ring-inset ring-border',
              page === meta.current_page
                ? 'z-10 bg-primary text-primary-foreground ring-primary'
                : 'text-foreground hover:bg-accent'
            ]">
            {{ page }}
          </Link>
        </template>

        <!-- Next page -->
        <Link
          preserve-state
          preserve-scroll
          :href="meta.next_page_url ?? '#'"
          class="relative inline-flex items-center rounded-r-md px-2 py-2 ring-1 ring-inset ring-border hover:bg-accent"
          :class="!meta.next_page_url && 'pointer-events-none opacity-50'">
          <span class="sr-only">Next</span>
          <IconChevronRight class="h-4 w-4" aria-hidden="true" />
        </Link>
      </nav>
    </div>
  </div>
</template>
>>>>>>> 4b53cd3c406824d13ed214052d6ad009789d0835
