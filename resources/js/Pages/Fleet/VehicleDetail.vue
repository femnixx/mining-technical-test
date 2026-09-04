<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    vehicle: Object,
    telemetry: Array,
    maintenance: Array,
    latest_telemetry: Object,
    fuel_trend: Array,
    dispatch_history: Array,
});

const sortedTelemetry = computed(() => {
    return [...props.telemetry].sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp))
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-900">{{ vehicle.vehicle_code }} - {{ vehicle.model }}</h2>
                <Link :href="route('fleet.dashboard')" class="text-sm font-medium text-gray-700 hover:text-gray-900">&larr; Back to Fleet</Link>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-md border border-gray-200 bg-white p-4">
                <h3 class="text-sm font-semibold text-gray-900">Vehicle Specs</h3>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Type</dt><dd class="font-medium text-gray-900">{{ vehicle.type }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Model</dt><dd class="font-medium text-gray-900">{{ vehicle.model }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd class="font-medium text-gray-900">{{ vehicle.status }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Fuel Capacity</dt><dd class="font-medium text-gray-900">{{ vehicle.fuel_capacity_l }} L</dd></div>
                </dl>
            </div>

            <div class="rounded-md border border-gray-200 bg-white p-4">
                <h3 class="text-sm font-semibold text-gray-900">Latest Telemetry</h3>
                <div v-if="latest_telemetry" class="mt-4 grid grid-cols-2 gap-4">
                    <div><div class="text-xs text-gray-500">Speed</div><div class="text-lg font-semibold text-gray-900">{{ latest_telemetry.speed_kmh }} km/h</div></div>
                    <div><div class="text-xs text-gray-500">Fuel</div><div class="text-lg font-semibold text-gray-900">{{ latest_telemetry.fuel_level_pct }}%</div></div>
                    <div><div class="text-xs text-gray-500">Engine Temp</div><div class="text-lg font-semibold text-gray-900">{{ latest_telemetry.engine_temp_c }}°C</div></div>
                    <div><div class="text-xs text-gray-500">Load</div><div class="text-lg font-semibold text-gray-900">{{ latest_telemetry.load_tonnage }} t</div></div>
                </div>
                <div v-else class="mt-4 text-sm text-gray-500">No telemetry data available.</div>
            </div>

            <div class="rounded-md border border-gray-200 bg-white p-4">
                <h3 class="text-sm font-semibold text-gray-900">Fuel Trend</h3>
                <div class="mt-4 h-40">
                    <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="h-full w-full">
                        <polyline :fill="'none'" :stroke="'#111827'" :stroke-width="'2'" :points="fuel_trend.map((pt, i) => `${(i / (fuel_trend.length - 1)) * 100},${100 - pt.fuel_level_pct}`).join(' ')" />
                    </svg>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span>24h ago</span>
                    <span>Now</span>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-md border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900">Telemetry History</div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-4 py-3">Time</th>
                                <th class="px-4 py-3">Speed</th>
                                <th class="px-4 py-3">Fuel</th>
                                <th class="px-4 py-3">Temp</th>
                                <th class="px-4 py-3">Load</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="log in sortedTelemetry.slice(0, 20)" :key="log.id">
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ new Date(log.timestamp).toLocaleString() }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ log.speed_kmh }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ log.fuel_level_pct }}%</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ log.engine_temp_c }}°C</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ log.load_tonnage }} t</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-md border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900">Maintenance History</div>
                <div class="p-4 space-y-3">
                    <div v-if="!maintenance.length" class="text-sm text-gray-500">No maintenance records.</div>
                    <div v-for="record in maintenance" :key="record.id" class="rounded-md border border-gray-200 p-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-800">{{ record.priority }}</span>
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-800">{{ record.status }}</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-700">{{ record.issue_description }}</div>
                        <div class="mt-1 text-xs text-gray-500">Reported: {{ new Date(record.reported_at).toLocaleString() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-md border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900">Dispatch History</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Operator</th>
                            <th class="px-4 py-3">Pit</th>
                            <th class="px-4 py-3">Target</th>
                            <th class="px-4 py-3">Shift</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="dispatch in dispatch_history" :key="dispatch.id">
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ new Date(dispatch.created_at).toLocaleDateString() }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ dispatch.operator.name }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ dispatch.pit_location }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ dispatch.target_tonnage }} t</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ new Date(dispatch.shift_start).toLocaleString() }}</td>
                        </tr>
                        <tr v-if="!dispatch_history.length">
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No dispatch history.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
