<script setup>
import {computed, onBeforeUnmount, ref} from 'vue'
import { Button } from '@/Components/ui/button'
import { IconUpload, IconX, IconPhoto } from '@tabler/icons-vue'

const props = defineProps({
  modelValue: {
    type: [File, null],
    default: null
  },
  existing: {
    type: String,
    default: null
  },
  maxSize: {
    type: Number,
    default: 2048 // 2MB in KB
  },
  accept: {
    type: String,
    default: 'image/*'
  }
})

const emit = defineEmits(['update:modelValue', 'change', 'clear'])

const fileInputRef = ref(null)

const preview = computed(() => {
  if (props.modelValue instanceof File) {
    return URL.createObjectURL(props.modelValue)
  }
  return props.existing
})

const handleClick = () => {
  fileInputRef.value?.click()
}

const handleFileChange = (event) => {
  const file = event.target.files?.[0]

  if (!file) return

  // Check file size
  if (file.size > props.maxSize * 1024) {
    alert(`File size must be less than ${props.maxSize / 1024}MB`)
    return
  }

  emit('update:modelValue', file)
  emit('change', file)

  // Reset file input
  if (fileInputRef.value) {
    fileInputRef.value.value = ''
  }
}

const clearImage = () => {
  emit('update:modelValue', null)
  emit('clear')
}

// Cleanup object URL on unmount
onBeforeUnmount(() => {
  if (preview.value && props.modelValue instanceof File) {
    URL.revokeObjectURL(preview.value)
  }
})
</script>

<template>
  <div class="relative">
    <!-- Hidden File Input -->
    <input
      ref="fileInputRef"
      type="file"
      :accept="accept"
      class="hidden"
      @change="handleFileChange"
    />

    <!-- Image Preview or Placeholder -->
    <div
      class="relative aspect-square w-full overflow-hidden rounded-lg border border-border bg-muted"
      :class="{ 'hover:bg-muted/80': !preview }"
    >
      <!-- Preview Image -->
      <img
        v-if="preview"
        :src="preview"
        alt="Preview"
        class="h-full w-full object-cover"
      />

      <!-- Placeholder -->
      <div
        v-else
        class="flex h-full w-full items-center justify-center"
      >
        <IconPhoto
          class="h-12 w-12 text-muted-foreground"
        />
      </div>

      <!-- Clear Button -->
      <button
        v-if="preview"
        type="button"
        class="absolute right-2 top-2 rounded-full bg-background/80 p-1 text-muted-foreground backdrop-blur-sm hover:text-foreground"
        @click.prevent="clearImage"
      >
        <IconX class="h-4 w-4" />
      </button>
    </div>

    <!-- Upload Button -->
    <Button
      type="button"
      variant="outline"
      class="mt-2 w-full"
      @click="handleClick"
    >
      <IconUpload class="mr-2 h-4 w-4" />
      {{ modelValue || existing ? 'Change Image' : 'Upload Image' }}
    </Button>
  </div>
</template>
