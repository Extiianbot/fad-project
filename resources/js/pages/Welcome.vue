<script setup>
import { defineProps, ref } from 'vue';
import axios from 'axios'; // Import Axios
import NavBar from '../components/NavBar.vue';
import Modal from '../components/RegisterModal.vue';

const props = defineProps({
  users: {
    type: Array,
    default: () => [],
  },
  venues: {
    type: Array,
    default: () => [],
  },
});

const showModal = ref(false);

const form = ref({
  username: '',
  email: '',
  position: '',
  division: '',
  password: '',
  confirmPassword: '',
});

async function submitRegistration() {
  if (form.value.password !== form.value.confirmPassword) {
    alert("Passwords do not match!");
    return;
  }

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/register', {
      username: form.value.username,
      email: form.value.email,
      password: form.value.password,
      position: form.value.position,
      division: form.value.division,
    });

    console.log('Registration successful:', response.data);
    alert('User registered successfully!');

    // Reset form and close modal
    form.value = {
      username: '',
      email: '',
      position: '',
      division: '',
      password: '',
      confirmPassword: '',
    };
    showModal.value = false;
  } catch (error) {
    console.error('Registration failed:', error.response?.data || error.message);
    alert('Registration failed. Please try again.');
  }
}
</script>


<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- NavBar with register button that triggers the modal -->
    <NavBar @register="showModal = true" />

    <div class="max-w-7xl mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6 text-white">Staff and Venue Management</h1>

      <!-- Modal for user registration -->
  <Modal v-if="showModal" title="Register New Staff" @close="showModal = false">
    <form @submit.prevent="submitRegistration">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Username</label>
        <input v-model="form.username" type="text" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Email</label>
        <input v-model="form.email" type="email" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Password</label>
        <input v-model="form.password" type="password" class="w-full p-2 border rounded" required />
      </div>

    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Confirm Password</label>
        <input v-model="form.confirmPassword" type="password" class="w-full p-2 border rounded" required />
    </div>

      <div class="mb-4 flex gap-4">
        <div class="w-1/2">
          <label class="block text-sm font-medium mb-1">Position</label>
          <input v-model="form.position" type="text" class="w-full p-2 border rounded" required />
        </div>

        <div class="w-1/2">
          <label class="block text-sm font-medium mb-1">Division</label>
          <select v-model="form.division" class="w-full p-2 border rounded" required>
            <option disabled value="">Select Division</option>
            <option value="HR">PMED</option>
            <option value="IT">ICT</option>
            <option value="Marketing">FAD</option>
        </select>
        </div>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
        >
          Register
        </button>
      </div>
    </form>
  </Modal>

      <!-- Tables: Users and Venues -->
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Table: Users -->
        <div class="w-full lg:w-1/2">
          <h2 class="text-xl font-semibold mb-4 text-gray-200">Staff Users</h2>
          <div v-if="props.users && props.users.length > 0" class="overflow-x-auto bg-gray-800 rounded-xl shadow-md">
            <table class="min-w-full divide-y divide-gray-700">
              <thead class="bg-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">ID</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Username</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Position</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Division</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
                <tr v-for="user in props.users" :key="user.id" class="hover:bg-gray-700">
                  <td class="px-6 py-4">{{ user.id }}</td>
                  <td class="px-6 py-4">{{ user.username }}</td>
                  <td class="px-6 py-4">{{ user.email }}</td>
                  <td class="px-6 py-4">{{ user.position }}</td>
                  <td class="px-6 py-4">{{ user.division }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-gray-300 p-4 bg-gray-800 rounded-xl">
            No users found.
          </div>
        </div>

        <!-- Right Table: Venues -->
        <div class="w-full lg:w-1/2">
          <h2 class="text-xl font-semibold mb-4 text-gray-200">Venues</h2>
          <div v-if="props.venues && props.venues.length > 0" class="overflow-x-auto bg-gray-800 rounded-xl shadow-md">
            <table class="min-w-full divide-y divide-gray-700">
              <thead class="bg-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">ID</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Event Title</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Participants</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300">Conference Type</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
                <tr v-for="venue in props.venues" :key="venue.id" class="hover:bg-gray-700">
                  <td class="px-6 py-4">{{ venue.id }}</td>
                  <td class="px-6 py-4">{{ venue.title_of_event }}</td>
                  <td class="px-6 py-4">{{ venue.date_of_event }}</td>
                  <td class="px-6 py-4">{{ venue.number_of_participants }}</td>
                  <td class="px-6 py-4">{{ venue.conference_type }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-gray-300 p-4 bg-gray-800 rounded-xl">
            No venues found.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
