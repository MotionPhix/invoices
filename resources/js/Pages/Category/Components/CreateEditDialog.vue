<script setup>
import { useForm } from '@inertiajs/vue3'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/Components/ui/dialog'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Textarea } from '@/Components/ui/textarea'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  category: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'updated'])

const form = useForm({
  name: props.category?.name ?? '',
  description: props.category?.description ?? '',
  color: props.category?.color ?? '#4f46e5',
  _method: props.category ? 'PUT' : 'POST'
})

const handleSubmit = () => {
  const url = props.category
    ? route('categories.update', props.category.id)
    : route('categories.store')

  form.post(url, {
    preserveScroll: true,
    onSuccess: () => {
      emit('updated')
      form.reset()
    }
  })
}

// Watch for changes in the category prop to update form values
watch(() => props.category, (newCategory) => {
  if (newCategory) {
    form.name = newCategory.name
    form.description = newCategory.description ?? ''
    form.color = newCategory.color
    form._method = 'PUT'
  } else {
    form.reset()
    form._method = 'POST'
  }
}, { immediate: true })
</script>

<template>
  <Dialog :open="show" @update:open="$emit('close')">
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>
          {{ category ? 'Edit Category' : 'Create Category' }}
        </DialogTitle>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <Label for="name" required>Name</Label>
          <Input
            id="name"
            v-model="form.name"
            :error="form.errors.name"
            autofocus
          />
          <span v-if="form.errors.name" class="text-sm text-destructive">
            {{ form.errors.name }}
          </span>
        </div>

        <div>
          <Label for="description">Description</Label>
          <Textarea
            id="description"
            v-model="form.description"
            :error="form.errors.description"
            rows="3"
            placeholder="Optional category description"
          />
          <span v-if="form.errors.description" class="text-sm text-destructive">
            {{ form.errors.description }}
          </span>
        </div>

        <div>
          <Label for="color" required>Color</Label>
          <div class="flex items-center gap-2">
            <Input
              id="color"
              v-model="form.color"
              :error="form.errors.color"
            />
            <input
              type="color"
              v-model="form.color"
              class="h-10 w-10 rounded-md border cursor-pointer"
            />
          </div>
          <span v-if="form.errors.color" class="text-sm text-destructive">
            {{ form.errors.color }}
          </span>
        </div>

        <div class="flex justify-end gap-4 pt-4">
          <Button
            type="button"
            variant="outline"
            @click="$emit('close')"
            :disabled="form.processing"
          >
            Cancel
          </Button>
          <Button
            type="submit"
            :disabled="form.processing"
          >
            {{ category ? 'Update Category' : 'Create Category' }}
          </Button>
        </div>
      </form>
    </DialogContent>
  </Dialog>
</template>
