<script setup>
import { IconMailCheck, IconMailExclamation } from '@tabler/icons-vue'

defineProps({
  verified: {
    type: Boolean,
    required: true
  }
})
</script>

<template>
  <div class="inline-flex items-center">
    <template v-if="verified">
      <IconMailCheck class="h-4 w-4 text-green-500" />
      <span class="ml-1 text-sm text-green-500">Verified</span>
    </template>
    <template v-else>
      <IconMailExclamation class="h-4 w-4 text-amber-500" />
      <span class="ml-1 text-sm text-amber-500">Unverified</span>
    </template>
  </div>
</template>
