<template>
  <nav class="bg-white dark:bg-gray-900 w-full shadow-sm rounded-t-xl">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center gap-2">
        <img src="/logo.png" alt="NEDA Logo" class="h-8 w-8" />
        <span class="font-semibold text-lg text-gray-800 dark:text-white">NEDA</span>
      </div>
      <!-- Navigation Links -->
      <div class="flex-1 flex justify-center">
        <ul class="flex gap-8 text-sm font-medium text-gray-700 dark:text-gray-200">
          <li><a href="/dashboard" class="hover:text-blue-700 dark:hover:text-blue-400 transition">Request Form</a></li>
          <li><a href="#" class="hover:text-blue-700 dark:hover:text-blue-400 transition">Transportation Request</a></li>
          <li><a href="#" class="hover:text-blue-700 dark:hover:text-blue-400 transition">Venue Reservation</a></li>
          <li><a href="#" class="hover:text-blue-700 dark:hover:text-blue-400 transition">Pending Requests</a></li>
        </ul>
      </div>
      <!-- Right Side: Theme Toggle + Sign Out -->
      <div class="flex items-center gap-2">
        <button
          @click="toggleDark"
          class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
          :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
        >
          <span v-if="isDark">🌙</span>
          <span v-else>☀️</span>
        </button>
        <button
          @click="logout"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition"
        >
          Sign Out
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup lang="js">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const isDark = ref(false)

function toggleDark() {
  isDark.value = !isDark.value
  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}

onMounted(() => {
  const theme = localStorage.getItem('theme')
  if (theme === 'dark') {
    isDark.value = true
    document.documentElement.classList.add('dark')
  }
})

function logout() {
  router.post(route('logout'))
}
</script> 