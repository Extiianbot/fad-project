<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
    <div class="bg-white text-black rounded-lg shadow-lg p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ title }}</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">✕</button>
      </div>

      <form @submit.prevent="$emit('submit')">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Email</label>
          <input v-model="localForm.email" type="email" class="w-full p-2 border rounded" required @input="updateForm" />
          <div v-if="localForm.errors.email" class="text-red-500 text-sm mt-1">{{ localForm.errors.email }}</div>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Password</label>
          <input v-model="localForm.password" type="password" class="w-full p-2 border rounded" required @input="updateForm" />
          <div v-if="localForm.errors.password" class="text-red-500 text-sm mt-1">{{ localForm.errors.password }}</div>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded" :disabled="form.processing">
            {{ form.processing ? 'Logging in...' : 'Login' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="js">
import { defineProps, defineEmits, ref, watch } from 'vue';

const props = defineProps(['title', 'form']);
const emit = defineEmits(['close', 'submit']);

const localForm = ref({ ...props.form });

watch(() => props.form, (newVal) => {
  localForm.value = { ...newVal };
}, { deep: true });

function updateForm() {
  emit('update:form', localForm.value);
}
</script>
