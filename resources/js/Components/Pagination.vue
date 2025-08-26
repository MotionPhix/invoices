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
