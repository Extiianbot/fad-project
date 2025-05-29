<script setup lang="js">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import HomeNavBar from '../components/HomeNavBar.vue';

const form = useForm({
    destination: '',
    number_of_passengers: '',
    purpose: '',
    date_time_needed: '',
    date_time_returned: '',
    attachment: null,
});

const handleSubmit = () => {
    form.post(route('transportations.store'), {
        onSuccess: () => {
            form.reset();
            alert('Transportation request submitted successfully!');
        },
        onError: (errors) => {
            console.error('Submission failed:', errors);
            alert('Failed to submit request. Please check your inputs.');
        },
    });
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  form.attachment = file;
  const originalFileName = file.name;
  console.log(originalFileName); // e.g. "YzWLKVFFLePRmZ1gLJZtL6qaXiN90RB9wrEjzMV9.docx"
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
        <HomeNavBar />
        <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow dark:bg-gray-900 dark:text-gray-100">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Transportation Request Form</h2>
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
                    <input
                        type="file"
                        @change="handleFileUpload"
                        class="w-full p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 file:dark:text-gray-100 file:dark:bg-gray-700 file:dark:border-gray-600"
                    />
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md"
                        :disabled="form.processing"
                    >
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</template> 