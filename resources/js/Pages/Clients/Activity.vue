<script setup>
import { Head } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'
import { IconArrowLeft, IconHistory } from '@tabler/icons-vue'
import TimelineActivity from './Partials/TimelineActivity.vue'
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
  client: Object,
  activities: Object,
})

const getEventColor = (event) => {
  switch (event) {
    case 'created':
      return 'bg-green-500'
    case 'updated':
      return 'bg-blue-500'
    case 'deleted':
      return 'bg-red-500'
    case 'restored':
      return 'bg-yellow-500'
    default:
      return 'bg-gray-500'
  }
}
</script>

<template>
  <MainLayout>
    <Head :title="`${client.name} - Activity Log`" />

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="space-y-6">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
          <div>
            <div class="flex items-center gap-4">
              <Button
                variant="ghost"
                size="icon"
                :href="route('clients.show', client.id)"
              >
                <IconArrowLeft class="h-4 w-4" />
              </Button>
              <h2 class="text-2xl font-bold text-gray-900">Activity Log</h2>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">
              View all activity for {{ client.name }}
            </p>
          </div>
        </div>

        <!-- Activity Timeline -->
        <Card>
          <CardHeader>
            <CardTitle>Recent Activity</CardTitle>
            <CardDescription>
              Track all changes made to this client's information
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-8">
              <div
                v-for="(activity, index) in activities.data"
                :key="activity.id"
                class="relative"
              >
                <!-- Timeline line -->
                <div
                  v-if="index < activities.data.length - 1"
                  class="absolute left-5 top-8 h-full w-0.5 bg-muted"
                  aria-hidden="true"
                />

                <!-- Activity item -->
                <div class="relative flex items-start space-x-4">
                  <!-- Activity dot -->
                  <div
                    :class="[
                      'relative h-10 w-10 flex items-center justify-center rounded-full',
                      getEventColor(activity.event)
                    ]"
                  >
                    <IconHistory class="h-5 w-5 text-white" />
                  </div>

                  <!-- Activity content -->
                  <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                      <h3 class="font-medium text-gray-900">
                        {{ activity.description }}
                      </h3>
                      <time
                        :datetime="activity.created_at"
                        class="text-sm text-muted-foreground"
                      >
                        {{ new Date(activity.created_at).toLocaleString() }}
                      </time>
                    </div>

                    <div
                      v-if="activity.changes.attributes"
                      class="mt-2 text-sm"
                    >
                      <div
                        v-for="(newValue, field) in activity.changes.attributes"
                        :key="field"
                        class="grid grid-cols-3 gap-4 py-2"
                      >
                        <span class="font-medium">{{ field }}</span>
                        <span class="text-muted-foreground">
                          {{ activity.changes.old[field] || 'Not set' }}
                        </span>
                        <span>{{ newValue }}</span>
                      </div>
                    </div>

                    <p
                      v-if="activity.causer"
                      class="text-sm text-muted-foreground"
                    >
                      by {{ activity.causer.name }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div
              v-if="activities.data.length === 0"
              class="text-center py-12"
            >
              <IconHistory
                class="mx-auto h-12 w-12 text-muted-foreground"
                aria-hidden="true"
              />
              <h3 class="mt-2 text-sm font-semibold text-gray-900">
                No activity
              </h3>
              <p class="mt-1 text-sm text-muted-foreground">
                No changes have been made to this client yet
              </p>
            </div>
          </CardContent>
        </Card>

        <!-- Pagination -->
        <div
          v-if="activities.data.length > 0"
          class="mt-6"
        >
          <Pagination :links="activities.links" />
        </div>
      </div>
    </div>
  </MainLayout>
</template>
