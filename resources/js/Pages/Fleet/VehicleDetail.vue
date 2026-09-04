<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    vehicle: Object,
    telemetry: Array,
    maintenance: Array,
    latest_telemetry: Object,
    fuel_trend: Array,
    dispatch_history: Array,
})

const statusBadge = (status) => {
    const colors = {
        Active: 'bg-green-100 text-green-800',
        Idle: 'bg-yellow-100 text-yellow-800',
        Maintenance: 'bg-red-100 text-red-800',
    }
    return colors[status] || 'bg-gray-100 text-gray-800'
}

const priorityBadge = (priority) => {
    const colors = {
        Low: 'bg-gray-100 text-gray-800',
        Medium: 'bg-yellow-100 text-yellow-800',
        Critical: 'bg-red-100 text-red-800',
    }
    return colors[priority] || 'bg-gray-100 text-gray-800'
}

const sortedTelemetry = computed(() => {
    return [...props.telemetry].sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp))
})
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ vehicle.vehicle_code }} - {{ vehicle.model }}</h2>
                <Link :href="route('fleet.dashboard')" class="text-sm text-indigo-600 hover:text-indigo-900">Back to Fleet Dashboard</Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Vehicle Specs</h3>
                            <dl class="mt-4 space-y-3">
                                <div class="flex justify-between"><dt class="text-sm text-gray-500">Type</dt><dd class="text-sm font-medium text-gray-900">{{ vehicle.type }}</dd></div>
                                <div class="flex justify-between"><dt class="text-sm text-gray-500">Model</dt><dd class="text-sm font-medium text-gray-900">{{ vehicle.model }}</dd></div>
                                <div class="flex justify-between"><dt class="text-sm text-gray-500">Status</dt><dd><span :class="[statusBadge(vehicle.status), 'inline-flex rounded-full px-2 text-xs font-semibold leading-5']">{{ vehicle.status }}</span></dd></div>
                                <div class="flex justify-between"><dt class="text-sm text-gray-500">Fuel Capacity</dt><dd class="text-sm font-medium text-gray-900">{{ vehicle.fuel_capacity_l }} L</dd></div>
                            </dl>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Latest Telemetry</h3>
                            <div v-if="latest_telemetry" class="mt-4 grid grid-cols-2 gap-4">
                                <div><div class="text-sm text-gray-500">Speed</div><div class="text-lg font-semibold">{{ latest_telemetry.speed_kmh }} km/h</div></div>
                                <div><div class="text-sm text-gray-500">Fuel</div><div class="text-lg font-semibold">{{ latest_telemetry.fuel_level_pct }}%</div></div>
                                <div><div class="text-sm text-gray-500">Engine Temp</div><div class="text-lg font-semibold">{{ latest_telemetry.engine_temp_c }}°C</div></div>
                                <div><div class="text-sm text-gray-500">Load</div><div class="text-lg font-semibold">{{ latest_telemetry.load_tonnage }} t</div></div>
                            </div>
                            <div v-else class="mt-4 text-gray-500">No telemetry data available.</div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Fuel Trend</h3>
                            <div class="mt-4 h-40">
                                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="h-full w-full">
                                    <polyline
                                        :fill="'none'"
                                        :stroke="'#4f46e5'"
                                        :stroke-width="'2'"
                                        :points="fuel_trend.map((pt, i) => `${(i / (fuel_trend.length - 1)) * 100},${100 - pt.fuel_level_pct}`).join(' ')"
                                    />
                                </svg>
                            </div>
                            <div class="mt-2 flex justify-between text-xs text-gray-500">
                                <span>24h ago</span>
                                <span>Now</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Telemetry History</h3>
                            <div class="mt-4 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Time</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Speed</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Fuel</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Temp</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Load</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            <tr v-for="log in sortedTelemetry.slice(0, 20)" :key="log.id">
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ new Date(log.timestamp).toLocaleString() }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ log.speed_kmh }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ log.fuel_level_pct }}%</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ log.engine_temp_c }}°C</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ log.load_tonnage }} t</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Maintenance History</h3>
                            <div v-if="maintenance.length === 0" class="mt-4 text-gray-500">No maintenance records.</div>
                            <div v-else class="mt-4 space-y-3">
                                <div v-for="record in maintenance" :key="record.id" class="rounded-lg border border-gray-200 p-4">
                                    <div class="flex items-center justify-between">
                                        <span :class="[priorityBadge(record.priority), 'inline-flex rounded-full px-2 text-xs font-semibold leading-5']">{{ record.priority }}</span>
                                        <span :class="[statusBadge(record.status), 'inline-flex rounded-full px-2 text-xs font-semibold leading-5']">{{ record.status }}</span>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700">{{ record.issue_description }}</div>
                                    <div class="mt-1 text-xs text-gray-500">Reported: {{ new Date(record.reported_at).toLocaleString() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Dispatch History</h3>
                        <div v-if="dispatch_history.length === 0" class="mt-4 text-gray-500">No dispatch history.</div>
                        <div v-else class="mt-4 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Operator</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Pit</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Target</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Shift</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="dispatch in dispatch_history" :key="dispatch.id">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ new Date(dispatch.created_at).toLocaleDateString() }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ dispatch.operator.name }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ dispatch.pit_location }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ dispatch.target_tonnage }} t</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ new Date(dispatch.shift_start).toLocaleString() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
