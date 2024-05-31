<script setup lang="ts">
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from "vue";
import { currentEditing } from "../events/eventBus";

const props = withDefaults(defineProps<{
  shouldBeShort?: boolean,
  placeholder: string,
  step?: number,
  type?: string
}>(), {
  shouldBeShort: false,
  step: 0.01,
  type: 'text'
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
  <div class="relative flex flex-col items-start max-w-full" @click.stop>
    <div
      class="hover:bg-white/20 w-full overflow-hidden border border-transparent rounded-md cursor-pointer px-3 py-1.5"
      :class="{ 'text-ellipsis': props.shouldBeShort, 'invisible': isEditing }"
      @click="edit">
      {{ customModel ?? 'This here' }}
    </div>

    <textarea
      ref="input"
      @mouseleave="isEditing == false"
      v-model="customModel"
      v-if="props.type === 'text' && isEditing"
      class="absolute inset-0 max-w-full placeholder-gray-400 px-3 py-1.5 rounded-md focus:ring-2 focus:ring-blue-900"
      :placeholder="props.placeholder">
    </textarea>

    <input
      ref="input"
      @mouseleave="isEditing == false"
      v-if="props.type !== 'text' && isEditing"
      v-model="customModel"
      :step="props.type === 'number' ? props.step : ''"
      class="absolute inset-0 max-w-full placeholder-gray-400 px-3 py-1.5 rounded-md focus:ring-2 focus:ring-blue-900"
      :placeholder="props.placeholder"
      :type="props.type" />

  </div>
</template>
