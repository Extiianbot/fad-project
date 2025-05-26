<script setup>
import { ref } from 'vue';
import HomeNavBar from '../components/HomeNavBar.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    pendingVenues: {
        type: Array,
        required: true
    },
    auth: Object
});

const deleteVenue = (id) => {
    if (confirm('Are you sure you want to delete this reservation?')) {
        router.delete(route('venues.destroy', id));
    }
};

const editVenue = (id) => {
    router.get(route('venues.edit', id));
};

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString();
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <HomeNavBar />
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">My Venue Reservations</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Event Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Venue Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attendees</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Purpose</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Needed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Return</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attachment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="venue in pendingVenues" :key="venue.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ venue.event_title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ venue.venue_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ venue.number_of_attendees }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ venue.purpose }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ formatDateTime(venue.date_time_needed) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ formatDateTime(venue.date_time_returned) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span :class="{
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': !venue.is_approved_by && !venue.status,
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': venue.is_approved_by && venue.status,
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': !venue.is_approved_by && venue.status
                                }">
                                    {{ !venue.is_approved_by && !venue.status ? 'Pending' : 
                                       venue.is_approved_by && venue.status ? 'Approved' : 'Rejected' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <a v-if="venue.attachment" 
                                   :href="'/storage/' + venue.attachment" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    View Attachment
                                </a>
                                <span v-else class="text-gray-500 dark:text-gray-400">No attachment</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button @click="editVenue(venue.id)"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                        Edit
                                    </button>
                                    <button @click="deleteVenue(venue.id)"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
