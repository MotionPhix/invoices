<script setup lang="ts">
import { useDark, useToggle } from '@vueuse/core'
import { Button } from '@/components/ui/button'
import { IconSun, IconMoon } from '@tabler/icons-vue'

const isDark = useDark({
  selector: 'html',
  attribute: 'class',
  valueDark: 'dark',
  valueLight: 'light',
})

const toggleDark = useToggle(isDark)
</script>

<template>
  <Button
    variant="ghost"
    size="icon"
    @click="toggleDark()"
    class="rounded-full"
  >
    <IconSun
      v-if="isDark"
    />
    <IconMoon
      v-else
    />
    <span class="sr-only">Toggle theme</span>
  </Button>
</template>
