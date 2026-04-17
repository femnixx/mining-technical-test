<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    vehicle: Object,
    booking_history: Array
});
</script>

<template>
    <Head :title="vehicle?.model_name || 'Vehicle Profile'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">{{ vehicle?.model_name }} Profile</h2>
                <Link :href="route('dashboard')" class="text-sm text-gray-500 hover:underline">← Dashboard</Link>
            </div>
        </template>

        <div class="py-12 max-w-3xl mx-auto px-4">
            <div v-if="vehicle" class="bg-white shadow rounded-lg overflow-hidden">
                <div class="bg-indigo-600 p-6 text-white flex justify-between items-center">
                    <div>
                        <p class="text-xs uppercase font-bold opacity-75">Plate Number</p>
                        <p class="text-2xl font-mono font-black">{{ vehicle.plate_number }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 bg-white text-indigo-600 rounded-full text-xs font-bold uppercase">
                            {{ vehicle.is_available ? 'Available' : 'In Use' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 border-b">
                    <div class="p-6 border-r">
                        <p class="text-gray-400 text-xs uppercase font-bold mb-1">Fuel Consumption</p>
                        <p class="text-xl font-bold text-gray-800">{{ vehicle.fuel_consumption }} L/km</p>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-400 text-xs uppercase font-bold mb-1">Total Distance</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ vehicle.distance_km?.toLocaleString() ?? '0' }} KM
                        </p>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-bold">Vehicle Type</span>
                        <span class="text-gray-800">{{ vehicle.type || 'Light Vehicle' }}</span>
                    </div>
                    <div class="flex justify-between text-sm border-t pt-4">
                        <span class="text-gray-500 font-bold">Current Location</span>
                        <span class="text-gray-800 italic">📍 {{ vehicle.location?.name || 'Main Site' }}</span>
                    </div>
                </div>
                <div class="mt-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4 mx-4">Booking & Approval History</h3>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Period / Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Requested By</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Approvers</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="booking in booking_history" :key="booking.id">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ booking.driver_name }}</div>
                        <div class="text-xs text-gray-500">{{ new Date(booking.start_date).toLocaleDateString() }} - {{ new Date(booking.end_date).toLocaleDateString() }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ booking.admin?.name || 'System' }}
                    </td>
                    <td class="px-6 py-4 text-xs space-y-1">
                        <div class="flex items-center">
                            <span class="w-16 font-bold">Lvl 1:</span> {{ booking.approver1?.name }}
                        </div>
                        <div class="flex items-center">
                            <span class="w-16 font-bold">Lvl 2:</span> {{ booking.approver2?.name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span :class="{
                            'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                            'bg-blue-100 text-blue-800': booking.status === 'approved_level_1',
                            'bg-green-100 text-green-800': booking.status === 'approved',
                            'bg-red-100 text-red-800': booking.status === 'rejected',
                        }" class="px-2 py-1 rounded-full text-xs font-semibold uppercase">
                            {{ booking.status.replace('_', ' ') }}
                        </span>
                    </td>
                </tr>
                <tr v-if="booking_history.length === 0">
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                        No previous booking records found for this vehicle.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
            </div>
            

            <div v-else class="text-center py-10 text-gray-500">
                Loading vehicle data...
            </div>
        </div>
    </AuthenticatedLayout>
</template>