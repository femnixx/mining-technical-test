<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    vehicles: Array,
    approvers: Array,
});

const form = useForm({
    vehicle_id: '',
    driver_name: '',
    approver_1_id: '',
    approver_2_id: '',
    start_date: '',
    end_date: '',
});

const submit = () => {
    form.post(route('bookings.store'), {
        onSuccess: () => console.log('Bookings saved'),
        onError: (errors) => console.error('Validation failed: ', errors)
    });
};
</script>

<template>
    <Head title="Create Booking" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900">Create New Booking</h2>
        </template>

        <div class="rounded-md border border-gray-200 bg-white p-4 sm:p-6">
            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <InputLabel for="vehicle" value="Select Vehicle" />
                    <select v-model="form.vehicle_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                        <option value="" disabled>Choose a vehicle</option>
                        <option v-for="v in vehicles" :key="v.id" :value="v.id">
                            {{ v.model_name }} ({{ v.plate_number }}) - {{ v.ownership }}
                        </option>
                    </select>
                    <InputError :message="form.errors.vehicle_id" />
                </div>

                <div>
                    <InputLabel for="driver_name" value="Driver Name" />
                    <TextInput v-model="form.driver_name" type="text" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.driver_name" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel value="Approver 1 (Level 1)" />
                        <select v-model="form.approver_1_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            <option v-for="a in approvers" :key="a.id" :value="a.id">{{ a.name }}</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel value="Approver 2 (Level 2)" />
                        <select v-model="form.approver_2_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            <option v-for="a in approvers" :key="a.id" :value="a.id">{{ a.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel value="Start Date" />
                        <TextInput v-model="form.start_date" type="date" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel value="End Date" />
                        <TextInput v-model="form.end_date" type="date" class="mt-1 block w-full" />
                    </div>
                </div>

                <PrimaryButton :disabled="form.processing">Submit Booking</PrimaryButton>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
