<template>
  <nav class="bg-gray-900 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo / Site Title -->
        <div class="text-2xl font-semibold tracking-wide text-white">
          MySite
        </div>

        <!-- Right: Auth Buttons -->
        <div class="flex space-x-4">
          <a href="/login"
             class="px-4 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 transition duration-200">
            Login
          </a>
          <!-- Register triggers modal -->
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
