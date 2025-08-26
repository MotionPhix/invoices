<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
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
  IconChevronDown,
  IconTrash,
  IconCheck,
  IconX,
  IconUsers
} from '@tabler/icons-vue'
import { useToast } from '@/components/ui/toast/use-toast'

interface Props {
  selected: number[]
}

const props = withDefaults(defineProps<Props>(), {
  selected: () => []
})

const { toast } = useToast()

const hasSelection = computed(() => props.selected.length > 0)

const handleBulkAction = async (action: string, value?: string) => {
  try {
    await router.post(route('clients.bulk-action'), {
      action,
      value,
      selected: props.selected
    }, {
      onSuccess: () => {
        toast({
          title: 'Success',
          description: `Successfully ${action}d ${props.selected.length} client(s)`,
        })
      },
      onError: (errors) => {
        toast({
          title: 'Error',
          description: 'There was an error processing the bulk action',
          variant: 'destructive',
        })
      },
    })
  } catch (error) {
    console.error('Bulk action error:', error)
  }
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger asChild>
      <Button
        variant="outline"
        class="gap-2"
        :disabled="!hasSelection">
        <IconUsers class="h-4 w-4" />
        Bulk Actions ({{ selected.length }})
        <IconChevronDown class="h-4 w-4" />
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent align="end" class="w-56">
      <DropdownMenuLabel>Bulk Actions</DropdownMenuLabel>

      <DropdownMenuSeparator />

      <DropdownMenuGroup>
        <DropdownMenuItem @click="handleBulkAction('delete')">
          <IconTrash class="mr-2 h-4 w-4" />
          <span>Delete Selected</span>
        </DropdownMenuItem>

        <DropdownMenuSeparator />

        <DropdownMenuItem @click="handleBulkAction('status', 'active')">
          <IconCheck class="mr-2 h-4 w-4" />
          <span>Mark as Active</span>
        </DropdownMenuItem>

        <DropdownMenuItem @click="handleBulkAction('status', 'inactive')">
          <IconX class="mr-2 h-4 w-4" />
          <span>Mark as Inactive</span>
        </DropdownMenuItem>
      </DropdownMenuGroup>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
