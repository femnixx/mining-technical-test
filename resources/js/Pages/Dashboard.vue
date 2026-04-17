<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import VehicleChart from '@/Components/VehicleChart.vue'; // Importing the 'Graphic Thing'

const props = defineProps({
    total_bookings: Number,
    available_vehicles: Number,
    recent_bookings: Array,
    chart_data: Array
});

const isAdmin = () => usePage().props.auth.user.role?.toLowerCase() === 'admin';

const isMyTurn = (booking) => {
    const authId = Number(usePage().props.auth.user.id);
    const role = usePage().props.auth.user.role?.toLowerCase();

    if (role !== 'approver') return false;

    // Level 1 logic
    if (booking.status === 'pending' && Number(booking.approver_1_id) === authId) return true;
    // Level 2 logic
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
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">Fleet Monitoring Dashboard</h2>
                
                <div class="flex items-center gap-3">
                    <a :href="route('bookings.export')" 
                       class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export CSV
                    </a>

                    <Link v-if="isAdmin()" 
                          :href="route('bookings.create')" 
                          class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold transition shadow-sm">
                        + New Booking
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow rounded-lg border-l-4 border-indigo-500 flex justify-between items-end">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Bookings</p>
                        <p class="text-3xl font-bold text-gray-900">{{ total_bookings }}</p>
                    </div>
                    <div class="text-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white p-6 shadow rounded-lg border-l-4 border-green-500 flex justify-between items-end">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Available Vehicles</p>
                        <p class="text-3xl font-bold text-gray-900">{{ available_vehicles }}</p>
                    </div>
                    <div class="text-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div v-if="chart_data && chart_data.length > 0">
                <VehicleChart :chartData="chart_data" />
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-700">Recent Booking Activities</h3>
                    
                    <Link v-if="isAdmin()" 
                          :href="route('bookings.index')" 
                          class="text-xs text-indigo-600 hover:text-indigo-800 font-bold uppercase tracking-wider transition">
                        View Full History →
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-600 border-b">
                            <tr>
                                <th class="p-4">Vehicle Info</th>
                                <th class="p-4">Driver Name</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="booking in recent_bookings" :key="booking.id" class="hover:bg-gray-50/80 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-900">{{ booking.vehicle?.model_name }}</div>
                                    <div class="text-xs font-mono text-gray-400">{{ booking.vehicle?.plate_number }}</div>
                                </td>
                                <td class="p-4 text-gray-600 font-medium">{{ booking.driver_name }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight shadow-sm border" :class="statusClass(booking.status)">
                                        {{ booking.status.replace(/_/g, ' ') }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div v-if="isMyTurn(booking)" class="flex justify-end gap-3">
                                        <button @click="approve(booking.id)" class="text-green-600 text-xs font-black hover:text-green-800 uppercase tracking-tighter transition">Approve</button>
                                        <button @click="reject(booking.id)" class="text-red-600 text-xs font-black hover:text-red-800 uppercase tracking-tighter transition">Reject</button>
                                    </div>
                                    <Link v-else-if="isAdmin()" 
                                          :href="route('bookings.show', booking.id)" 
                                          class="text-xs text-gray-400 hover:text-indigo-600 font-bold transition">
                                        View Details
                                    </Link>
                                    <span v-else class="text-[10px] text-gray-300 font-bold uppercase italic">Waiting</span>
                                </td>
                            </tr>
                            <tr v-if="recent_bookings.length === 0">
                                <td colspan="4" class="p-12 text-center text-gray-400 italic">No recent activities found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>