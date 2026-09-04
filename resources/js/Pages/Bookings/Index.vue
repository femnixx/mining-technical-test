<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ bookings: Array });

const statusClass = (status) => ({
    'bg-gray-100 text-gray-800': status === 'pending',
    'bg-blue-50 text-blue-800': status === 'approved_level_1',
    'bg-green-50 text-green-800': status === 'approved',
    'bg-red-50 text-red-800': status === 'rejected',
});
</script>

<template>
    <Head title="All Bookings" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">All Bookings</h2>
                <Link :href="route('dashboard')" class="text-sm font-medium text-gray-700 hover:text-gray-900">&larr; Back</Link>
            </div>
        </template>

        <div class="rounded-md border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Vehicle</th>
                            <th class="px-4 py-3">Driver</th>
                            <th class="px-4 py-3">Requested By</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="booking in bookings" :key="booking.id">
                            <td class="px-4 py-3">
                                <Link :href="route('vehicles.show', booking.vehicle_id)" class="font-medium text-gray-900 hover:text-gray-700">
                                    {{ booking.vehicle?.model_name }}
                                    <div class="text-xs text-gray-500">{{ booking.vehicle?.plate_number }} &middot; {{ booking.vehicle?.fuel_consumption }} L/km</div>
                                </Link>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ booking.driver_name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ booking.user?.name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold" :class="statusClass(booking.status)">
                                    {{ booking.status.replace(/_/g, ' ') }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="!bookings.length">
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No bookings found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
