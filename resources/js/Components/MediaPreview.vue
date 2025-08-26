<script setup>
import { ref, watch } from 'vue'
import { Modal } from '@/Components/ui/modal'
import { Button } from '@/components/ui/button'
import { IconChevronLeft, IconChevronRight, IconFile } from '@tabler/icons-vue'

const props = defineProps({
  media: {
    type: Object,
    required: true
  },
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const isImage = (mimeType) => mimeType.startsWith('image/')
const isPdf = (mimeType) => mimeType === 'application/pdf'

const loading = ref(false)
const error = ref(null)

// For PDF preview
const pdfUrl = ref(null)
const currentPage = ref(1)
const totalPages = ref(1)

watch(() => props.show, async (newVal) => {
  if (newVal && isPdf(props.media.mime_type)) {
    loading.value = true
    error.value = null

    try {
      // Use PDF.js to load and render the PDF
      const pdfjsLib = await import('pdfjs-dist')
      const pdf = await pdfjsLib.getDocument(props.media.original_url).promise
      totalPages.value = pdf.numPages
      pdfUrl.value = props.media.preview_url
    } catch (e) {
      error.value = 'Failed to load PDF preview'
    } finally {
      loading.value = false
    }
  }
})

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}
</script>

<template>
  <Modal
    :show="show"
    @close="$emit('close')"
    :max-width="'4xl'"
  >
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium">
          {{ media.file_name }}
        </h3>
        <Button variant="ghost" @click="$emit('close')">
          <span class="sr-only">Close</span>
          <IconX class="h-4 w-4" />
        </Button>
      </div>

      <div class="relative bg-gray-100 rounded-lg overflow-hidden min-h-[400px]">
        <!-- Image Preview -->
        <img
          v-if="isImage(media.mime_type)"
          :src="media.preview_url || media.original_url"
          class="max-w-full h-auto mx-auto"
          :alt="media.file_name"
        />

        <!-- PDF Preview -->
        <template v-else-if="isPdf(media.mime_type)">
          <div v-if="loading" class="flex items-center justify-center h-full">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          </div>

          <div v-else-if="error" class="flex items-center justify-center h-full text-red-500">
            {{ error }}
          </div>

          <div v-else class="relative">
            <img
              :src="pdfUrl"
              class="max-w-full h-auto mx-auto"
              :alt="`Page ${currentPage} of PDF`"
            />

            <div class="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-4">
              <Button
                variant="secondary"
                size="sm"
                :disabled="currentPage === 1"
                @click="prevPage"
              >
                <IconChevronLeft class="h-4 w-4" />
                Previous
              </Button>

              <span class="text-sm">
                Page {{ currentPage }} of {{ totalPages }}
              </span>

              <Button
                variant="secondary"
                size="sm"
                :disabled="currentPage === totalPages"
                @click="nextPage"
              >
                Next
                <IconChevronRight class="h-4 w-4" />
              </Button>
            </div>
          </div>
        </template>

        <!-- Other File Types -->
        <div
          v-else
          class="flex flex-col items-center justify-center h-full py-12"
        >
          <IconFile class="h-16 w-16 text-gray-400" />
          <p class="mt-4 text-sm text-gray-600">
            Preview not available for this file type
          </p>
          <Button
            variant="secondary"
            size="sm"
            class="mt-4"
            :href="media.original_url"
            target="_blank"
          >
            Download to View
          </Button>
        </div>
      </div>

      <!-- File Information -->
      <div class="mt-4 space-y-2 text-sm text-gray-600">
        <p v-if="media.custom_properties?.description">
          {{ media.custom_properties.description }}
        </p>
        <p>Size: {{ formatFileSize(media.size) }}</p>
        <p>Uploaded: {{ new Date(media.created_at).toLocaleString() }}</p>
      </div>
    </div>
  </Modal>
</template>
