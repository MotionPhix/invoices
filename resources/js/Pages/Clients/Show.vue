<script setup lang="ts">
import { ref } from 'vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import {
  Tabs,
  TabsContent,
  TabsList,
  TabsTrigger,
} from '@/Components/ui/tabs'
import {
  IconBuilding,
  IconMail,
  IconPhone,
  IconMapPin,
  IconTruck,
  IconReceipt,
  IconNotes,
  IconCreditCard,
  IconActivity,
  IconEdit,
  IconTrash,
  IconFileText,
  IconTags,
  IconFolder,
} from '@tabler/icons-vue'
import { useToast } from '@/components/ui/toast/use-toast'

const props = defineProps({
  client: {
    type: Object,
    required: true,
  },
  activities: {
    type: Array,
    default: () => [],
  },
  invoices: {
    type: Array,
    default: () => [],
  },
  documents: {
    type: Array,
    default: () => [],
  },
})

const { toast } = useToast()
const currentTab = ref('overview')

const deleteClient = () => {
  if (confirm('Are you sure you want to delete this client?')) {
    router.delete(route('clients.destroy', props.client.id), {
      onSuccess: () => {
        toast({
          title: 'Success',
          description: 'Client deleted successfully',
        })
      },
    })
  }
}
</script>

<template>
  <MainLayout>
    <Head :title="client.name" />

    <div class="space-y-6">
      <!-- Header Section -->
      <div class="flex items-start justify-between">
        <div>
          <h2 class="text-2xl font-bold tracking-tight">{{ client.name }}</h2>
          <p class="text-muted-foreground">
            {{ client.company_name }}
          </p>
        </div>

        <div class="flex items-center gap-2">
          <Button
            variant="outline"
            :href="route('clients.edit', client.id)"
          >
            <IconEdit class="mr-2 h-4 w-4" />
            Edit
          </Button>

          <Button
            variant="destructive"
            @click="deleteClient"
          >
            <IconTrash class="mr-2 h-4 w-4" />
            Delete
          </Button>
        </div>
      </div>

      <!-- Main Content -->
      <Tabs v-model="currentTab" class="space-y-4">
        <TabsList>
          <TabsTrigger value="overview">Overview</TabsTrigger>
          <TabsTrigger value="invoices">Invoices</TabsTrigger>
          <TabsTrigger value="documents">Documents</TabsTrigger>
          <TabsTrigger value="activity">Activity</TabsTrigger>
        </TabsList>

        <!-- Overview Tab -->
        <TabsContent value="overview" class="space-y-4">
          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Contact Information -->
            <Card>
              <CardHeader>
                <CardTitle>Contact Information</CardTitle>
              </CardHeader>
              <CardContent>
                <div class="space-y-4">
                  <div class="flex items-center gap-2">
                    <IconMail class="h-4 w-4 text-muted-foreground" />
                    <a :href="`mailto:${client.email}`" class="hover:underline">
                      {{ client.email }}
                    </a>
                  </div>
                  <div class="flex items-center gap-2">
                    <IconPhone class="h-4 w-4 text-muted-foreground" />
                    <a :href="`tel:${client.phone}`" class="hover:underline">
                      {{ client.phone }}
                    </a>
                  </div>
                  <div class="flex items-center gap-2">
                    <IconBuilding class="h-4 w-4 text-muted-foreground" />
                    <span>{{ client.company_name }}</span>
                  </div>
                  <div v-if="client.vat_number" class="flex items-center gap-2">
                    <IconReceipt class="h-4 w-4 text-muted-foreground" />
                    <span>VAT: {{ client.vat_number }}</span>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Billing Address -->
            <Card>
              <CardHeader>
                <CardTitle>Billing Address</CardTitle>
              </CardHeader>

              <CardContent>
                <div class="flex items-start gap-2">
                  <IconMapPin class="h-4 w-4 mt-1 text-muted-foreground" />

                  <div class="space-y-1">
                    <p>{{ client.billing_address }}</p>
                    <p>{{ client.billing_city }}, {{ client.billing_state }}</p>
                    <p>{{ client.billing_postal_code }}</p>
                    <p>{{ client.billing_country }}</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Shipping Address -->
            <Card>
              <CardHeader>
                <CardTitle>Shipping Address</CardTitle>
                <CardDescription v-if="client.use_billing_for_shipping">
                  Same as billing address
                </CardDescription>
              </CardHeader>

              <CardContent>
                <div class="flex items-start gap-2">
                  <IconTruck class="h-4 w-4 mt-1 text-muted-foreground" />

                  <div class="space-y-1">
                    <template v-if="client.use_billing_for_shipping">
                      <p>{{ client.billing_address }}</p>
                      <p>{{ client.billing_city }}, {{ client.billing_state }}</p>
                      <p>{{ client.billing_postal_code }}</p>
                      <p>{{ client.billing_country }}</p>
                    </template>

                    <template v-else>
                      <p>{{ client.shipping_address }}</p>
                      <p>{{ client.shipping_city }}, {{ client.shipping_state }}</p>
                      <p>{{ client.shipping_postal_code }}</p>
                      <p>{{ client.shipping_country }}</p>
                    </template>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Additional Information -->
            <Card>
              <CardHeader>
                <CardTitle>Additional Information</CardTitle>
              </CardHeader>

              <CardContent>
                <div class="space-y-4">
                  <div class="flex items-center gap-2">
                    <IconCreditCard class="h-4 w-4 text-muted-foreground" />
                    <span>Currency: {{ client.currency }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <IconTags class="h-4 w-4 text-muted-foreground" />
                    <span>Status: {{ client.status }}</span>
                  </div>
                  <div v-if="client.notes" class="flex items-start gap-2">
                    <IconNotes class="h-4 w-4 mt-1 text-muted-foreground" />
                    <p class="text-sm">{{ client.notes }}</p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>

        <!-- Invoices Tab -->
        <TabsContent value="invoices">
          <Card>
            <CardHeader>
              <CardTitle>Invoices</CardTitle>
              <CardDescription>
                View and manage all invoices for this client
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div v-if="invoices.length" class="space-y-4">
                <!-- Invoice list implementation here -->
                <div v-for="invoice in invoices" :key="invoice.id">
                  <!-- Invoice item component here -->
                </div>
              </div>
              <div v-else class="text-center py-6 text-muted-foreground">
                No invoices found for this client
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- Documents Tab -->
        <TabsContent value="documents">
          <Card>
            <CardHeader>
              <CardTitle>Documents</CardTitle>
              <CardDescription>
                Manage documents and attachments
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div v-if="documents.length" class="space-y-4">
                <!-- Documents list implementation here -->
                <div v-for="document in documents" :key="document.id">
                  <!-- Document item component here -->
                </div>
              </div>
              <div v-else class="text-center py-6 text-muted-foreground">
                No documents found for this client
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- Activity Tab -->
        <TabsContent value="activity">
          <Card>
            <CardHeader>
              <CardTitle>Activity Log</CardTitle>
              <CardDescription>
                Recent activities and changes
              </CardDescription>
            </CardHeader>

            <CardContent>
              <div v-if="activities.length" class="space-y-4">
                <div
                  v-for="activity in activities"
                  :key="activity.id"
                  class="flex items-start gap-4 py-4 border-b last:border-0">
                  <div class="flex-shrink-0">
                    <IconActivity class="h-5 w-5 text-muted-foreground" />
                  </div>

                  <div class="flex-1">
                    <p class="text-sm">
                      {{ activity.description }}
                    </p>

                    <p class="text-xs text-muted-foreground">
                      {{ activity.created_at }}
                    </p>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-6 text-muted-foreground">
                No activities found for this client
              </div>
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>
    </div>
  </MainLayout>
</template>
