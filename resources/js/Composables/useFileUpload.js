import { ref, watch } from 'vue'

export function useFileUpload(form, fieldName) {
  const preview = ref(null)

  // Watch for changes in the form field and clear preview if field is cleared
  watch(() => form[fieldName], (newValue) => {
    if (!newValue) {
      preview.value = null
    }
  })

  const handleFileChange = (event) => {
    const file = event.target.files?.[0] || event

    if (file) {
      // Update the form field with the file
      form[fieldName] = file

      // Create a preview URL for images
      if (file instanceof File && file.type.startsWith('image/')) {
        preview.value = URL.createObjectURL(file)
      }
    }
  }

  const clearFile = () => {
    // Clear the form field
    form[fieldName] = null

    // Clear the preview if it exists
    if (preview.value) {
      URL.revokeObjectURL(preview.value)
      preview.value = null
    }
  }

  // Cleanup function to revoke object URLs when component is unmounted
  const cleanup = () => {
    if (preview.value) {
      URL.revokeObjectURL(preview.value)
    }
  }

  return {
    preview,
    handleFileChange,
    clearFile,
    cleanup
  }
}
