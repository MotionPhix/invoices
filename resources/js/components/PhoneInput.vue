<script setup lang="ts">
import MazPhoneNumberInput from 'maz-ui/components/MazPhoneNumberInput'
import InputError from "@/components/InputError.vue";
import { onMounted } from 'vue'

const props = defineProps<{
  error: string | object
}>()

const phone = defineModel({ default: () => {} })

onMounted(() => {
  if (! phone.value.number) {
    phone.value = {
      number: '',
      type: 'mobile',
      country_code: 'MW',
      is_primary_phone: true,
    }
  }
})
</script>

<template>
  <div
    class="relative mb-4 space-y-2 group first-letter:uppercase">
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
      {{ phone.type }} phone
    </label>

    <!-- :only-countries="['MW', 'ZA', 'ZM', 'ZW']" -->
    <MazPhoneNumberInput
      v-model:country-code="phone.country_code"
      v-model="phone.number"
      country-selector-display-name
      show-code-on-list
      class="w-full"
      :class="{ 'border-r-4 rounded-xl border-r-indigo-600 dark:border-r-yellow-500': phone.is_primary_phone }"
      color="success"
      no-flags />

    <InputError :message="props.error[`phone.${idx}.type`]" />
    <InputError :message="props.error[`phone.${idx}.number`]" />
    <InputError :message="props.error[`phone.${idx}.country_code`]" />

  </div>
</template>
