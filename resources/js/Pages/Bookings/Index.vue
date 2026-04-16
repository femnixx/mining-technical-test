<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ bookings: Array });

const statusClass = (status) => ({
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-blue-100 text-blue-800': status === 'approved_level_1',
    'bg-green-100 text-green-800': status === 'approved',
    'bg-red-100 text-red-800': status === 'rejected',
});
</script>

<template>
    <Head title="All Bookings" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">All Bookings</h2>
        </template>

        <div class="py-10 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <Link class="ml-4 my-2" :href="route('dashboard')">Back to Dashboard</Link>
                <table class="w-full text-left">
                    
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-600">
                        <tr>
                            <th class="p-4">Vehicle</th>
                            <th class="p-4">Driver</th>
                            <th class="p-4">Requested By</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-gray-50">
                            <td class="p-4">
                                <Link :href="route('bookings.show', booking.id)" class="hover:text-indigo-600 transition">
                                    <div class="font-bold">{{ booking.vehicle?.model_name }}</div>
                                    <div class="text-xs text-gray-400">{{ booking.vehicle?.plate_number }}</div>
                                </Link>
                            </td>
                            <td class="p-4 text-gray-600">{{ booking.driver_name }}</td>
                            <td class="p-4 text-gray-600">{{ booking.user?.name }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase" :class="statusClass(booking.status)">
                                    {{ booking.status.replace(/_/g, ' ') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>@