<script setup lang="ts">
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from "vue";
import { currentEditing } from "../events/eventBus";

const props = withDefaults(defineProps<{
  label?: string,
  street?: string,
  city?: string,
  state?: string,
  country?: string,
  type?: string
}>(), {
  street: '',
  city: '',
  state: '',
  country: '',
  type: 'work'
})

const customModel = defineModel()

const input = ref<HTMLElement | null>(null);

const isEditing = ref(false);

async function edit() {
  // Emit an event to close other inputs
  currentEditing.value = customModel;

  isEditing.value = true;

  await nextTick();

  input.value ? input.value.select() : '';
}

function handleClickOutside(event: MouseEvent) {

  if (input.value && !input.value.contains(event.target as Node)) {

    isEditing.value = false;

    if (currentEditing.value === customModel) {

      currentEditing.value = null;

    }

  }

}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

watch(currentEditing, (newVal) => {
  if (newVal !== customModel) {
    isEditing.value = false;
  }
});
</script>

<template>

  <h3 class="mb-2 mt-6 text-lg font-medium" v-if="label">{{ label }}</h3>

  <div class="grid grid-cols-2 gap-6">
    <label class="block font-sans text-gray-700">
      Street
      <input
        class="border mt-1 border-gray-300 shadow-sm block w-full rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed"
        type="text"
        @input="$emit('update:street', $event.target.value)"
        :value="street"
        placeholder="Street"/>
    </label>

    <label
      class="block font-sans text-gray-700">
      City
      <input
        class="border mt-1 border-gray-300 shadow-sm block w-full rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed"
        type="text"
        @input="$emit('update:city', $event.target.value)"
        :value="city"
        placeholder="Enter city name"/>
    </label>

    <label
      class="block font-sans text-gray-700">
      State
      <input
        class="border mt-1 border-gray-300 shadow-sm block w-full rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed"
        type="text"
        @input="$emit('update:state', $event.target.value)"
        :value="state"
        placeholder="Enter state name"/>
    </label>

    <label
      class="block font-sans text-gray-700">
      Country
      <input
        class="border mt-1 border-gray-300 shadow-sm block w-full rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed"
        type="text"
        @input="$emit('update:country', $event.target.value)"
        :value="country"
        placeholder="Enter country name"/>
    </label>
  </div>
</template>
