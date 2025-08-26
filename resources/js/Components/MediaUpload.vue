<script setup lang="ts">
import {ref} from 'vue'
import {router} from '@inertiajs/vue3'
import {Button} from '@/components/ui/button'
import {Input} from '@/components/ui/input'
import {Textarea} from '@/components/ui/textarea'
import {useToast} from '@/components/ui/toast/use-toast'
import {IconUpload, IconX, IconFile, IconDownload, IconTrash, IconEye } from '@tabler/icons-vue'

const props = withDefaults(
  defineProps<{
    modelType: 'invoice' | 'client' | 'support_request'
    modelId: string
    existingMedia: Array<any>
    maxFiles: number
  }>(),
  {
    existingMedia: () => [],
    maxFiles: 10
  }
)

const emit = defineEmits(['uploaded', 'deleted'])
const {toast} = useToast()
const fileInput = ref(null)
const dragging = ref(false)
const uploading = ref(false)
const description = ref('')
const selectedFiles = ref([])
const uploadProgress = ref({})
const previewUrls = ref({})

const handleDrop = (e) => {
  e.preventDefault()
  dragging.value = false
  const files = Array.from(e.dataTransfer.files)
  addFiles(files)
}

const handleFileSelect = (e) => {
  const files = Array.from(e.target.files)
  addFiles(files)
}

const addFiles = (files) => {
  if (selectedFiles.value.length + files.length > props.maxFiles) {
    toast({
      title: 'Error',
      description: `You can only upload up to ${props.maxFiles} files at once`,
      variant: 'destructive'
    })
    return
  }

  files.forEach(file => {
    // Generate preview for images
    if (file.type.startsWith('image/')) {
      const reader = new FileReader()
      reader.onload = (e) => {
        previewUrls.value[file.name] = e.target.result
      }
      reader.readAsDataURL(file)
    }

    // Add file to selected files if not already present
    if (!selectedFiles.value.find(f => f.name === file.name)) {
      selectedFiles.value.push({
        file,
        description: '',
        status: 'pending'
      })
    }
  })
}

const removeFile = (fileName) => {
  selectedFiles.value = selectedFiles.value.filter(f => f.file.name !== fileName)
  delete previewUrls.value[fileName]
  delete uploadProgress.value[fileName]
}

const uploadFiles = async () => {
  if (!selectedFiles.value.length) return

  uploading.value = true
  const totalFiles = selectedFiles.value.length
  let successCount = 0

  for (const fileData of selectedFiles.value) {
    if (fileData.status === 'uploaded') continue

    const formData = new FormData()
    formData.append('file', fileData.file)
    formData.append('model_type', props.modelType)
    formData.append('model_id', props.modelId)
    formData.append('description', fileData.description)

    try {
      uploadProgress.value[fileData.file.name] = 0

      await router.post(route('media.store'), formData, {
        onProgress: (progress) => {
          uploadProgress.value[fileData.file.name] = progress.percentage
        },
        onSuccess: () => {
          fileData.status = 'uploaded'
          successCount++
        },
        onError: (errors) => {
          fileData.status = 'error'
          fileData.error = Object.values(errors)[0]
        }
      })
    } catch (error) {
      fileData.status = 'error'
      fileData.error = 'Upload failed'
    }
  }

  if (successCount === totalFiles) {
    selectedFiles.value = []
    uploadProgress.value = {}
    previewUrls.value = {}
    fileInput.value.value = ''
    emit('uploaded')
    toast({
      title: 'Success',
      description: `${successCount} ${successCount === 1 ? 'file' : 'files'} uploaded successfully`
    })
  } else if (successCount > 0) {
    toast({
      title: 'Partial Success',
      description: `${successCount} of ${totalFiles} files uploaded successfully`,
      variant: 'warning'
    })
  }

  uploading.value = false
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
            <span>Upload files</span>
            <input
              ref="fileInput"
              id="file-upload"
              name="file-upload"
              type="file"
              multiple
              class="sr-only"
              @change="handleFileSelect"
            >
          </label>
          <p class="pl-1">or drag and drop</p>
        </div>
        <p class="text-xs text-muted-foreground mt-2">
          Upload up to {{ maxFiles }} files (PDF, Word, Excel, or images up to 10MB each)
        </p>
      </div>
    </div>

    <!-- Selected Files -->
    <div v-if="selectedFiles.length" class="space-y-4">
      <div
        v-for="fileData in selectedFiles"
        :key="fileData.file.name"
        class="space-y-4 p-4 border rounded-lg"
        :class="{
          'bg-green-50': fileData.status === 'uploaded',
          'bg-red-50': fileData.status === 'error'
        }"
      >
        <div class="flex items-center gap-2">
          <div v-if="previewUrls[fileData.file.name]" class="relative w-16 h-16">
            <img
              :src="previewUrls[fileData.file.name]"
              class="w-full h-full object-cover rounded"
              alt="Preview"
            />
          </div>
          <div v-else>
            <IconFile class="h-8 w-8 text-muted-foreground" />
          </div>

          <div class="flex-1">
            <div class="flex items-center justify-between">
              <span class="font-medium">{{ fileData.file.name }}</span>
              <Button
                variant="ghost"
                size="icon"
                @click="removeFile(fileData.file.name)"
              >
                <IconX class="h-4 w-4" />
              </Button>
            </div>

            <div class="text-sm text-muted-foreground">
              {{ formatFileSize(fileData.file.size) }}
            </div>

            <div v-if="uploadProgress[fileData.file.name]" class="mt-2">
              <Progress :value="uploadProgress[fileData.file.name]" />
            </div>

            <div v-if="fileData.status === 'error'" class="mt-2 text-sm text-red-600">
              {{ fileData.error }}
            </div>
          </div>
        </div>

        <Textarea
          v-model="fileData.description"
          rows="2"
          placeholder="Add a description for this file..."
          :disabled="fileData.status === 'uploaded'"
        />
      </div>

      <div class="flex justify-end">
        <Button
          type="button"
          :disabled="uploading"
          @click="uploadFiles" >
          {{ uploading ? 'Uploading...' : 'Upload All Files' }}
        </Button>
      </div>
    </div>

    <!-- Existing Media -->
    <div v-if="existingMedia.length" class="space-y-4">
      <h3 class="font-medium">Attached Documents</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="media in existingMedia"
          :key="media.id"
          class="flex items-center justify-between p-3 border rounded-lg">
          <div class="flex items-center gap-3">
            <div v-if="media.preview_url" class="relative w-12 h-12">
              <img
                :src="media.preview_url"
                class="w-full h-full object-cover rounded"
                alt="Preview"
              />
            </div>
            <div v-else>
              <IconFile class="h-5 w-5 text-muted-foreground" />
            </div>

            <div>
              <div class="font-medium truncate max-w-[200px]">
                {{ media.file_name }}
              </div>
              <p v-if="media.custom_properties?.description" class="text-sm text-muted-foreground truncate max-w-[200px]">
                {{ media.custom_properties.description }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <Button
              v-if="media.preview_url"
              variant="ghost"
              size="icon"
              @click="$emit('preview', media)">
              <IconEye class="h-4 w-4" />
            </Button>

            <Button
              variant="ghost"
              size="icon"
              :href="media.original_url"
              target="_blank">
              <IconDownload class="h-4 w-4" />
            </Button>

            <Button
              variant="ghost"
              size="icon"
              @click="deleteMedia(media.id)">
              <IconTrash class="h-4 w-4" />
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
