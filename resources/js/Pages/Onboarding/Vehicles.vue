<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineOptions({ layout: AuthenticatedLayout });

const vehicleTypes = ['Haul Truck', 'Excavator', 'Dozer', 'Passenger', 'Cargo'];
const rows = ref([
    { model_name: '', plate_number: '', type: 'Cargo', fuel_capacity_l: '', location: '' },
]);

function addRow() {
    rows.value.push({ model_name: '', plate_number: '', type: 'Cargo', fuel_capacity_l: '', location: '' });
}

function removeRow(index) {
    if (rows.value.length > 1) {
        rows.value.splice(index, 1);
    }
}

const form = useForm({
    vehicles: rows.value,
});

function submit() {
    form.vehicles = rows.value;
    form.post(route('onboarding.vehicles.store'), {
        onFinish: () => {},
    });
}
</script>

<template>
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Onboarding</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Step 2 of 3 - Add your vehicles</p>
        </div>

        <form @submit.prevent="submit" class="max-w-3xl">
            <div class="space-y-4">
                <div v-for="(row, index) in rows" :key="index" class="grid grid-cols-1 gap-4 rounded border border-gray-300 bg-white p-4 dark:border-gray-600 dark:bg-gray-800 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <InputLabel :for="'model_name_' + index" value="Model Name" />
                        <TextInput
                            :id="'model_name_' + index"
                            v-model="row.model_name"
                            type="text"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors[`vehicles.${index}.model_name`]" />
                    </div>
                    <div class="sm:col-span-2">
                        <InputLabel :for="'plate_number_' + index" value="Plate Number" />
                        <TextInput
                            :id="'plate_number_' + index"
                            v-model="row.plate_number"
                            type="text"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors[`vehicles.${index}.plate_number`]" />
                    </div>
                    <div>
                        <InputLabel :for="'type_' + index" value="Type" />
                        <select
                            :id="'type_' + index"
                            v-model="row.type"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        >
                            <option v-for="t in vehicleTypes" :key="t" :value="t">{{ t }}</option>
                        </select>
                        <InputError :message="form.errors[`vehicles.${index}.type`]" />
                    </div>
                    <div>
                        <InputLabel :for="'fuel_' + index" value="Fuel Capacity (L)" />
                        <TextInput
                            :id="'fuel_' + index"
                            v-model="row.fuel_capacity_l"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        />
                        <InputError :message="form.errors[`vehicles.${index}.fuel_capacity_l`]" />
                    </div>
                    <div class="sm:col-span-6">
                        <InputLabel :for="'location_' + index" value="Location" />
                        <TextInput
                            :id="'location_' + index"
                            v-model="row.location"
                            type="text"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        />
                        <InputError :message="form.errors[`vehicles.${index}.location`]" />
                    </div>
                    <div class="sm:col-span-6 flex justify-end">
                        <button
                            type="button"
                            @click="removeRow(index)"
                            class="text-sm text-red-600 hover:text-red-800"
                            :disabled="rows.length <= 1"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <button
                    type="button"
                    @click="addRow"
                    class="rounded border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Add Vehicle
                </button>
                <PrimaryButton :disabled="form.processing">
                    Continue
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
