<script setup lang="ts">
import {computed, ref} from 'vue'
import { router } from '@inertiajs/vue3'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
  DropdownMenuSub,
  DropdownMenuSubContent,
  DropdownMenuSubTrigger,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import {
  IconUpload,
  IconDownload,
  IconFile,
  IconX,
  IconFileText,
  IconFileSpreadsheet,
  IconFileTypePdf,
  IconChecks,
  IconFileImport,
  IconFileDownload,
  IconChevronDown
} from '@tabler/icons-vue'
import { useToast } from '@/components/ui/toast/use-toast'

interface Props {
  filters: Record<string, any>
  selected: number[] // Array of selected client IDs
}

const props = withDefaults(defineProps<Props>(), {
  filters: () => ({}),
  selected: () => []
})

const { toast } = useToast()
const importDialog = ref(false)
const uploading = ref(false)
const selectedFile = ref<File | null>(null)
const errors = ref([])

const handleFileSelect = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (input.files?.length) {
    const file = input.files[0]
    // Check file type
    if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
      toast({
        title: 'Invalid file type',
        description: 'Please upload a CSV file',
        variant: 'destructive',
      })
      return
    }
    // Check file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
      toast({
        title: 'File too large',
        description: 'File size should not exceed 2MB',
        variant: 'destructive',
      })
      return
    }
    selectedFile.value = file
  }
}

const handleImport = async () => {
  if (!selectedFile.value) return

  uploading.value = true
  const formData = new FormData()
  formData.append('file', selectedFile.value)

  try {
    await router.post(route('clients.import'), formData, {
      onSuccess: () => {
        importDialog.value = false
        selectedFile.value = null
        toast({
          title: 'Success',
          description: 'Clients imported successfully',
        })
      },
      onError: (errors) => {
        toast({
          title: 'Error',
          description: 'There was an error importing the clients',
          variant: 'destructive',
        })
      },
    })
  } finally {
    uploading.value = false
  }
}

type ExportType = 'all' | 'filtered' | 'selected'
type ExportFormat = 'csv' | 'excel' | 'pdf'

const handleExport = (type: ExportType, format: ExportFormat) => {
  const url = new URL(route('clients.export'))
  url.searchParams.append('format', format)
  url.searchParams.append('type', type)

  switch (type) {
    case 'filtered':
      if (props.filters) {
        Object.entries(props.filters).forEach(([key, value]) => {
          if (value) url.searchParams.append(key, value as string)
        })
      }
      break
    case 'selected':
      if (props.selected.length > 0) {
        url.searchParams.append('selected', props.selected.join(','))
      }
      break
  }

  window.location.href = url.toString()
}

const downloadSample = () => {
  window.location.href = route('clients.sample')
}

const hasSelection = computed(() => props.selected.length > 0)
const hasFilters = computed(() => Object.values(props.filters).some(value => value))

const exportFormats = [
  { label: 'CSV', value: 'csv', icon: IconFileText },
  { label: 'Excel', value: 'excel', icon: IconFileSpreadsheet },
  { label: 'PDF', value: 'pdf', icon: IconFileTypePdf }
] as const
</script>

