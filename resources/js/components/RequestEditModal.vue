<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';
import Modal from '../components/Modal.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    request: {
        type: Object,
        required: true,
        default: () => ({}) // Add default empty object
    }
});

const emit = defineEmits(['close']);
const loading = ref(false);

// Initialize form with empty values
const form = useForm({
    id: null,
    type: '',
    venue: {
        conference_type: '',
        title_of_event: '',
        number_of_participants: null,
        date_of_event: '',
        time_start: '',
        time_end: '',
        attachment: null
    },
    transportation: {
        destination: '',
        number_of_passengers: null,
        purpose: '',
        date_time_needed: '',
        date_time_returned: '',
        attachment: null
    }
});

// Watch for changes in props.request
watch(() => props.request, (newRequest) => {
    if (newRequest) {
        form.reset();
        form.id = newRequest.id;
        form.type = newRequest.type;
        
        if (newRequest.type === 'transportation') {
            Object.assign(form.transportation, newRequest.transportation);
        } else if (newRequest.type === 'venue') {
            Object.assign(form.venue, newRequest.venue);
        }
    }
}, { immediate: true });


const updateRequest = async () => {
    try {
        loading.value = true;
        console.log('Sending data:', form);
        
        // Get the data directly from the nested object
        const data = form.type === 'transportation' 
            ? form.transportation 
            : form.venue;
        
        // Add the type and id to the data
        const formData = {
            ...data,
            id: form.id,
            type: form.type
        };
        
        await form.put(route('requests.update', { id: form.id, type: form.type }), {
            data: formData,
            onSuccess: () => {
                emit('close');
                form.reset();
            },
            onError: (errors) => {
                console.error('Update failed:', errors);
            }
        });
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
};

// Watch for changes in props.request
/*
watch(() => props.request, (newRequest) => {
    if (newRequest) {
        form.reset();
        form.id = newRequest.id;
        form.type = newRequest.type;
        
        if (newRequest.type === 'transportation') {
            form.destination = newRequest.transportation?.destination || '';
            form.number_of_passengers = newRequest.transportation?.number_of_passengers || null;
            form.purpose = newRequest.transportation?.purpose || '';
            form.date_time_needed = newRequest.transportation?.date_time_needed || '';
            form.date_time_returned = newRequest.transportation?.date_time_returned || '';
            form.attachment = newRequest.transportation?.attachment || null;
        } else if (newRequest.type === 'venue') {
            form.conference_type = newRequest.venue?.conference_type || '';
            form.title_of_event = newRequest.venue?.title_of_event || '';
            form.number_of_participants = newRequest.venue?.number_of_participants || null;
            form.date_of_event = newRequest.venue?.date_of_event || '';
            form.time_start = newRequest.venue?.time_start || '';
            form.time_end = newRequest.venue?.time_end || '';
            form.attachment = newRequest.venue?.attachment || null;
        }
    }
}, { immediate: true });
*/



const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (form.type === 'transportation') {
            form.transportation.attachment = file;
        } else {
            form.venue.attachment = file;
        }
    }
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white text-black rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Edit Request</h2>
                <button @click="emit('close')" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            <form @submit.prevent="updateRequest" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select v-model="form.type" class="w-full p-2 border rounded" required>
                        <option value="venue">Venue</option>
                        <option value="transportation">Transportation</option>
                    </select>
                    <div v-if="form.errors.type" class="text-red-500 text-sm mt-1">{{ form.errors.type }}</div>
                </div>

                <!-- Transportation Fields -->
                <div v-if="form.type === 'transportation'" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Destination</label>
                            <input v-model="form.destination" type="text" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.destination }" />
                            <div v-if="form.errors.destination" class="text-red-500 text-sm mt-1">{{ form.errors.destination }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Number of Passengers</label>
                            <input v-model="form.number_of_passengers" type="number" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   min="1"
                                   :class="{ 'border-red-500': form.errors.number_of_passengers }" />
                            <div v-if="form.errors.number_of_passengers" class="text-red-500 text-sm mt-1">{{ form.errors.number_of_passengers }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Purpose</label>
                            <input v-model="form.purpose" type="text" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.purpose }" />
                            <div v-if="form.errors.purpose" class="text-red-500 text-sm mt-1">{{ form.errors.purpose }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Date Time Needed</label>
                            <input v-model="form.date_time_needed" type="datetime-local" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.date_time_needed }" />
                            <div v-if="form.errors.date_time_needed" class="text-red-500 text-sm mt-1">{{ form.errors.date_time_needed }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Date Time Returned</label>
                            <input v-model="form.date_time_returned" type="datetime-local" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.date_time_returned }" />
                            <div v-if="form.errors.date_time_returned" class="text-red-500 text-sm mt-1">{{ form.errors.date_time_returned }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Attachment</label>
                            <input type="file" @change="handleFileUpload" 
                                   class="w-full p-2 border rounded" 
                                   :class="{ 'border-red-500': form.errors.attachment }" />
                            <div v-if="form.errors.attachment" class="text-red-500 text-sm mt-1">{{ form.errors.attachment }}</div>
                        </div>
                    </div>
                </div>

                <!-- Venue Fields -->
                <div v-if="form.type === 'venue'" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Conference Type</label>
                            <select v-model="form.conference_type" 
                                    class="w-full p-2 border rounded" 
                                    required 
                                    :class="{ 'border-red-500': form.errors.conference_type }">
                                <option value="">Select Conference Type</option>
                                <option value="Mini Conference">Mini Conference</option>
                                <option value="Conference Hall">Conference Hall</option>
                            </select>
                            <div v-if="form.errors.conference_type" class="text-red-500 text-sm mt-1">{{ form.errors.conference_type }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Title of Event</label>
                            <input v-model="form.title_of_event" type="text" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.title_of_event }" />
                            <div v-if="form.errors.title_of_event" class="text-red-500 text-sm mt-1">{{ form.errors.title_of_event }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Number of Participants</label>
                            <input v-model.number="form.number_of_participants" type="number" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   min="1"
                                   :class="{ 'border-red-500': form.errors.number_of_participants }" />
                            <div v-if="form.errors.number_of_participants" class="text-red-500 text-sm mt-1">{{ form.errors.number_of_participants }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Date of Event</label>
                            <input v-model="form.date_of_event" type="date" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.date_of_event }" />
                            <div v-if="form.errors.date_of_event" class="text-red-500 text-sm mt-1">{{ form.errors.date_of_event }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Time Start</label>
                            <input v-model="form.time_start" type="time" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.time_start }" />
                            <div v-if="form.errors.time_start" class="text-red-500 text-sm mt-1">{{ form.errors.time_start }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Time End</label>
                            <input v-model="form.time_end" type="time" 
                                   class="w-full p-2 border rounded" 
                                   required 
                                   :class="{ 'border-red-500': form.errors.time_end }" />
                            <div v-if="form.errors.time_end" class="text-red-500 text-sm mt-1">{{ form.errors.time_end }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Attachment</label>
                            <input type="file" @change="handleFileUpload" 
                                   class="w-full p-2 border rounded" 
                                   :class="{ 'border-red-500': form.errors.attachment }" />
                            <div v-if="form.errors.attachment" class="text-red-500 text-sm mt-1">{{ form.errors.attachment }}</div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="emit('close')" 
                            class="text-gray-500 hover:text-gray-700">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded" 
                            :disabled="loading">
                        <span v-if="loading">Saving...</span>
                        <span v-else>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>