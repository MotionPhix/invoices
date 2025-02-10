<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'

const form = useForm({
  subject: '',
  message: '',
  priority: 'low'
})

const handleSubmit = () => {
  form.post(route('client-portal.support.store'))
}
</script>

<template>
  <div class="container mx-auto py-8">
    <Card class="mx-auto max-w-2xl">
      <CardHeader>
        <CardTitle>New Support Request</CardTitle>
        <CardDescription>
          Submit a new support request
        </CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div class="space-y-2">
            <label for="subject">Subject</label>
            <Input
              id="subject"
              v-model="form.subject"
              type="text"
              required
              :error="form.errors.subject"
            />
          </div>

          <div class="space-y-2">
            <label for="priority">Priority</label>
            <Select v-model="form.priority">
              <SelectTrigger>
                <SelectValue placeholder="Select priority" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="low">Low</SelectItem>
                <SelectItem value="medium">Medium</SelectItem>
                <SelectItem value="high">High</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div class="space-y-2">
            <label for="message">Message</label>
            <Textarea
              id="message"
              v-model="form.message"
              required
              rows="6"
              :error="form.errors.message"
            />
          </div>

          <Button
            type="submit"
            class="w-full"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Submitting...' : 'Submit Request' }}
          </Button>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
