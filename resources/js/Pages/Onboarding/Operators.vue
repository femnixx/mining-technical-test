<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineOptions({ layout: AuthenticatedLayout });

const statuses = ['On Shift', 'Off Duty', 'On Break'];
const rows = ref([
    { name: '', license_number: '', status: 'Off Duty' },
]);

function addRow() {
    rows.value.push({ name: '', license_number: '', status: 'Off Duty' });
}

function removeRow(index) {
    if (rows.value.length > 1) {
        rows.value.splice(index, 1);
    }
}

const form = useForm({
    operators: rows.value,
});

function submit() {
    form.operators = rows.value;
    form.post(route('onboarding.operators.store'), {
        onFinish: () => {},
    });
}
</script>

<template>
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Onboarding</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Step 3 of 3 - Add your operators</p>
        </div>

        <form @submit.prevent="submit" class="max-w-3xl">
            <div class="space-y-4">
                <div v-for="(row, index) in rows" :key="index" class="grid grid-cols-1 gap-4 rounded border border-gray-300 bg-white p-4 dark:border-gray-600 dark:bg-gray-800 sm:grid-cols-4">
                    <div class="sm:col-span-2">
                        <InputLabel :for="'name_' + index" value="Name" />
                        <TextInput
                            :id="'name_' + index"
                            v-model="row.name"
                            type="text"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors[`operators.${index}.name`]" />
                    </div>
                    <div>
                        <InputLabel :for="'license_' + index" value="License Number" />
                        <TextInput
                            :id="'license_' + index"
                            v-model="row.license_number"
                            type="text"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors[`operators.${index}.license_number`]" />
                    </div>
                    <div>
                        <InputLabel :for="'status_' + index" value="Status" />
                        <select
                            :id="'status_' + index"
                            v-model="row.status"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        >
                            <option v-for="s in statuses" :key="s" :value="s">{{ s }}</option>
                        </select>
                        <InputError :message="form.errors[`operators.${index}.status`]" />
                    </div>
                    <div class="sm:col-span-4 flex justify-end">
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
                    Add Operator
                </button>
                <PrimaryButton :disabled="form.processing">
                    Complete Setup
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
