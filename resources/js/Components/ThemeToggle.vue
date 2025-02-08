<script setup>
import { ref, onMounted } from 'vue'
import { useLocalStorage } from '@vueuse/core'
import { Button } from '@/Components/ui/button'
import { IconSun, IconMoon } from '@tabler/icons-vue'

const theme = useLocalStorage('theme', 'light')

const toggleTheme = () => {
  theme.value = theme.value === 'light' ? 'dark' : 'light'
  updateTheme()
}

const updateTheme = () => {
  if (theme.value === 'dark') {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

onMounted(() => {
  updateTheme()
})
</script>

<template>
  <Button
    variant="ghost"
    size="icon"
    @click="toggleTheme"
    class="rounded-full"
  >
    <IconSun
      v-if="theme === 'dark'"
      class="h-5 w-5 text-yellow-500"
    />
    <IconMoon
      v-else
      class="h-5 w-5 text-slate-700"
    />
    <span class="sr-only">Toggle theme</span>
  </Button>
</template>
