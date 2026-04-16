<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage, Link } from '@inertiajs/vue3';

const props = defineProps({ booking: Object });

const auth = usePage().props.auth.user;
const role = auth.role?.toLowerCase();
const userId = Number(auth.id);

const isMyTurn = () => {
    if (role !== 'approver') return false;
    if (props.booking.status === 'pending' && Number(props.booking.approver_1_id) === userId) return true;
    if (props.booking.status === 'approved_level_1' && Number(props.booking.approver_2_id) === userId) return true;
    return false;
};

const approve = () => router.post(route('bookings.approve', props.booking.id));
const reject  = () => router.post(route('bookings.reject',  props.booking.id));

const statusConfig = {
    pending:          { label: 'Pending',           classes: 'bg-yellow-100 text-yellow-800 border-yellow-200' },
    approved_level_1: { label: 'Approved — Level 1', classes: 'bg-blue-100 text-blue-800 border-blue-200' },
    approved:         { label: 'Fully Approved',     classes: 'bg-green-100 text-green-800 border-green-200' },
    rejected:         { label: 'Rejected',           classes: 'bg-red-100 text-red-800 border-red-200' },
};

const statusInfo = () => statusConfig[props.booking.status] ?? { label: props.booking.status, classes: 'bg-gray-100 text-gray-700' };

const approvalSteps = [
    {
        level: 1,
        label: 'Level 1 Approval',
        approver: props.booking.approver1,
        done: ['approved_level_1', 'approved'].includes(props.booking.status),
        rejected: props.booking.status === 'rejected' && props.booking.status !== 'approved_level_1',
    },
    {
        level: 2,
        label: 'Level 2 Approval',
        approver: props.booking.approver2,
        done: props.booking.status === 'approved',
        rejected: props.booking.status === 'rejected' && ['approved_level_1'].includes(props.booking.status),
    },
];
</script>

<template>
    <Head title="Booking Details" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('dashboard')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-800">Booking Details</h2>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border" :class="statusInfo().classes">
                    {{ statusInfo().label }}
                </span>
            </div>
        </template>

        <div class="py-10 px-4 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Vehicle Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-4">Vehicle</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 17H5a2 2 0 01-2-2V9a2 2 0 012-2h1M16 17h3a2 2 0 002-2V9a2 2 0 00-2-2h-1M8 17V7a2 2 0 012-2h4a2 2 0 012 2v10M8 17h8"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">{{ booking.vehicle?.model_name }}</p>
                        <p class="text-sm text-gray-400">{{ booking.vehicle?.plate_number }} · {{ booking.vehicle?.ownership }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-4">Booking Info</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Driver</p>
                        <p class="font-semibold text-gray-800">{{ booking.driver_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Requested By</p>
                        <p class="font-semibold text-gray-800">{{ booking.user?.name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Start Date</p>
                        <p class="font-semibold text-gray-800">{{ booking.start_date }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">End Date</p>
                        <p class="font-semibold text-gray-800">{{ booking.end_date }}</p>
                    </div>
                </div>
            </div>

            <!-- Approval Timeline -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-6">Approval Progress</h3>
                <div class="relative">
                    <!-- Connector line -->
                    <div class="absolute left-5 top-5 bottom-5 w-0.5 bg-gray-100"></div>

                    <div class="space-y-6">
                        <div v-for="step in approvalSteps" :key="step.level" class="flex items-start gap-4 relative">
                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 z-10"
                                :class="step.done ? 'bg-green-500' : step.rejected ? 'bg-red-400' : 'bg-gray-200'">
                                <svg v-if="step.done" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg v-else-if="step.rejected" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span v-else class="text-xs font-bold text-gray-500">{{ step.level }}</span>
                            </div>
                            <!-- Info -->
                            <div class="pt-1.5">
                                <p class="font-semibold text-gray-700 text-sm">{{ step.label }}</p>
                                <p class="text-xs text-gray-400">{{ step.approver?.name ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (only shown if it's the approver's turn) -->
            <div v-if="isMyTurn()" class="bg-white shadow rounded-lg p-6 flex gap-3 justify-end">
                <p class="text-sm text-gray-500 mr-auto self-center">It's your turn to review this booking.</p>
                <button @click="reject"
                    class="px-5 py-2 rounded-lg border border-red-300 text-red-600 font-semibold text-sm hover:bg-red-50 transition">
                    Reject
                </button>
                <button @click="approve"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 transition">
                    Approve
                </button>
            </div>

        </div>
    </AuthenticatedLayout>
</template>