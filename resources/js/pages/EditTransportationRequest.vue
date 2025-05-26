<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import HomeNavBar from '../components/HomeNavBar.vue';

const props = defineProps({
    transportation: {
        type: Object,
        required: true
    }
});

const form = useForm({
    destination: props.transportation.destination,
    number_of_passengers: props.transportation.number_of_passengers,
    purpose: props.transportation.purpose,
    date_time_needed: props.transportation.date_time_needed,
    date_time_returned: props.transportation.date_time_returned,
    attachment: null,
});

const previewUrl = ref(null);
const fileError = ref('');
const allowedFileTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'image/jpeg',
    'image/png'
];

const handleSubmit = () => {
    form.put(route('transportations.update', props.transportation.id), {
        onSuccess: () => {
            form.reset();
            previewUrl.value = null;
            fileError.value = '';
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
    });
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    fileError.value = '';

    if (!file) {
        form.attachment = null;
        previewUrl.value = null;
        return;
    }

    if (!allowedFileTypes.includes(file.type)) {
        fileError.value = 'Please upload a valid file type (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG)';
        form.attachment = null;
        previewUrl.value = null;
        return;
    }

    if (file.size > 5 * 1024 * 1024) { // 5MB limit
        fileError.value = 'File size should not exceed 5MB';
        form.attachment = null;
        previewUrl.value = null;
        return;
    }

    form.attachment = file;
    
    // Create preview URL for images
    if (file.type.startsWith('image/')) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
    }
};

// Clean up preview URL when component is unmounted
onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
        <HomeNavBar />
        <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow dark:bg-gray-900 dark:text-gray-100">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Edit Transportation Request</h2>
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Destination -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Destination
                    </label>
                    <input
                        v-model="form.destination"
                        type="text"
                        class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
                        required
                        placeholder="Destination"
                    />
                </div>

                <!-- Number of Passengers -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Number of Passengers
                    </label>
                    <input
                        v-model="form.number_of_passengers"
                        type="number"
                        min="1"
                        class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
                        required
                    />
                </div>

                <!-- Purpose -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Purpose
                    </label>
                    <textarea
                        v-model="form.purpose"
                        rows="3"
                        class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
                        required
                        placeholder="Purpose"
                    ></textarea>
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Date and Time Needed
                        </label>
                        <input
                            v-model="form.date_time_needed"
                            type="datetime-local"
                            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400"
                            required
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Date and Time Return
                        </label>
                        <input
                            v-model="form.date_time_returned"
                            type="datetime-local"
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
                    <div class="space-y-2">
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (MAX. 5MB)
                                    </p>
                                </div>
                                <input
                                    type="file"
                                    @change="handleFileUpload"
                                    class="hidden"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                />
                            </label>
                        </div>
                        
                        <!-- Current Attachment -->
                        <div v-if="transportation.attachment" class="mt-2 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Current attachment</span>
                                </div>
                                <a :href="'/storage/' + transportation.attachment"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    View
                                </a>
                            </div>
                        </div>
                        
                        <!-- File Preview -->
                        <div v-if="previewUrl" class="mt-4">
                            <div class="relative w-full h-48 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
                                <img :src="previewUrl" alt="Preview" class="w-full h-full object-contain" />
                            </div>
                        </div>
                        
                        <!-- Selected File Info -->
                        <div v-if="form.attachment" class="mt-2 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ form.attachment.name }}</span>
                            </div>
                        </div>
                        
                        <!-- Error Message -->
                        <p v-if="fileError" class="mt-2 text-sm text-red-600 dark:text-red-400">
                            {{ fileError }}
                        </p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a :href="route('transportations.pending')"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700"
                        :disabled="form.processing"
                    >
                        Update Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</template> 