<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { useToast } from '@/components/ui/toast/use-toast'

const { toast } = useToast()
const form = useForm({
  email: ''
})

const handleSubmit = async () => {
  form.post(route('client-portal.login.send-link'), {
    onSuccess: () => {
      toast({
        title: 'Success',
        description: 'Login link has been sent to your email.',
      })
    },
  })
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center">
    <div class="w-full max-w-md space-y-8 px-4">
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-bold tracking-tight">
          Client Portal Login
        </h2>
        <p class="mt-2 text-sm text-muted-foreground">
          Enter your email to receive a login link
        </p>
      </div>

      <form @submit.prevent="handleSubmit" class="mt-8 space-y-6">
        <div class="space-y-2">
          <Input
            id="email"
            v-model="form.email"
            type="email"
            required
            placeholder="Enter your email"
            :error="form.errors.email"
          />
          <p v-if="form.errors.email" class="text-sm text-destructive">
            {{ form.errors.email }}
          </p>
        </div>

        <Button
          type="submit"
          class="w-full"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Sending...' : 'Send Login Link' }}
        </Button>
      </form>
    </div>
  </div>
</template>
