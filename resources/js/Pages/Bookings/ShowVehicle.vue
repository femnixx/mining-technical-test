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
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Booking #{{ booking.id }}</h2>
                <Link :href="route('bookings.index')" class="text-sm text-indigo-600 font-bold">← Back to List</Link>
            </div>
        </template>

        <div class="py-12 px-4 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1 space-y-6">
                <div class="bg-gray-900 text-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Vehicle Telemetry</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase">Unit Model</p>
                            <p class="text-lg font-bold">{{ booking.vehicle.model_name }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-gray-800 p-2 rounded">
                                <p class="text-[9px] text-gray-500 uppercase">Avg Fuel</p>
                                <p class="font-mono text-sm">{{ booking.vehicle.fuel_consumption }} L/km</p>
                            </div>
                            <div class="bg-gray-800 p-2 rounded">
                                <p class="text-[9px] text-gray-500 uppercase">Mileage</p>
                                <p class="font-mono text-sm">{{ booking.vehicle.total_km }} KM</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase">Current Position</p>
                            <p class="text-sm italic">📍 {{ booking.vehicle.current_location ?? 'On-Site Pit A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow border">
                    <h3 class="font-bold border-b pb-2 mb-4">Request Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <p><span class="text-gray-400">Driver:</span> {{ booking.driver_name }}</p>
                        <p><span class="text-gray-400">Requested By:</span> {{ booking.user.name }}</p>
                        <p><span class="text-gray-400">Period:</span> {{ booking.start_date }} to {{ booking.end_date }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow border overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b font-bold text-sm">Activity Log</div>
                    <div class="p-4 space-y-4">
                        <div v-for="log in logs" :key="log.id" class="flex gap-4 text-xs border-l-2 border-indigo-500 pl-4">
                            <div>
                                <p class="font-bold text-gray-800">{{ log.action }}</p>
                                <p class="text-gray-500">{{ log.description }}</p>
                                <p class="text-[10px] text-gray-400">{{ log.created_at }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>