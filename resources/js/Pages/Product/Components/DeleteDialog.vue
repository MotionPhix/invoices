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
  show: Boolean,
  product: Object,
})

const emit = defineEmits(['close'])

const handleDelete = () => {
  router.delete(route('products.destroy', props.product.id), {
    onSuccess: () => emit('close'),
  })
}
</script>

<template>
  <Dialog :open="show" @update:open="$emit('close')">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Delete Product</DialogTitle>
        <DialogDescription>
          Are you sure you want to delete "{{ product.name }}"? This action cannot be undone.
        </DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <Button
          variant="outline"
          @click="$emit('close')"
        >
          Cancel
        </Button>
        <Button
          variant="destructive"
          @click="handleDelete"
        >
          Delete
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
