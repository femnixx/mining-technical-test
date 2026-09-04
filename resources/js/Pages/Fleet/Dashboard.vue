<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FleetMap from '@/Components/FleetMap.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    fleet_summary: Object,
    active_shifts: Array,
    total_tonnage_today: Number,
    alerts: Array,
    vehicles: Array,
});

const statusBadge = (status) => {
    const colors = {
        Active: 'bg-gray-100 text-gray-800',
        Idle: 'bg-gray-100 text-gray-800',
        Maintenance: 'bg-gray-100 text-gray-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900">Fleet Dashboard</h2>
        </template>

        <FleetMap :vehicles="vehicles" :shifts="active_shifts" class="mb-6" />

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Fleet</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ fleet_summary.total }}</p>
            </div>
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Active</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ fleet_summary.active }}</p>
            </div>
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Idle</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ fleet_summary.idle }}</p>
            </div>
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Maintenance</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ fleet_summary.maintenance }}</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-md border border-gray-200 bg-white p-4">
                <h3 class="text-sm font-semibold text-gray-900">Active Shifts</h3>
                <div v-if="active_shifts.length === 0" class="mt-4 text-sm text-gray-500">No active shifts.</div>
                <div v-else class="mt-4 space-y-3">
                    <div v-for="shift in active_shifts" :key="shift.id" class="flex items-center justify-between rounded-md border border-gray-200 p-3">
                        <div>
                            <div class="font-medium text-gray-900">{{ shift.vehicle.vehicle_code }} - {{ shift.vehicle.model }}</div>
                            <div class="text-sm text-gray-500">{{ shift.operator.name }} @ {{ shift.pit_location }}</div>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">On Shift</span>
                    </div>
                </div>
            </div>

            <div class="rounded-md border border-gray-200 bg-white p-4">
                <h3 class="text-sm font-semibold text-gray-900">Shift Tonnage</h3>
                <div class="mt-4 text-4xl font-bold text-gray-900">{{ total_tonnage_today }}</div>
                <div class="mt-1 text-sm text-gray-500">tonnes moved today</div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-md border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900">Fleet Inventory</div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-4 py-3">Code</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Model</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="vehicle in vehicles" :key="vehicle.id">
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">{{ vehicle.vehicle_code }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ vehicle.type }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ vehicle.model }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm">
                                    <span :class="[statusBadge(vehicle.status), 'inline-flex rounded-full px-2 text-xs font-semibold']">{{ vehicle.status }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium">
                                    <Link :href="route('fleet.vehicles.show', vehicle.id)" class="text-gray-700 hover:text-gray-900">View</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-md border border-gray-200 bg-white p-4">
                <h3 class="text-sm font-semibold text-gray-900">Active Alerts</h3>
                <div v-if="alerts.length === 0" class="mt-4 text-sm text-gray-500">No open alerts.</div>
                <div v-else class="mt-4 space-y-3">
                    <div v-for="alert in alerts" :key="alert.id" class="rounded-md border border-gray-200 p-3">
                        <div class="flex items-center justify-between">
                            <div class="font-medium text-gray-900">{{ alert.vehicle.vehicle_code }}</div>
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">{{ alert.priority }}</span>
                        </div>
                        <div class="mt-1 text-sm text-gray-700">{{ alert.issue_description }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
