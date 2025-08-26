<script setup lang="ts">
import {Button} from '@/components/ui/button'
import {IconPlus} from '@tabler/icons-vue'
import {type Component} from "vue";
import {router} from "@inertiajs/vue3"

const props = withDefaults(
  defineProps<{
    title: string
    description: string
    icon: Component
    createRoute?: string
    createLabel?: string
  }>(), {
    createLabel: 'Create New'
  }
)
</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-[400px] p-8 text-center">
    <div
      v-if="icon"
      class="flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-primary/10 dark:bg-primary/20">
      <component
        :is="icon"
        class="w-10 h-10 text-primary"
      />
    </div>

    <h3 class="text-xl font-semibold text-foreground mb-2">
      {{ title }}
    </h3>

    <p class="text-muted-foreground max-w-sm mb-6">
      {{ description }}
    </p>

    <Button
      v-if="createRoute"
      @click="router.get(createRoute, {}, { replace: true })"
      class="inline-flex items-center">
      <IconPlus class="w-4 h-4"/>
      {{ createLabel }}
    </Button>
  </div>
</template>
