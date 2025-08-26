<script setup>
import { router } from '@inertiajs/vue3'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  category: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close'])

const handleDelete = () => {
  router.delete(route('categories.destroy', props.category.id), {
    preserveScroll: true,
    onSuccess: () => emit('close')
  })
}
</script>

<template>
  <Dialog :open="show" @update:open="$emit('close')">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Delete Category</DialogTitle>
        <DialogDescription>
          Are you sure you want to delete "{{ category.name }}"?
          This action cannot be undone.
          <template v-if="category.products_count > 0">
            <br><br>
            <strong class="text-destructive">Warning:</strong>
            This category has {{ category.products_count }}
            {{ category.products_count === 1 ? 'product' : 'products' }}
            associated with it. These items will become uncategorized.
          </template>
        </DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <Button
          type="button"
          variant="outline"
          @click="$emit('close')"
        >
          Cancel
        </Button>
        <Button
          type="button"
          variant="destructive"
          @click="handleDelete"
        >
          Delete Category
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
