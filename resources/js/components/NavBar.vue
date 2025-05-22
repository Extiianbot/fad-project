<script setup lang="js">
import { ref, onMounted } from 'vue'

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
</script>

<template>
  <nav class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo / Site Title -->
        <div class="text-2xl font-semibold tracking-wide text-gray-900 dark:text-white pl-2">
          MySite
        </div>

        <!-- Right: Auth Buttons + Theme Toggle -->
        <div class="flex space-x-4 items-center">
          <!-- Light/Dark Toggle Button -->
          <button
            @click="toggleDark"
            class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
            :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
          >
            <span v-if="isDark">🌙</span>
            <span v-else>☀️</span>
          </button>
          <!-- Emit login event -->
          <a href="#"
             @click.prevent="$emit('login')"
             class="px-4 py-2 rounded-md text-sm font-medium border border-blue-600 text-blue-600 bg-transparent hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400 transition duration-200">
            Login
          </a>

          <!-- Emit register event -->
          <a href="#"
             @click.prevent="$emit('register')"
             class="px-4 py-2 rounded-md text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white transition duration-200">
            Register
          </a>
        </div>
      </div>
    </div>
  </nav>
</template>
