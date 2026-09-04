<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    vehicles: Array,
    operators: Array,
    active_shifts: Array,
})

const form = useForm({
    vehicle_id: '',
    operator_id: '',
    pit_location: '',
    target_tonnage: '',
})

const submit = () => {
    form.post(route('dispatch.store'), {
        onSuccess: () => form.reset(),
    })
}

const endShift = (id) => {
    if (confirm('End this shift?')) {
        useForm().post(route('dispatch.end', id))
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dispatch Control Panel</h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">New Dispatch</h3>
                            <form @submit.prevent="submit" class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                                    <select v-model="form.vehicle_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select a vehicle</option>
                                        <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.vehicle_code }} - {{ v.model }}</option>
                                    </select>
                                    <div v-if="form.errors.vehicle_id" class="mt-1 text-sm text-red-600">{{ form.errors.vehicle_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Operator</label>
                                    <select v-model="form.operator_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select an operator</option>
                                        <option v-for="op in operators" :key="op.id" :value="op.id">{{ op.name }} ({{ op.license_number }})</option>
                                    </select>
                                    <div v-if="form.errors.operator_id" class="mt-1 text-sm text-red-600">{{ form.errors.operator_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pit Location</label>
                                    <input v-model="form.pit_location" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                    <div v-if="form.errors.pit_location" class="mt-1 text-sm text-red-600">{{ form.errors.pit_location }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Target Tonnage (optional)</label>
                                    <input v-model="form.target_tonnage" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                    <div v-if="form.errors.target_tonnage" class="mt-1 text-sm text-red-600">{{ form.errors.target_tonnage }}</div>
                                </div>
                                <button type="submit" :disabled="form.processing" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 disabled:opacity-50">
                                    {{ form.processing ? 'Dispatching...' : 'Create Dispatch' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Active Shifts</h3>
                            <div v-if="active_shifts.length === 0" class="mt-4 text-gray-500">No active shifts.</div>
                            <div v-else class="mt-4 space-y-3">
                                <div v-for="shift in active_shifts" :key="shift.id" class="flex items-center justify-between rounded-lg border border-gray-200 p-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ shift.vehicle.vehicle_code }} - {{ shift.vehicle.model }}</div>
                                        <div class="text-sm text-gray-500">{{ shift.operator.name }} @ {{ shift.pit_location }}</div>
                                        <div class="text-xs text-gray-400">Target: {{ shift.target_tonnage }} t</div>
                                    </div>
                                    <button @click="endShift(shift.id)" class="rounded-md bg-red-600 px-3 py-1.5 text-sm text-white hover:bg-red-700">End Shift</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
