<script setup>
import { defineProps, ref } from 'vue';
import axios from 'axios';
import NavBar from '../components/NavBar.vue';
import LoginModal from '../components/LoginModal.vue';
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

const showRegisterModal = ref(false);
const showLoginModal = ref(false);

// Register form
const registerForm = ref({
  username: '',
  email: '',
  position: '',
  division: '',
  password: '',
});

// Login form
const loginForm = ref({
  email: '',
  password: '',
});

// Register user
async function submitRegistration() {
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/register', registerForm.value);
    console.log('Registration successful:', response.data);
    alert('User registered successfully!');
    registerForm.value = {
      username: '',
      email: '',
      position: '',
      division: '',
      password: '',
    };
    showRegisterModal.value = false;
  } catch (error) {
    console.error('Registration failed:', error.response?.data || error.message);
    alert('Registration failed. Please try again.');
  }
}

// Login user
async function submitLogin() {
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login', loginForm.value);
    console.log('Login successful:', response.data);
    alert('Login successful!');
    loginForm.value = {
      email: '',
      password: '',
    };
    showLoginModal.value = false;
    // Optional: localStorage.setItem('auth_token', response.data.token);
  } catch (error) {
    console.error('Login failed:', error.response?.data || error.message);
    alert('Login failed. Please try again.');
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- NavBar now controls modals -->
    <NavBar @login="showLoginModal = true" @register="showRegisterModal = true" />

    <div class="max-w-7xl mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6 text-white">Staff and Venue Management</h1>

      <!-- Removed extra Login button from here -->

      <!-- Login Modal -->
      <LoginModal
        v-if="showLoginModal"
        title="Login"
        :form="loginForm"
        @close="showLoginModal = false"
        @submit="submitLogin"
      />

      <!-- Register Modal -->
      <Modal
        v-if="showRegisterModal"
        title="Register New Staff"
        @close="showRegisterModal = false"
      >
        <form @submit.prevent="submitRegistration">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Username</label>
            <input v-model="registerForm.username" type="text" class="w-full p-2 border rounded" required />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input v-model="registerForm.email" type="email" class="w-full p-2 border rounded" required />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Password</label>
            <input v-model="registerForm.password" type="password" class="w-full p-2 border rounded" required />
          </div>

          <div class="mb-4 flex gap-4">
            <div class="w-1/2">
              <label class="block text-sm font-medium mb-1">Position</label>
              <input v-model="registerForm.position" type="text" class="w-full p-2 border rounded" required />
            </div>

            <div class="w-1/2">
              <label class="block text-sm font-medium mb-1">Division</label>
              <select v-model="registerForm.division" class="w-full p-2 border rounded" required>
                <option disabled value="">Select Division</option>
                <option value="PMED">PMED</option>
                <option value="ICT">ICT</option>
                <option value="FAD">FAD</option>
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

      <!-- Tables -->
      <div class="flex flex-col lg:flex-row gap-6 mt-8">
        <!-- Users Table -->
        <div class="w-full lg:w-1/2">
          <h2 class="text-xl font-semibold mb-4 text-gray-200">Staff Users</h2>
          <div v-if="props.users.length" class="overflow-x-auto bg-gray-800 rounded-xl shadow-md">
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

        <!-- Venues Table -->
        <div class="w-full lg:w-1/2">
          <h2 class="text-xl font-semibold mb-4 text-gray-200">Venues</h2>
          <div v-if="props.venues.length" class="overflow-x-auto bg-gray-800 rounded-xl shadow-md">
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
