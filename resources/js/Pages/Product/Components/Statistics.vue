<script setup>
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import {
  IconPackage,
  IconTools,
  IconAlertTriangle,
  IconCategory,
} from '@tabler/icons-vue'

defineProps({
  statistics: Object,
})
</script>

<template>
  <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
    <Card>
      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle class="text-sm font-medium">
          Total Products
        </CardTitle>
        <IconPackage class="h-4 w-4 text-muted-foreground" />
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">
          {{ statistics.products }}
        </div>
        <p class="text-xs text-muted-foreground">
          Physical products in catalog
        </p>
      </CardContent>
    </Card>

    <Card>
      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle class="text-sm font-medium">
          Total Services
        </CardTitle>
        <IconTools class="h-4 w-4 text-muted-foreground" />
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">
          {{ statistics.services }}
        </div>
        <p class="text-xs text-muted-foreground">
          Available services
        </p>
      </CardContent>
    </Card>

    <Card>
      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle class="text-sm font-medium">
          Low Stock Items
        </CardTitle>
        <IconAlertTriangle class="h-4 w-4 text-warning" />
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">
          {{ statistics.low_stock }}
        </div>
        <p class="text-xs text-muted-foreground">
          Products below threshold
        </p>
      </CardContent>
    </Card>

    <Card>
      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle class="text-sm font-medium">
          Categories
        </CardTitle>
        <IconCategory class="h-4 w-4 text-muted-foreground" />
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">
          {{ statistics.categories.length }}
        </div>
        <p class="text-xs text-muted-foreground">
          Active product categories
        </p>
      </CardContent>
    </Card>
  </div>
</template>
