<script setup lang="js">
import { defineProps, ref, onMounted } from 'vue';
import axios from 'axios';
import NavBar from '../components/NavBar.vue';
import LoginModal from '../components/LoginModal.vue';
import Modal from '../components/RegisterModal.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
  transportations: {
    type: Array,
    default: () => [],
  },
  venues: {
    type: Array,
    default: () => [],
  },
});

// Helper functions for formatting dates and times
const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString();
};

const formatTime = (time) => {
    if (!time) return '';
    
    // Split the time string into hours and minutes
    const [hours, minutes] = time.split(':');
    
    // Convert hours to 12-hour format
    const hour12 = parseInt(hours) % 12 || 12;
    const ampm = parseInt(hours) >= 12 ? 'PM' : 'AM';
    
    return `${hour12.toString().padStart(2, '0')}:${minutes} ${ampm}`;
};

const formatDateTime = (dateTime) => {
  if (!dateTime) return '';
  return new Date(dateTime).toLocaleString();
};

// Helper function to format status
const formatStatus = (status) => {
  return status ? 'Approved' : 'Pending';
};

// Helper function to get staff user name
const getStaffUserName = (staffUser) => {
  return staffUser?.username || 'Unknown';
};

const showRegisterModal = ref(false);
const showLoginModal = ref(false);

// Light/Dark mode toggle
const isDark = ref(false);
function toggleDark() {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
}
onMounted(() => {
  const theme = localStorage.getItem('theme');
  if (theme === 'dark') {
    isDark.value = true;
    document.documentElement.classList.add('dark');
  }
});

// Register form
const registerForm = ref({
  username: '',
  email: '',
  position: '',
  division: '',
  password: '',
});

// Login form
const loginForm = useForm({
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
function submitLogin() {
  loginForm.post(route('login'), {
    onSuccess: () => {
      showLoginModal.value = false;
      window.location.href = '/dashboard';
    },
    onError: (errors) => {
      console.error('Login failed:', errors);
      if (errors.email) {
        alert(errors.email);
      } else {
        alert('Login failed. Please check your credentials.');
      }
    },
  });
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    <!-- NavBar now controls modals -->
    <NavBar @login="showLoginModal = true" @register="showRegisterModal = true" />

    <div class="max-w-7xl mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8 text-center text-gray-900 dark:text-gray-100">Transportation Request and Venue Reservation</h1>

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
      <div class="flex flex-col lg:flex-row gap-8 mt-10 justify-center">
        <!-- Transportation Table -->
        <div class="w-full lg:w-1/2 max-w-2xl mx-auto">
          <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-200">Transportation Requests</h2>
          <div v-if="props.transportations.length" class="overflow-x-auto rounded-xl shadow-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">ID</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Destination</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Passengers</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Time Needed</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Staff User</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="transportation in props.transportations" :key="transportation.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ transportation.id }}</td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ transportation.destination }}</td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ transportation.number_of_passengers }}</td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    {{ formatTime(transportation.date_time_needed) }}
                  </td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    {{ getStaffUserName(transportation.staff_user) }}
                  </td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    <span :class="{
                      'px-2 py-1 rounded-full text-xs': true,
                      'bg-green-100 text-green-800': transportation.status,
                      'bg-yellow-100 text-yellow-800': !transportation.status
                    }">
                      {{ formatStatus(transportation.status) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-gray-500 dark:text-gray-400 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mt-2">
            No transportation requests found.
          </div>
        </div>

        <!-- Venue Table -->
        <div class="w-full lg:w-1/2 max-w-2xl mx-auto">
          <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-200">Venue Reservations</h2>
          <div v-if="props.venues.length" class="overflow-x-auto rounded-xl shadow-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">ID</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Title</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Type</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Participants</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Time Start</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Time End</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Staff User</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="venue in props.venues" :key="venue.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ venue.id }}</td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ venue.title_of_event }}</td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ venue.conference_type }}</td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ venue.number_of_participants }}</td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    {{ formatDate(venue.date_of_event) }}
                  </td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    {{ formatTime(venue.time_start) }}
                  </td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    {{ formatTime(venue.time_end) }}
                  </td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    {{ getStaffUserName(venue.staff_user) }}
                  </td>
                  <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    <span :class="{
                      'px-2 py-1 rounded-full text-xs': true,
                      'bg-green-100 text-green-800': venue.status,
                      'bg-yellow-100 text-yellow-800': !venue.status
                    }">
                      {{ formatStatus(venue.status) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-gray-500 dark:text-gray-400 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mt-2">
            No venue reservations found.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
