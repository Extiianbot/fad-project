<script setup>
import { defineProps } from 'vue';
import HomeNavBar from '../components/HomeNavBar.vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    requests: {
        type: Array,
        required: true
    },
    auth: Object
});

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString();
};

const viewRequest = (id, type) => {
    router.get(`/requests/${id}/${type}/view`);
};

const editRequest = (id, type) => {
    router.get(`/requests/${id}/${type}/edit`);
};

const deleteRequest = (id, type) => {
    if (confirm('Are you sure you want to delete this request?')) {
        router.delete(`/requests/${id}/${type}`)
            .then(() => {
                // Refresh the page after successful deletion
                router.visit('/requests');
            })
            .catch(error => {
                console.error('Error deleting request:', error);
                alert('Failed to delete request. Please try again.');
            });
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <HomeNavBar />
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">My Requests</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="request in requests" :key="request.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ request.id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ request.type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ formatDateTime(request.created_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span :class="{
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': !request.is_approved_by && !request.status,
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': request.is_approved_by && request.status,
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': !request.is_approved_by && request.status
                                }">
                                    {{ !request.is_approved_by && !request.status ? 'Pending' : 
                                       request.is_approved_by && request.status ? 'Approved' : 'Rejected' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button @click="viewRequest(request.id)"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                        View
                                    </button>
                                    <button @click="editRequest(request.id)"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                        Edit
                                    </button>
                                    <button @click="deleteRequest(request.id)"
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