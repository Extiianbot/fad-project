<template>
  <div
    v-if="show"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
  >
    <div
      class="bg-white rounded-xl shadow-lg max-w-md w-full p-6 relative"
      @click.stop
    >
      <!-- Close Button -->
      <button
        @click="close"
        class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-xl"
      >
        &times;
      </button>

      <!-- Modal Header -->
      <div v-if="$slots.header" class="mb-4">
        <slot name="header" />
      </div>

      <!-- Modal Body -->
      <div v-if="$slots.body" class="mb-4">
        <slot name="body" />
      </div>

      <!-- Modal Footer -->
      <div v-if="$slots.footer">
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<script setup lang="js">
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  show: Boolean,
})
const emit = defineEmits(['close'])

function close() {
  emit('close')
}
</script>