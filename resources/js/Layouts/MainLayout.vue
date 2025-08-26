<script setup lang="ts">
import {ref, computed, onMounted, onUnmounted, watch} from 'vue'
import {usePage, Head, Link} from '@inertiajs/vue3'
import {
  useBreakpoints,
  useWindowScroll,
  useWindowSize,
  usePreferredLanguages
} from '@vueuse/core'
import {IconMenu2, IconX} from '@tabler/icons-vue'
import ThemeToggle from '@/components/ThemeToggle.vue'
import {Button} from '@/components/ui/button'
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
} from '@/components/ui/navigation-menu'
import UserNav from '@/components/UserNav.vue'
import ApplicationMark from "@/components/ApplicationMark.vue";
import {Toaster} from "@/components/ui/toast";

const props = defineProps({
  title: String,
})

const page = usePage()
const showMobileMenu = ref(false)

// Breakpoints
const breakpoints = useBreakpoints({
  sm: 640,
  md: 768,
  lg: 1024,
  xl: 1280,
  '2xl': 1536,
})

const isMobile = breakpoints.smaller('md')
const isTablet = breakpoints.between('md', 'lg')
const isDesktop = breakpoints.greater('lg')

// Window scroll for header effects
const {y: scrollY} = useWindowScroll()
const isScrolled = computed(() => scrollY.value > 0)

// Window size for responsive adjustments
const {width: windowWidth} = useWindowSize()

// Auto-close mobile menu on breakpoint change
watch(() => isMobile.value, (newVal) => {
  if (!newVal) showMobileMenu.value = false
})

const navigation = [
  {
    name: 'Dashboard',
    href: route('dashboard'),
    active: route().current('dashboard'),
  },
  {
    name: 'Clients',
    href: route('clients.index'),
    active: route().current('clients.*'),
  },
  {
    name: 'Invoices',
    href: route('invoices.index'),
    active: route().current('invoices.*'),
  },
  {
    name: 'Products & Services',
    href: route('products.index'),
    active: route().current('products.*'),
  },
  /*{
    name: 'Reports',
    href: route('reports.index'),
    active: route().current('reports.*'),
  },*/
]

const currentTeam = computed(() => page.props.auth.user?.current_team)

// Handle escape key to close mobile menu
const onKeydown = (e) => {
  if (e.key === 'Escape' && showMobileMenu.value) {
    showMobileMenu.value = false
  }
}

onMounted(() => {
  document.addEventListener('keydown', onKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', onKeydown)
})
</script>

<template>
  <Toaster />

  <div class="min-h-screen bg-background">
    <Head :title="title"/>

    <!-- Top Navigation -->
    <header
      class="sticky top-0 z-40 w-full border-b transition-all duration-200"
      :class="[
        isScrolled
          ? 'bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60'
          : 'bg-background'
      ]">
      <div class="container flex h-16 items-center space-x-4 sm:justify-between sm:space-x-0">
        <!-- Logo -->
        <Link
          href="/"
          class="gap-x-2 md:gap-x-4 items-center hidden sm:flex">
          <ApplicationMark class="h-8 md:h-10" />
          <span class="hidden font-bold sm:inline-block">
            {{ page.props.app.name }}
          </span>
        </Link>

        <!-- Mobile Navigation Trigger -->
        <Button
          v-if="isMobile"
          variant="ghost"
          class="mr-2 px-0 text-base hover:bg-transparent focus:ring-0"
          @click="showMobileMenu = !showMobileMenu">
          <IconMenu2 v-if="!showMobileMenu" class="h-6 w-6"/>
          <IconX v-else class="h-6 w-6"/>
          <span class="sr-only">Toggle Menu</span>
        </Button>

        <!-- Desktop Navigation -->
        <div class="flex flex-1 items-center justify-end space-x-4">
          <nav v-if="!isMobile" class="flex items-center space-x-6">
            <NavigationMenu>
              <NavigationMenuList>
                <NavigationMenuItem
                  v-for="item in navigation"
                  :key="item.name">
                  <Link
                    as="button"
                    :href="item.href"
                    class="group inline-flex h-9 w-max items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus:outline-none disabled:pointer-events-none disabled:opacity-50"
                    :class="{ 'bg-secondary': item.active }">
                    {{ item.name }}
                  </Link>
                </NavigationMenuItem>
              </NavigationMenuList>
            </NavigationMenu>
          </nav>

          <div class="flex items-center space-x-4">
            <ThemeToggle/>
            <UserNav/>
          </div>
        </div>
      </div>
    </header>

    <!-- Mobile Navigation -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="translate-y-1 opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-1 opacity-0">
      <div
        v-if="showMobileMenu && isMobile"
        class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm"
        @click="showMobileMenu = false">
        <div
          class="fixed inset-x-4 top-8 z-50 origin-top rounded-xl bg-background p-8 ring-1 ring-ring"
          @click.stop>
          <div class="flex flex-col space-y-4">
            <Link
              v-for="item in navigation"
              :key="item.name"
              :href="item.href"
              class="text-sm font-medium transition-colors hover:text-primary"
              :class="{ 'text-primary': item.active }"
              @click="showMobileMenu = false">
              {{ item.name }}
            </Link>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Page Header -->
    <div v-if="$slots.header" class="border-b bg-background">
      <div class="container py-4">
        <slot name="header"/>
      </div>
    </div>

    <!-- Main Content -->
    <main
      class="container py-6 max-w-4xl"
      :class="{
        'px-4': isMobile,
        'px-6': isTablet,
        'px-8': isDesktop
      }">
      <slot/>
    </main>

    <!-- Footer -->
    <footer class="border-t py-6 md:py-0 max-w-4xl mx-auto">
      <div
        class="container flex flex-col items-center justify-between gap-4 md:h-16 md:flex-row"
        :class="{
          'px-4': isMobile,
          'px-6': isTablet,
          'px-8': isDesktop
        }">
        <p class="text-sm text-muted-foreground">
          Built with ❤️ by {{ page.props.app.name }}
        </p>
        <p class="text-sm text-muted-foreground">
          {{ new Date().getFullYear() }} © All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>
