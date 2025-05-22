<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div class="bg-white text-black w-full max-w-md rounded-2xl shadow-xl p-6 relative">
      <button
        @click="$emit('close')"
        class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl"
      >
        &times;
      </button>
      <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
      <slot />
    </div>
  </div>
</template>

<script setup lang="js">
defineProps({
  title: {
    type: String,
    required: true,
  },
});
</script>