<template>
  <div class="flex items-center gap-2">
    <Button
      variant="outline"
      class="gap-2"
      @click="downloadSample">
      <IconFileImport class="h-4 w-4" />
      Sample File
    </Button>

    <!-- Export Dropdown -->
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" class="gap-2">
          <IconDownload class="h-4 w-4" />
          Export
          <IconChevronDown class="h-4 w-4" />
        </Button>
      </DropdownMenuTrigger>

      <DropdownMenuContent align="end" class="w-56">
        <DropdownMenuLabel>Export Options</DropdownMenuLabel>
        <DropdownMenuSeparator />

        <DropdownMenuGroup>
          <!-- All Clients -->
          <DropdownMenuSub>
            <DropdownMenuSubTrigger>
              <IconFileDownload class="mr-2 h-4 w-4" />
              <span>Export All Clients</span>
            </DropdownMenuSubTrigger>
            <DropdownMenuSubContent>
              <DropdownMenuItem
                v-for="format in exportFormats"
                :key="format.value"
                @click="handleExport('all', format.value)">
                <component :is="format.icon" class="mr-2 h-4 w-4" />
                <span>{{ format.label }}</span>
              </DropdownMenuItem>
            </DropdownMenuSubContent>
          </DropdownMenuSub>

          <!-- Filtered Clients -->
          <DropdownMenuSub>
            <DropdownMenuSubTrigger :disabled="!hasFilters">
              <IconFileDownload class="mr-2 h-4 w-4" />
              <span>Export Filtered Clients</span>
            </DropdownMenuSubTrigger>
            <DropdownMenuSubContent>
              <DropdownMenuItem
                v-for="format in exportFormats"
                :key="format.value"
                @click="handleExport('filtered', format.value)">
                <component :is="format.icon" class="mr-2 h-4 w-4" />
                <span>{{ format.label }}</span>
              </DropdownMenuItem>
            </DropdownMenuSubContent>
          </DropdownMenuSub>

          <!-- Selected Clients -->
          <DropdownMenuSub>
            <DropdownMenuSubTrigger :disabled="!hasSelection">
              <IconChecks class="mr-2 h-4 w-4" />
              <span>Export Selected ({{ selected.length }})</span>
            </DropdownMenuSubTrigger>
            <DropdownMenuSubContent>
              <DropdownMenuItem
                v-for="format in exportFormats"
                :key="format.value"
                @click="handleExport('selected', format.value)">
                <component :is="format.icon" class="mr-2 h-4 w-4" />
                <span>{{ format.label }}</span>
              </DropdownMenuItem>
            </DropdownMenuSubContent>
          </DropdownMenuSub>
        </DropdownMenuGroup>
      </DropdownMenuContent>
    </DropdownMenu>

    <!-- Import Dialog -->
    <Dialog v-model:open="importDialog">
      <DialogTrigger asChild>
        <Button variant="outline" class="gap-2">
          <IconUpload class="h-4 w-4" />
          Import
        </Button>
      </DialogTrigger>

      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Import Clients</DialogTitle>
          <DialogDescription>
            Upload a CSV file to import clients. Make sure your file follows the required format.
          </DialogDescription>
        </DialogHeader>

        <div class="grid gap-4 py-4">
          <Button
            variant="outline"
            class="w-full gap-2"
            @click="downloadSample">
            <IconFileDownload class="h-4 w-4" />
            Download Sample File
          </Button>

          <div class="grid w-full items-center gap-1.5">
            <label
              for="file"
              class="relative cursor-pointer rounded-lg border-2 border-dashed border-muted-foreground/25 p-8 text-center hover:border-muted-foreground/50"
            >
              <input
                id="file"
                type="file"
                accept=".csv"
                class="hidden"
                @change="handleFileSelect"
              />

              <div v-if="!selectedFile" class="space-y-2">
                <IconUpload class="mx-auto h-8 w-8 text-muted-foreground" />
                <div class="text-sm font-medium">
                  Drop your CSV file here or click to browse
                </div>
                <div class="text-xs text-muted-foreground">
                  CSV file up to 2MB
                </div>
              </div>

              <div v-else class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <IconFile class="h-8 w-8 text-muted-foreground" />
                  <div class="space-y-1">
                    <p class="text-sm font-medium">
                      {{ selectedFile.name }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                      {{ Math.round(selectedFile.size / 1024) }}kb
                    </p>
                  </div>
                </div>
                <Button
                  variant="ghost"
                  size="icon"
                  @click.prevent="selectedFile = null">
                  <IconX class="h-4 w-4" />
                </Button>
              </div>
            </label>
          </div>
        </div>

        <DialogFooter>
          <Button
            variant="outline"
            @click="importDialog = false" >
            Cancel
          </Button>
          <Button
            :disabled="!selectedFile || uploading"
            @click="handleImport" >
            {{ uploading ? 'Importing...' : 'Import' }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>
