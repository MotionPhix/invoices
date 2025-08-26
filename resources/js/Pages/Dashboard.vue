<script setup>
import Welcome from '@/components/Welcome.vue';
import MainLayout from "@/Layouts/MainLayout.vue";
</script>

<template>
  <MainLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
          <Welcome/>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
