<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router, Link } from '@inertiajs/vue3';

const props = defineProps({
    total_bookings: Number,
    available_vehicles: Number,
    recent_bookings: Array,
    chart_data: Array
});

const isAdmin = () => usePage().props.auth.user.role?.toLowerCase() === 'admin';
const isApprover = () => usePage().props.auth.user.role?.toLowerCase() === 'approver';

const isMyTurn = (booking) => {
    const authId = Number(usePage().props.auth.user.id);
    const role = usePage().props.auth.user.role?.toLowerCase();

    if (role !== 'approver') return false;
    if (booking.status === 'pending' && Number(booking.approver_1_id) === authId) return true;
    if (booking.status === 'approved_level_1' && Number(booking.approver_2_id) === authId) return true;
    return false;
};

const approve = (id) => router.post(route('bookings.approve', id));
const reject = (id) => router.post(route('bookings.reject', id));

const statusClass = (status) => ({
    'bg-gray-100 text-gray-800': status === 'pending',
    'bg-blue-50 text-blue-800': status === 'approved_level_1',
    'bg-green-50 text-green-800': status === 'approved',
    'bg-red-50 text-red-800': status === 'rejected',
});
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Dashboard</h2>
                <div class="flex flex-wrap items-center gap-2">
                    <a :href="route('bookings.export')" class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export CSV
                    </a>
                    <Link v-if="isAdmin()" :href="route('bookings.create')" class="inline-flex items-center rounded-md border border-transparent bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800">
                        + New Booking
                    </Link>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:gap-6">
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Bookings</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ total_bookings }}</p>
            </div>
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Available Vehicles</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ available_vehicles }}</p>
            </div>
        </div>

        <div v-if="chart_data && chart_data.length > 0" class="mt-6 rounded-md border border-gray-200 bg-white p-4">
            <h3 class="text-sm font-semibold text-gray-900">Booking Trends</h3>
            <div class="mt-4 h-48 w-full">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="h-full w-full">
                    <polyline :fill="'none'" :stroke="'#111827'" :stroke-width="'2'" :points="chart_data.map((pt, i) => `${(i / (chart_data.length - 1)) * 100},${100 - pt.count}`).join(' ')" />
                </svg>
            </div>
        </div>

        <div class="mt-6 rounded-md border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-900">Recent Booking Activities</h3>
                <Link v-if="isAdmin() || isApprover()" :href="route('bookings.index')" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                    View Full History
                </Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Vehicle</th>
                            <th class="px-4 py-3">Driver</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="booking in recent_bookings" :key="booking.id">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ booking.vehicle?.model_name }}</div>
                                <div class="text-xs text-gray-500">{{ booking.vehicle?.plate_number }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ booking.driver_name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold" :class="statusClass(booking.status)">
                                    {{ booking.status.replace(/_/g, ' ') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div v-if="isMyTurn(booking)" class="flex justify-end gap-3">
                                    <button @click="approve(booking.id)" class="text-sm font-medium text-green-700 hover:text-green-900">Approve</button>
                                    <button @click="reject(booking.id)" class="text-sm font-medium text-red-700 hover:text-red-900">Reject</button>
                                </div>
                                <Link v-else-if="isAdmin() || isApprover()" :href="route('bookings.show', booking.id)" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                    View Details
                                </Link>
                                <span v-else class="text-xs text-gray-400">Waiting</span>
                            </td>
                        </tr>
                        <tr v-if="recent_bookings.length === 0">
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No recent activities found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
