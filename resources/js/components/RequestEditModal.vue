<script setup>
import { defineProps, defineEmits, ref, watch, onMounted } from 'vue';
import Modal from '../components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

onMounted(() => {
    if (!document.getElementById('toast-container')) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50';
        document.body.appendChild(container);
    }
});

const props = defineProps({
    show: Boolean,
    request: {
        type: Object,
        required: true,
        default: () => ({})
    }
});

const emit = defineEmits(['close']);
const loading = ref(false);

const form = useForm({
    id: null,
    conference_type: '',
    title_of_event: '',
    number_of_participants: null,
    date_of_event: '',
    time_start: '',
    time_end: '',
    destination: '',
    number_of_passengers: null,
    purpose: '',
    date_time_needed: '',
    date_time_returned: '',
    attachment: null
});

watch(() => props.request, (newRequest) => {
    if (newRequest) {
        form.reset();
        form.id = newRequest.id;

        form.conference_type = '';
        form.title_of_event = '';
        form.number_of_participants = null;
        form.date_of_event = '';
        form.time_start = '';
        form.time_end = '';
        form.destination = '';
        form.number_of_passengers = null;
        form.purpose = '';
        form.date_time_needed = '';
        form.date_time_returned = '';
        form.attachment = null;

        if (newRequest.type === 'transportation') {
            form.destination = newRequest.transportation?.destination || '';
            form.number_of_passengers = newRequest.transportation?.number_of_passengers || null;
            form.purpose = newRequest.transportation?.purpose || '';
            form.date_time_needed = newRequest.transportation?.date_time_needed || '';
            form.date_time_returned = newRequest.transportation?.date_time_returned || '';
            // keep the filename as reference for display, not submission
            form.attachment = null;
        } else if (newRequest.type === 'venue') {
            form.conference_type = newRequest.venue?.conference_type || '';
            form.title_of_event = newRequest.venue?.title_of_event || '';
            form.number_of_participants = newRequest.venue?.number_of_participants || null;
            form.date_of_event = newRequest.venue?.date_of_event || '';
            form.time_start = newRequest.venue?.time_start || '';
            form.time_end = newRequest.venue?.time_end || '';
            form.attachment = null;
        }
    }
}, { immediate: true });

const updateRequest = async () => {
    try {
        loading.value = true;
        const formData = new FormData();

        Object.keys(form).forEach(key => {
            if (
                key !== 'errors' &&
                key !== 'id' &&
                key !== 'attachment' &&
                form[key] !== null
            ) {
                formData.append(key, form[key]);
            }
        });

        formData.append('id', form.id);

        // ✅ Append only if it's a real File
        if (form.attachment instanceof File) {
            formData.append('attachment', form.attachment);
        }

        await form.put(route('requests.update', { id: form.id, type: props.request.type }), {
            data: formData,
            onSuccess: () => {
                toast.success('Request updated successfully!');
                form.reset();
                emit('close');
            },
            onError: (errors) => {
                console.error('Form errors:', errors);
                toast.error(`Failed to update request: ${Object.values(errors).join(' ')}`);
            }
        });
    } catch (error) {
        console.error('Error:', error);
        toast.error('An unexpected error occurred. Please try again.');
    } finally {
        loading.value = false;
    }
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.attachment = file;
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
            <div v-if="props.request.type === 'transportation'" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm">Destination</label>
                        <input v-model="form.destination" type="text" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Number of Passengers</label>
                        <input v-model="form.number_of_passengers" type="number" min="1" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Purpose</label>
                        <input v-model="form.purpose" type="text" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Date Time Needed</label>
                        <input v-model="form.date_time_needed" type="datetime-local" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Date Time Returned</label>
                        <input v-model="form.date_time_returned" type="datetime-local" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Attachment</label>
                        <input type="file" @change="handleFileUpload" accept=".pdf,.doc,.docx,.ppt,.pptx" class="w-full p-2 border rounded" />
                        <div v-if="form.attachment" class="text-sm mt-1 text-gray-600">Selected: {{ form.attachment.name }}</div>
                        <div v-else-if="props.request.transportation?.attachment" class="text-sm mt-1 text-gray-500">
                            Existing: {{ props.request.transportation.attachment }}
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="props.request.type === 'venue'" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm">Conference Type</label>
                        <select v-model="form.conference_type" required class="w-full p-2 border rounded">
                            <option value="Mini Conference">Mini Conference</option>
                            <option value="Conference Hall">Conference Hall</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm">Title of Event</label>
                        <input v-model="form.title_of_event" type="text" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Number of Participants</label>
                        <input v-model="form.number_of_participants" type="number" min="1" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Date of Event</label>
                        <input v-model="form.date_of_event" type="date" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Time Start</label>
                        <input v-model="form.time_start" type="time" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Time End</label>
                        <input v-model="form.time_end" type="time" required class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Attachment</label>
                        <input type="file" @change="handleFileUpload" accept=".pdf,.doc,.docx,.ppt,.pptx" class="w-full p-2 border rounded" />
                        <div v-if="form.attachment" class="text-sm mt-1 text-gray-600">Selected: {{ form.attachment.name }}</div>
                        <div v-else-if="props.request.venue?.attachment" class="text-sm mt-1 text-gray-500">
                            Existing: {{ props.request.venue.attachment }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" @click="emit('close')" class="text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded" :disabled="loading">
                    <span v-if="loading">Saving...</span>
                    <span v-else>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
</template>
