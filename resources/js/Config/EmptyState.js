import {
  IconUsers,
  IconFileInvoice,
  IconPackage,
  IconTags,
  IconSettings,
  IconSearch,
  IconAlertTriangle,
  IconCloudOff
} from '@tabler/icons-vue'

export const emptyStates = {
  clients: {
    title: 'No clients yet',
    description: 'Get started by creating your first client. You can add their contact information, business details, and manage their invoices all in one place.',
    icon: IconUsers,
  },
  invoices: {
    title: 'No invoices yet',
    description: 'Create your first invoice by selecting a client and adding items. You can save it as a draft or send it right away.',
    icon: IconFileInvoice,
  },
  products: {
    title: 'No products yet',
    description: 'Start by adding products or services that you offer. You can include prices, descriptions, and other details.',
    icon: IconPackage,
  },
  categories: {
    title: 'No categories yet',
    description: 'Organize your products and services by creating categories. This helps keep your inventory organized and easy to manage.',
    icon: IconTags,
  },
  settings: {
    title: 'No settings configured',
    description: 'Configure your business settings including tax rates, currency preferences, and invoice templates.',
    icon: IconSettings,
  },
  search: {
    title: 'No results found',
    description: 'We couldn\'t find anything matching your search. Try adjusting your keywords or filters.',
    icon: IconSearch,
    showCreateButton: false,
  },
  error: {
    title: 'Something went wrong',
    description: 'We encountered an error while loading this content. Please try again or contact support if the problem persists.',
    icon: IconAlertTriangle,
    showCreateButton: false,
  },
  offline: {
    title: 'You\'re offline',
    description: 'Check your internet connection and try again. Your changes will be saved and synced when you\'re back online.',
    icon: IconCloudOff,
    showCreateButton: false,
  }
}
