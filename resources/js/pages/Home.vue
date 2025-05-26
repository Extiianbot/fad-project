<script setup lang="js">
import HomeNavBar from '../components/HomeNavBar.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <HomeNavBar />
    <div class="flex flex-col items-center justify-center min-h-[80vh]">
      <h1 class="text-2xl font-bold mb-8">Welcome to the Reservation System</h1>
      <div class="flex gap-4">
        <Link
          :href="route('venue.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
        >
          Venue Reservation Form
        </Link>
        <Link
          :href="route('transportation.create')"
          class="border border-blue-600 text-blue-600 font-semibold py-3 px-6 rounded-lg transition-colors hover:bg-blue-50"
        >
          Transportation Request Form
        </Link>
      </div>
    </div>
  </div>
</template>