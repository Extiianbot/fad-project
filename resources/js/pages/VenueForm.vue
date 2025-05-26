<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <HomeNavBar />
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
      <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Conference Room Reservation Form</h2>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Title of Event -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Title of Event
          </label>
          <input
            v-model="form.title_of_event"
            type="text"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
            required
            placeholder="Enter event title"
          />
        </div>

        <!-- Conference Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Conference Type
          </label>
          <select
            v-model="form.conference_type"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
            required
          >
            <option value="">Select Conference Type</option>
            <option value="seminar">Mini Conference</option>
            <option value="workshop">Conference Hall</option>
          </select>
        </div>

        <!-- Number of Participants -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Number of Participants
          </label>
          <input
            v-model="form.number_of_participants"
            type="number"
            min="1"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
            required
          />
        </div>

        <!-- Date of Event -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Date of Event
          </label>
          <input
            v-model="form.date_of_event"
            type="date"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
            required
          />
        </div>

        <!-- Time Start -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Time Start
            </label>
            <input
              v-model="form.time_start"
              type="time"
              class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Time End
            </label>
            <input
              v-model="form.time_end"
              type="time"
              class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
              required
            />
          </div>
        </div>

        <!-- Attachment -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Attachment (Optional)
          </label>
          <input
            type="file"
            @change="handleFileUpload"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 file:dark:text-gray-100 file:dark:bg-gray-700 file:dark:border-gray-600"
          />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md"
            :disabled="form.processing"
          >
            Submit Reservation
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import HomeNavBar from '../components/HomeNavBar.vue';

const form = useForm({
  conference_type: '',
  number_of_participants: '',
  date_of_event: '',
  time_start: '',
  time_end: '',
  attachment: null,
  title_of_event: ''
});

const handleSubmit = () => {
  form.post(route('venues.store'), {
    onSuccess: () => {
      form.reset();
      alert('Conference room reservation submitted successfully!');
    },
    onError: (errors) => {
      console.error('Submission failed:', errors);
      alert('Failed to submit request. Please check your inputs.');
    },
  });
};

const handleFileUpload = (event) => {
  form.attachment = event.target.files[0];
};
</script>