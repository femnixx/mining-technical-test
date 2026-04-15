<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    total_bookings: Number,
    available_vehicles: Number,
    recent_bookings: Array,
});

const isMyTurn = (booking) => {
    const authId = usePage().props.auth.user.id;
    if (booking.status === 'pending' && booking.approver_1_id === authId) return true;
    if (booking.status === 'level_1_approved' && booking.approver_2_id === authId) return true;
    return false;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-white p-6 shadow-sm rounded-lg border-l-4 border-indigo-500">
                        <p class="text-sm text-gray-500 uppercase font-bold">Total Bookings</p>
                        <p class="text-2xl font-semibold">{{ total_bookings }}</p>
                    </div>
                    <div class="bg-white p-6 shadow-sm rounded-lg border-l-4 border-green-500">
                        <p class="text-sm text-gray-500 uppercase font-bold">Vehicles Available</p>
                        <p class="text-2xl font-semibold">{{ available_vehicles }}</p>
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Recent Bookings</h3>
                        
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="p-3 border-b">Vehicle</th>
                                    <th class="p-3 border-b">Driver</th>
                                    <th class="p-3 border-b">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="booking in recent_bookings" :key="booking.id">
                                    <td class="p-3 border-b">{{ booking.vehicle.model_name }}</td>
                                    <td class="p-3 border-b">{{ booking.driver_name }}</td>
                                    <td class="p-3 border-b capitalize">
                                        <span :class="{
                                            'text-yellow-600': booking.status === 'pending',
                                            'text-blue-600': booking.status === 'level_1_approved',
                                            'text-green-600': booking.status === 'approved'
                                        }">{{ booking.status.replace(/_/g, ' ') }}</span>
                                    </td>
                                </tr>
                                <tr v-for="booking in recent_bookings" :key="booking.id">
                                <td class="p-3 border-b">
                                    {{ booking.vehicle.model_name }} 
                                    <span class="text-xs text-gray-400">({{ booking.vehicle.ownership }})</span>
                                </td>
                                <td class="p-3 border-b">{{ booking.driver_name }}</td>
                                <td class="p-3 border-b">
                                    <span class="px-2 py-1 rounded text-xs" :class="statusClass(booking.status)">
                                        {{ booking.status }}
                                    </span>
                                </td>
                                <td class="p-3 border-b">
                                    <div v-if="$page.props.auth.user.role === 'approver' && isMyTurn(booking)">
                                        <button @click="approve(booking.id)" class="text-green-600 hover:underline mr-2">Approve</button>
                                        <button @click="reject(booking.id)" class="text-red-600 hover:underline">Reject</button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>