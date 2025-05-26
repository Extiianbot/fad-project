<script setup>
import { ref } from 'vue';
import HomeNavBar from '../components/HomeNavBar.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    venues: {
        type: Array,
        required: true
    }
});

const deleteVenue = (id) => {
    if (confirm('Are you sure you want to delete this reservation?')) {
        router.delete(route('venues.destroy', id));
    }
};

const formatDate = (date) => {
    if (!date) return '';
    const dateObj = new Date(date);
    return dateObj.toLocaleDateString();
};

const formatTime = (time) => {
    if (!time) return '';
    // Extract just the time part (HH:MM)
    const timeParts = time.split(':');
    if (timeParts.length >= 2) {
        return `${timeParts[0]}:${timeParts[1]}`;
    }
    return time;
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <HomeNavBar />
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Venue Reservations</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title of Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Conference Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Participants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time Start</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time End</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attachment</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="venue in venues" :key="venue.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ venue.title_of_event }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ venue.conference_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ venue.number_of_participants }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ formatDate(venue.date_of_event) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ formatTime(venue.time_start) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ formatTime(venue.time_end) }}
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
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
