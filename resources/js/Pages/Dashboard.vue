<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router, Link } from '@inertiajs/vue3'; // Added Link for better perf

const props = defineProps({
    total_bookings: Number,
    available_vehicles: Number,
    recent_bookings: Array,
});

// Admin check helper
const isAdmin = () => usePage().props.auth.user.role?.toLowerCase() === 'admin';

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
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-blue-100 text-blue-800': status === 'approved_level_1',
    'bg-green-100 text-green-800': status === 'approved',
    'bg-red-100 text-red-800': status === 'rejected',
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">Dashboard</h2>
                
                <Link v-if="isAdmin()" 
                      :href="route('bookings.create')" 
                      class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold transition">
                    + New Booking
                </Link>
            </div>
        </template>

        <div class="py-12 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 shadow rounded-lg border-l-4 border-indigo-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Total Bookings</p>
                    <p class="text-3xl font-bold">{{ total_bookings }}</p>
                </div>
                <div class="bg-white p-6 shadow rounded-lg border-l-4 border-green-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Available Vehicles</p>
                    <p class="text-3xl font-bold">{{ available_vehicles }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold">Recent Bookings</h3>
                    
                    <Link v-if="isAdmin()" 
                          :href="route('bookings.index')" 
                          class="text-xs text-indigo-600 hover:text-indigo-800 font-bold uppercase tracking-wider">
                        View All Log →
                    </Link>
                </div>

                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-600">
                        <tr>
                            <th class="p-4">Vehicle</th>
                            <th class="p-4">Driver</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="booking in recent_bookings" :key="booking.id" class="hover:bg-gray-50">
                            <td class="p-4">
                                <div class="font-bold">{{ booking.vehicle?.model_name }}</div>
                                <div class="text-xs text-gray-400">{{ booking.vehicle?.plate_number }}</div>
                            </td>
                            <td c@lass="p-4 text-gray-600">{{ booking.driver_name }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase" :class="statusClass(booking.status)">
                                    {{ booking.status.replace(/_/g, ' ') }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div v-if="isMyTurn(booking)" class="flex justify-end gap-2">
                                    <button @click="approve(booking.id)" class="text-green-600 font-bold hover:underline">Approve</button>
                                    <button @click="reject(booking.id)" class="text-red-600 font-bold hover:underline">Reject</button>
                                </div>
                                <Link v-else-if="isAdmin()" 
                                      :href="route('bookings.create', booking.id)" 
                                      class="text-xs text-gray-500 hover:text-indigo-600 font-bold">
                                    Details
                                </Link>
                                <span v-else class="text-xs text-gray-300">No Action Required</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>