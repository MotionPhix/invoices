<script setup>
import { ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Button } from '@/Components/ui/button'
import {
  IconUser,
  IconSettings,
  IconLogout,
  IconBuilding,
  IconChevronDown
} from '@tabler/icons-vue'

const page = usePage()
const user = page.props.auth.user

const logout = () => {
  router.post(route('logout'))
}

const switchToTeam = (team) => {
  router.put(route('current-team.update'), {
    team_id: team.id,
  }, {
    preserveState: false,
  })
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger asChild>
      <Button
        variant="ghost"
        class="relative h-8 w-8 rounded-full">
        <img
          v-if="page.props.jetstream.managesProfilePhotos"
          :src="user.profile_photo_url"
          :alt="user.name"
          class="h-8 w-8 rounded-full object-cover"
        />
        <span v-else class="flex items-center gap-2 text-sm font-medium">
          {{ user.name }}
          <IconChevronDown class="h-4 w-4" />
        </span>
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
        <DropdownMenuItem :href="route('profile.show')">
          <IconUser class="mr-2 h-4 w-4" />
          Profile
        </DropdownMenuItem>
        <DropdownMenuItem v-if="page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
          <IconSettings class="mr-2 h-4 w-4" />
          API Tokens
        </DropdownMenuItem>
      </DropdownMenuGroup>

      <!-- Teams -->
      <template v-if="page.props.jetstream.hasTeamFeatures">
        <DropdownMenuSeparator />
        <DropdownMenuGroup>
          <DropdownMenuLabel>Teams</DropdownMenuLabel>
          <DropdownMenuItem :href="route('teams.show', user.current_team)">
            <IconBuilding class="mr-2 h-4 w-4" />
            Team Settings
          </DropdownMenuItem>
          <DropdownMenuItem
            v-if="page.props.jetstream.canCreateTeams"
            :href="route('teams.create')"
          >
            Create New Team
          </DropdownMenuItem>
        </DropdownMenuGroup>

        <!-- Team Switcher -->
        <template v-if="user.all_teams.length > 1">
          <DropdownMenuSeparator />
          <DropdownMenuGroup>
            <DropdownMenuLabel>Switch Teams</DropdownMenuLabel>
            <DropdownMenuItem
              v-for="team in user.all_teams"
              :key="team.id"
              @click="switchToTeam(team)"
            >
              <span
                class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center"
                :class="{ 'text-primary': team.id === user.current_team_id }"
              >
                <svg
                  v-if="team.id === user.current_team_id"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-4 w-4"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </span>
              <span class="ml-6">{{ team.name }}</span>
            </DropdownMenuItem>
          </DropdownMenuGroup>
        </template>
      </template>

      <DropdownMenuSeparator />
      <DropdownMenuItem @click="logout">
        <IconLogout class="mr-2 h-4 w-4" />
        Log out
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
