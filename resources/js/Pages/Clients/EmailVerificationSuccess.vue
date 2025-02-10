<script setup>
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Email Verified" />

  <div class="min-h-screen flex flex-col items-center justify-center">
    <div class="p-8 bg-white rounded-lg shadow-md max-w-md w-full">
      <div class="text-center">
        <h2 class="text-2xl font-bold text-green-600 mb-4">
          Email Verified Successfully
        </h2>
        <p class="text-gray-600 mb-6">
          Thank you for verifying your email address. You can now close this window.
        </p>
      </div>
    </div>
  </div>
</template>
