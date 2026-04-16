<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    total_bookings: Number,
    available_vehicles: Number,
    recent_bookings: Array,
});

// Helper to check if the current user is the required approver for the current stage
const isMyTurn = (booking) => {
    const authId = usePage().props.auth.user.id;
    if (booking.status === 'pending' && booking.approver_1_id === authId) return true;
    if (booking.status === 'approved_level_1' && booking.approver_2_id === authId) return true;
    return false;
};

// Logic for Approval
const approve = (id) => {
    if (confirm('Are you sure you want to approve this booking?')) {
        router.post(route('bookings.approve', id));
    }
};

// Logic for Rejection
const reject = (id) => {
    if (confirm('Are you sure you want to reject this booking?')) {
        router.post(route('bookings.reject', id));
    }
};

// Helper for status styling
const statusClass = (status) => {
    return {
        'bg-yellow-100 text-yellow-800': status === 'pending',
        'bg-blue-100 text-blue-800': status === 'approved_level_1',
        'bg-green-100 text-green-800': status === 'approved',
        'bg-red-100 text-red-800': status === 'rejected',
    };
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
                        <p class="text-sm text-gray-500 uppercase font-bold text-xs">Total Bookings</p>
                        <p class="text-2xl font-semibold">{{ total_bookings }}</p>
                    </div>
                    <div class="bg-white p-6 shadow-sm rounded-lg border-l-4 border-green-500">
                        <p class="text-sm text-gray-500 uppercase font-bold text-xs">Vehicles Available</p>
                        <p class="text-2xl font-semibold">{{ available_vehicles }}</p>
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Recent Bookings</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="p-3 border-b">Vehicle</th>
                                        <th class="p-3 border-b">Driver</th>
                                        <th class="p-3 border-b">Status</th>
                                        <th class="p-3 border-b text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="booking in recent_bookings" :key="booking.id" class="hover:bg-gray-50">
                                        <td class="p-3 border-b">
                                            <div class="font-medium text-gray-900">{{ booking.vehicle?.model_name || 'N/A' }}</div>
                                            <div class="text-xs text-gray-400">{{ booking.vehicle?.plate_number }} ({{ booking.vehicle?.ownership }})</div>
                                        </td>
                                        <td class="p-3 border-b text-gray-700">{{ booking.driver_name }}</td>
                                        <td class="p-3 border-b capitalize">
                                            <span class="px-2 py-1 rounded text-xs font-bold" :class="statusClass(booking.status)">
                                                {{ booking.status.replace(/_/g, ' ') }}
                                            </span>
                                        </td>
                                        <td class="p-3 border-b text-right">
                                            <div v-if="$page.props.auth.user.role === 'approver' && isMyTurn(booking)" class="flex justify-end gap-3">
                                                <button @click="approve(booking.id)" class="text-green-600 hover:text-green-800 font-bold text-sm">Approve</button>
                                                <button @click="reject(booking.id)" class="text-red-600 hover:text-red-800 font-bold text-sm">Reject</button>
                                            </div>
                                            <span v-else class="text-xs text-gray-400">No Action Required</span>
                                        </td>
                                    </tr>
                                    <tr v-if="recent_bookings.length === 0">
                                        <td colspan="4" class="p-6 text-center text-gray-500">No recent bookings found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>