<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    bookings: Array,
});

const authId = Number(usePage().props.auth.user.id);
const role = usePage().props.auth.user.role?.toLowerCase();

const isMyTurn = (booking) => {
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

const statusLabel = (status) => status.replace(/_/g, ' ');
</script>

<template>
    <Head title="Approvals" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">Pending Approvals</h2>
        </template>

        <div class="py-12 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-700">Bookings Awaiting Your Approval</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-600 border-b">
                            <tr>
                                <th class="p-4">Vehicle Info</th>
                                <th class="p-4">Driver Name</th>
                                <th class="p-4">Approval Level</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="booking in bookings" :key="booking.id"
                                class="hover:bg-gray-50/80 transition"
                                :class="{ 'bg-amber-50/40': isMyTurn(booking) }">
                                <td class="p-4">
                                    <div class="font-bold text-gray-900">{{ booking.vehicle?.model_name }}</div>
                                    <div class="text-xs font-mono text-gray-400">{{ booking.vehicle?.plate_number }}</div>
                                </td>
                                <td class="p-4 text-gray-600 font-medium">{{ booking.driver_name }}</td>
                                <td class="p-4 text-gray-500 text-sm">
                                    <span v-if="booking.status === 'pending'">Level 1</span>
                                    <span v-else-if="booking.status === 'approved_level_1'">Level 2</span>
                                    <span v-else>—</span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight shadow-sm border"
                                          :class="statusClass(booking.status)">
                                        {{ statusLabel(booking.status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div v-if="isMyTurn(booking)" class="flex justify-end gap-3">
                                        <button @click="approve(booking.id)"
                                                class="text-green-600 text-xs font-black hover:text-green-800 uppercase tracking-tighter transition">
                                            Approve
                                        </button>
                                        <button @click="reject(booking.id)"
                                                class="text-red-600 text-xs font-black hover:text-red-800 uppercase tracking-tighter transition">
                                            Reject
                                        </button>
                                    </div>
                                    <span v-else class="text-[10px] text-gray-300 font-bold uppercase italic">
                                        Not your turn
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="!bookings || bookings.length === 0">
                                <td colspan="5" class="p-12 text-center text-gray-400 italic">
                                    No bookings awaiting approval.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>