<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import { useToast } from '@/Components/ui/toast/use-toast'
import { IconUpload, IconX, IconFile, IconDownload, IconTrash } from '@tabler/icons-vue'

const props = defineProps({
  modelType: {
    type: String,
    required: true,
    validator: (value) => ['invoice', 'client', 'support_request'].includes(value)
  },
  modelId: {
    type: String,
    required: true
  },
  existingMedia: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['uploaded', 'deleted'])
const { toast } = useToast()
const fileInput = ref(null)
const dragging = ref(false)
const uploading = ref(false)
const description = ref('')
const selectedFile = ref(null)

const handleDrop = (e) => {
  e.preventDefault()
  dragging.value = false
  const file = e.dataTransfer.files[0]
  if (file) {
    selectedFile.value = file
  }
}

const handleFileSelect = (e) => {
  const file = e.target.files[0]
  if (file) {
    selectedFile.value = file
  }
}

const uploadFile = async () => {
  if (!selectedFile.value) return

  uploading.value = true
  const formData = new FormData()
  formData.append('file', selectedFile.value)
  formData.append('model_type', props.modelType)
  formData.append('model_id', props.modelId)
  formData.append('description', description.value)

  try {
    await router.post(route('media.store'), formData, {
      onSuccess: () => {
        selectedFile.value = null
        description.value = ''
        fileInput.value.value = ''
        emit('uploaded')
        toast({
          title: 'Success',
          description: 'Document uploaded successfully'
        })
      },
      onError: (errors) => {
        toast({
          title: 'Error',
          description: Object.values(errors)[0],
          variant: 'destructive'
        })
      }
    })
  } finally {
    uploading.value = false
  }
}

const deleteMedia = async (mediaId) => {
  if (!confirm('Are you sure you want to delete this document?')) return

  try {
    await router.delete(route('media.destroy', mediaId), {
      onSuccess: () => {
        emit('deleted', mediaId)
        toast({
          title: 'Success',
          description: 'Document deleted successfully'
        })
      }
    })
  } catch (error) {
    toast({
      title: 'Error',
      description: 'Failed to delete document',
      variant: 'destructive'
    })
  }
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}
</script>

<template>
  <div class="space-y-6">
    <!-- Upload Area -->
    <div
      class="border-2 border-dashed rounded-lg p-6"
      :class="{
        'border-primary bg-primary/5': dragging,
        'border-border': !dragging
      }"
      @dragenter.prevent="dragging = true"
      @dragleave.prevent="dragging = false"
      @dragover.prevent
      @drop="handleDrop"
    >
      <div class="text-center">
        <IconUpload class="mx-auto h-12 w-12 text-gray-400" />
        <div class="mt-4">
          <label
            for="file-upload"
            class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary/80"
          >
            <span>Upload a file</span>
            <input
              ref="fileInput"
              id="file-upload"
              name="file-upload"
              type="file"
              class="sr-only"
              @change="handleFileSelect"
            >
          </label>
          <p class="pl-1">or drag and drop</p>
        </div>
        <p class="text-xs text-muted-foreground mt-2">
          PDF, Word, Excel, or images up to 10MB
        </p>
      </div>
    </div>

    <!-- Selected File -->
    <div v-if="selectedFile" class="space-y-4">
      <div class="flex items-center gap-2 p-2 border rounded">
        <IconFile class="h-5 w-5 text-muted-foreground" />
        <span class="flex-1 truncate">{{ selectedFile.name }}</span>
        <span class="text-sm text-muted-foreground">
          {{ formatFileSize(selectedFile.size) }}
        </span>
        <Button
          variant="ghost"
          size="icon"
          @click="selectedFile = null; fileInput.value = ''"
        >
          <IconX class="h-4 w-4" />
        </Button>
      </div>

      <div class="space-y-2">
        <label for="description" class="text-sm font-medium">Description</label>
        <Textarea
          id="description"
          v-model="description"
          rows="3"
          placeholder="Add a description for this document..."
        />
      </div>

      <div class="flex justify-end">
        <Button
          type="button"
          :disabled="uploading"
          @click="uploadFile"
        >
          {{ uploading ? 'Uploading...' : 'Upload Document' }}
        </Button>
      </div>
    </div>

    <!-- Existing Media -->
    <div v-if="existingMedia.length" class="space-y-4">
      <h3 class="font-medium">Attached Documents</h3>
      <div class="space-y-2">
        <div
          v-for="media in existingMedia"
          :key="media.id"
          class="flex items-center justify-between p-3 border rounded-lg"
        >
          <div class="flex items-center gap-3">
            <IconFile class="h-5 w-5 text-muted-foreground" />
            <div>
              <a
                :href="media.original_url"
                target="_blank"
                class="font-medium hover:underline"
              >
                {{ media.file_name }}
              </a>
              <p v-if="media.custom_properties?.description" class="text-sm text-muted-foreground">
                {{ media.custom_properties.description }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <Button
              variant="ghost"
              size="icon"
              :href="media.original_url"
              target="_blank"
            >
              <IconDownload class="h-4 w-4" />
            </Button>
            <Button
              variant="ghost"
              size="icon"
              @click="deleteMedia(media.id)"
            >
              <IconTrash class="h-4 w-4" />
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
