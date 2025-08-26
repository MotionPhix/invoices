<script setup lang="ts">
import {computed} from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import {
  IconUser,
  IconSettings,
  IconLogout,
} from '@tabler/icons-vue'
import {
  Avatar,
  AvatarFallback,
  AvatarImage
} from "@/components/ui/avatar";

const page = usePage()
const user = page.props.auth.user

const logout = () => {
  router.post(route('logout'))
}

// Compute user initials from name
const userInitials = computed(() => {
  if (!user.name) return ''
  return user.name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger asChild>
      <Button variant="ghost" class="relative h-8 w-8 rounded-full">
        <Avatar>
          <AvatarImage
            v-if="page.props.jetstream.managesProfilePhotos && user.profile_photo_url"
            :src="user.profile_photo_url"
            :alt="user.name"
          />
          <AvatarFallback>{{ userInitials }}</AvatarFallback>
        </Avatar>
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent class="min-w-40" align="end">
      <DropdownMenuLabel class="font-normal">
        <div class="flex flex-col space-y-1">
          <p class="text-sm font-medium leading-none">{{ user.name }}</p>
          <p class="text-xs leading-none text-muted-foreground">
            {{ user.email }}
          </p>
        </div>
      </DropdownMenuLabel>

      <DropdownMenuSeparator />

      <DropdownMenuGroup>
        <DropdownMenuItem
          @click="router.visit(route('profile.show'), {replace: true})">
          <IconUser class="mr-2 h-4 w-4" />
          Profile
        </DropdownMenuItem>

        <DropdownMenuItem
          v-if="page.props.jetstream.hasApiFeatures"
          @click="router.visit(route('api-tokens.index'), {replace: true})">
          <IconSettings class="mr-2 h-4 w-4" />
          API Tokens
        </DropdownMenuItem>
      </DropdownMenuGroup>

      <DropdownMenuSeparator />

      <DropdownMenuItem @click="logout">
        <IconLogout class="mr-2 h-4 w-4" />
        Log out
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
