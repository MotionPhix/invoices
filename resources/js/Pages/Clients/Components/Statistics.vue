<script setup lang="ts">
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'
import {
  IconUsers,
  IconUserCheck,
  IconUserX,
  IconUsersPlus,
  IconClockHour4,
  IconWorld,
} from '@tabler/icons-vue'

const props = defineProps<{
  statistics: {
    total: number
    active: number
    inactive: number
    new_this_month: number
    recent_activity: {
      total: number
      created: number
      updated: number
      deleted: number
      restored: number
    }
    top_countries: Array<{
      country: string
      count: number
    }>
  }
}>()

const cards = [
  {
    title: 'Total Clients',
    value: props.statistics.total,
    description: 'All registered clients',
    icon: IconUsers,
    color: 'text-blue-500',
  },
  {
    title: 'Active Clients',
    value: props.statistics.active,
    description: 'Currently active clients',
    icon: IconUserCheck,
    color: 'text-green-500',
  },
  {
    title: 'Inactive Clients',
    value: props.statistics.inactive,
    description: 'Currently inactive clients',
    icon: IconUserX,
    color: 'text-red-500',
  },
  {
    title: 'New This Month',
    value: props.statistics.new_this_month,
    description: 'Added this month',
    icon: IconUsersPlus,
    color: 'text-purple-500',
  },
]
</script>

<template>
  <div class="space-y-6 mb-6">
    <!-- Main Stats Grid -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
      <Card
        v-for="card in cards"
        :key="card.title"
        class="relative overflow-hidden"
      >
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
          <CardTitle class="text-sm font-medium">
            {{ card.title }}
          </CardTitle>
          <component
            :is="card.icon"
            class="h-4 w-4"
            :class="card.color"
          />
        </CardHeader>
        <CardContent>
          <div class="text-2xl font-bold">{{ card.value }}</div>
          <p class="text-xs text-muted-foreground">
            {{ card.description }}
          </p>
        </CardContent>
      </Card>
    </div>

    <!-- Additional Stats -->
    <div class="grid gap-4 md:grid-cols-2">
      <!-- Recent Activity Card -->
      <Card>
        <CardHeader>
          <CardTitle>Recent Activity</CardTitle>
          <CardDescription>
            Last 30 days activity
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-2 gap-4">
            <div
              v-for="(count, type) in statistics.recent_activity"
              :key="type"
              v-show="type !== 'total'"
              class="space-y-2"
            >
              <div class="flex items-center gap-2">
                <div
                  class="h-2 w-2 rounded-full"
                  :class="{
                    'bg-green-500': type === 'created',
                    'bg-blue-500': type === 'updated',
                    'bg-red-500': type === 'deleted',
                    'bg-yellow-500': type === 'restored'
                  }"
                />
                <span class="text-sm font-medium capitalize">{{ type }}</span>
              </div>
              <p class="text-2xl font-bold">
                {{ count }}
              </p>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Top Countries Card -->
      <Card>
        <CardHeader>
          <CardTitle>Top Countries</CardTitle>
          <CardDescription>
            Most common client locations
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div
              v-for="country in statistics.top_countries"
              :key="country.country"
              class="flex items-center"
            >
              <IconWorld class="h-4 w-4 text-muted-foreground mr-2" />
              <div class="flex-1 space-y-1">
                <p class="text-sm font-medium leading-none">
                  {{ country.country || 'Unknown' }}
                </p>
                <p class="text-sm text-muted-foreground">
                  {{ country.count }} client{{ country.count === 1 ? '' : 's' }}
                </p>
              </div>
              <div class="ml-auto font-medium">
                {{ Math.round((country.count / statistics.total) * 100) }}%
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </div>
</template>
