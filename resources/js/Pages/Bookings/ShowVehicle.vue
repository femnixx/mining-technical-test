<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    booking: Object,
    logs: Array
});
</script>

<template>
    <Head title="Booking Details" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Booking #{{ booking.id }}</h2>
                <Link :href="route('bookings.index')" class="text-sm font-medium text-gray-700 hover:text-gray-900">&larr; Back to List</Link>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Vehicle Telemetry</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Unit Model</p>
                        <p class="text-lg font-semibold text-gray-900">{{ booking.vehicle.model_name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded border border-gray-200 p-2">
                            <p class="text-xs text-gray-500">Avg Fuel</p>
                            <p class="font-mono text-sm text-gray-900">{{ booking.vehicle.fuel_consumption }} L/km</p>
                        </div>
                        <div class="rounded border border-gray-200 p-2">
                            <p class="text-xs text-gray-500">Mileage</p>
                            <p class="font-mono text-sm text-gray-900">{{ booking.vehicle.total_km }} KM</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Current Position</p>
                        <p class="text-sm text-gray-700">{{ booking.vehicle.current_location ?? 'On-Site Pit A' }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-md border border-gray-200 bg-white p-4">
                    <h3 class="text-sm font-semibold text-gray-900">Request Information</h3>
                    <div class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                        <p><span class="text-gray-500">Driver:</span> {{ booking.driver_name }}</p>
                        <p><span class="text-gray-500">Requested By:</span> {{ booking.user.name }}</p>
                        <p><span class="text-gray-500">Period:</span> {{ booking.start_date }} to {{ booking.end_date }}</p>
                    </div>
                </div>

                <div class="rounded-md border border-gray-200 bg-white">
                    <div class="border-b border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900">Activity Log</div>
                    <div class="p-4 space-y-3">
                        <div v-for="log in logs" :key="log.id" class="text-xs border-l-2 border-gray-300 pl-3">
                            <p class="font-semibold text-gray-800">{{ log.action }}</p>
                            <p class="text-gray-600">{{ log.description }}</p>
                            <p class="text-gray-400">{{ log.created_at }}</p>
                        </div>
                        <div v-if="!logs.length" class="text-sm text-gray-500">No activity logs.</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
