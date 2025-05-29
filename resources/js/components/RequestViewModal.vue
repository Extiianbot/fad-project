<script setup>
import { defineProps, defineEmits } from 'vue';
import Modal from '../components/Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    request: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close']);

const formatDateTime = (dateTime) => {
    const date = new Date(dateTime);
    return `${date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    })} ${date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    })}`;
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white text-black rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">View Request Details</h2>
                <button @click="emit('close')" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">ID</label>
                        <p class="text-sm">{{ request.id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <p class="text-sm">{{ request.type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Created At</label>
                        <p class="text-sm">{{ formatDateTime(request.created_at) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <span :class="{
                            'px-3 py-1 text-xs font-semibold rounded-full': true,
                            'bg-yellow-100 text-yellow-800': !request.is_approved_by && !request.status,
                            'bg-green-100 text-green-800': request.is_approved_by && request.status,
                            'bg-red-100 text-red-800': !request.is_approved_by && request.status
                        }">
                            {{ !request.is_approved_by && !request.status ? 'Pending' : 
                               request.is_approved_by && request.status ? 'Approved' : 'Rejected' }}
                        </span>
                    </div>
                </div>

                <!-- Venue Details -->
                <div v-if="request.type === 'venue'" class="space-y-4">
                    <h3 class="text-sm font-medium">Venue Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Conference Type</label>
                            <p class="text-sm">{{ request.venue?.conference_type }}</p>
                            
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Number of Participants</label>
                            <p class="text-sm">{{ request.venue?.number_of_participants }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Event Title</label>
                            <p class="text-sm">{{ request.venue?.title_of_event }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Date of Event</label>
                            <p class="text-sm">{{ request.venue?.date_of_event }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Time Start</label>
                            <p class="text-sm">{{ request.venue?.time_start }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Time End</label>
                            <p class="text-sm">{{ request.venue?.time_end }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Attachment</label>
                            <p class="text-sm">{{ request.venue?.attachment }}</p>
                        </div>
                    </div>
                </div>

                <!-- Transportation Details -->
                <div v-if="request.type === 'transportation'" class="space-y-4">
                    <h3 class="text-sm font-medium">Transportation Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Destination</label>
                            <p class="text-sm">{{ request.transportation?.destination }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Number of Passengers</label>
                            <p class="text-sm">{{ request.transportation?.number_of_passengers }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Purpose</label>
                            <p class="text-sm">{{ request.transportation?.purpose }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Date Time Needed</label>
                            <p class="text-sm">{{ request.transportation?.date_time_needed }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Date Time Returned</label>
                            <p class="text-sm">{{ request.transportation?.date_time_returned }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Attachment</label>
                            <p class="text-sm">{{ request.transportation?.attachment }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>