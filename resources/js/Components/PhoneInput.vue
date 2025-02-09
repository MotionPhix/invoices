<script setup lang="ts">
import { ref, watch } from 'vue'
import { Input } from '@/Components/ui/input'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import {
  parsePhoneNumberWithError,
  getCountryCallingCode,
} from 'libphonenumber-js'

const props = defineProps({
  modelValue: {
    type: String,
    required: true
  },
  error: {
    type: String,
    default: ''
  },
  defaultCountry: {
    type: String,
    default: 'MW'
  }
})

const emit = defineEmits(['update:modelValue'])

const countries = ref([
  { code: 'MW', name: 'Malawi' },
  { code: 'ZA', name: 'South Africa' },
  { code: 'ZM', name: 'Zambia' },
  { code: 'ZW', name: 'Zimbabwe' }
])

const selectedCountry = ref(props.defaultCountry)
const nationalNumber = ref('')

// Initialize the national number from the provided modelValue
watch(() => props.modelValue, (newValue) => {
  if (newValue) {
    try {
      const phoneNumber = parsePhoneNumberWithError(newValue)
      if (phoneNumber) {
        selectedCountry.value = phoneNumber.country
        nationalNumber.value = phoneNumber.nationalNumber
      }
    } catch (error) {
      nationalNumber.value = newValue
    }
  } else {
    nationalNumber.value = ''
  }
}, { immediate: true })

// Update the full phone number when either country or national number changes
watch([selectedCountry, nationalNumber], ([country, number]) => {
  try {
    if (number) {
      const phoneNumber = parsePhoneNumberWithError(number, country)
      if (phoneNumber) {
        emit('update:modelValue', phoneNumber.format('E.164'))
        return
      }
    }
    emit('update:modelValue', number)
  } catch (error) {
    emit('update:modelValue', number)
  }
})

const getCountryFlag = (countryCode) => {
  const codePoints = countryCode
    .toUpperCase()
    .split('')
    .map(char => 127397 + char.charCodeAt())
  return String.fromCodePoint(...codePoints)
}

const formatPlaceholder = (countryCode) => {
  try {
    return `+${getCountryCallingCode(countryCode)} xxx xxx xxx`
  } catch {
    return 'Enter phone number'
  }
}
</script>

<template>
  <div class="grid gap-2">
    <div class="flex gap-2">
      <Select
        v-model="selectedCountry">
        <SelectTrigger
          class="w-[110px]">
          <SelectValue>
            <span class="flex items-center gap-2">
              <span>{{ getCountryFlag(selectedCountry) }}</span>
              <span>{{ selectedCountry }}</span>
            </span>
          </SelectValue>
        </SelectTrigger>

        <SelectContent>
          <SelectItem
            v-for="country in countries"
            :key="country.code"
            :value="country.code">
            <span class="flex items-center gap-2">
              <span>{{ getCountryFlag(country.code) }}</span>
              <span>{{ country.name }}</span>
            </span>
          </SelectItem>
        </SelectContent>
      </Select>

      <Input
        v-model="nationalNumber"
        type="tel"
        :placeholder="formatPlaceholder(selectedCountry)"
        class="flex-1"
        :class="{ 'border-destructive': error }"
      />
    </div>

    <span
      v-if="error"
      class="text-sm text-destructive">
      {{ error }}
    </span>
  </div>
</template>
