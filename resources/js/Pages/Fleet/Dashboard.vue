<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    fleet_summary: Object,
    active_shifts: Array,
    total_tonnage_today: Number,
    alerts: Array,
    vehicles: Array,
})

const vehicleStatusBadge = (status) => {
    const colors = {
        Active: 'bg-green-100 text-green-800',
        Idle: 'bg-yellow-100 text-yellow-800',
        Maintenance: 'bg-red-100 text-red-800',
    }
    return colors[status] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Fleet Dashboard</h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total Fleet</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900">{{ fleet_summary.total }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Active</div>
                            <div class="mt-2 text-3xl font-bold text-green-600">{{ fleet_summary.active }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Idle</div>
                            <div class="mt-2 text-3xl font-bold text-yellow-600">{{ fleet_summary.idle }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Maintenance</div>
                            <div class="mt-2 text-3xl font-bold text-red-600">{{ fleet_summary.maintenance }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Active Shifts</h3>
                            <div v-if="active_shifts.length === 0" class="mt-4 text-gray-500">No active shifts.</div>
                            <div v-else class="mt-4 space-y-3">
                                <div v-for="shift in active_shifts" :key="shift.id" class="flex items-center justify-between rounded-lg border border-gray-200 p-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ shift.vehicle.vehicle_code }} - {{ shift.vehicle.model }}</div>
                                        <div class="text-sm text-gray-500">{{ shift.operator.name }} @ {{ shift.pit_location }}</div>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">On Shift</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Shift Tonnage</h3>
                            <div class="mt-4 text-4xl font-bold text-gray-900">{{ total_tonnage_today }}</div>
                            <div class="mt-1 text-sm text-gray-500">tonnes moved today</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Fleet Inventory</h3>
                            <div class="mt-4 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Code</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Type</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Model</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            <tr v-for="vehicle in vehicles" :key="vehicle.id">
                                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ vehicle.vehicle_code }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ vehicle.type }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ vehicle.model }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                    <span :class="[vehicleStatusBadge(vehicle.status), 'inline-flex rounded-full px-2 text-xs font-semibold leading-5']">{{ vehicle.status }}</span>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                                    <Link :href="route('fleet.vehicles.show', vehicle.id)" class="text-indigo-600 hover:text-indigo-900">View</Link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Active Alerts</h3>
                            <div v-if="alerts.length === 0" class="mt-4 text-gray-500">No open alerts.</div>
                            <div v-else class="mt-4 space-y-3">
                                <div v-for="alert in alerts" :key="alert.id" class="rounded-lg border border-red-200 bg-red-50 p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="font-medium text-red-800">{{ alert.vehicle.vehicle_code }}</div>
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">{{ alert.priority }}</span>
                                    </div>
                                    <div class="mt-1 text-sm text-red-700">{{ alert.issue_description }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
