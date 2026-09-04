<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    vehicles: Array,
    shifts: Array,
});

const selectedVehicle = ref(null);

const statusColor = (status) => {
    if (status === 'Active') return '#16a34a';
    if (status === 'Idle') return '#ca8a04';
    if (status === 'Maintenance') return '#6b7280';
    return '#374151';
};

const nodePositions = [
    { id: 'pit-a', label: 'Pit A', x: 180, y: 120 },
    { id: 'pit-b', label: 'Pit B', x: 420, y: 160 },
    { id: 'dump-a', label: 'Dump A', x: 120, y: 260 },
    { id: 'dump-b', label: 'Dump B', x: 520, y: 240 },
    { id: 'workshop', label: 'Workshop', x: 340, y: 340 },
    { id: 'office', label: 'Office', x: 520, y: 380 },
    { id: 'fuel', label: 'Fuel Bay', x: 160, y: 380 },
];

const nodeMap = Object.fromEntries(nodePositions.map((n) => [n.id, n]));

const pathPoints = computed(() => {
    const pts = [];
    const order = ['pit-a', 'dump-a', 'workshop', 'fuel', 'pit-b', 'dump-b', 'office'];
    order.forEach((id) => {
        const n = nodeMap[id];
        if (n) pts.push({ x: n.x, y: n.y });
    });
    return pts;
});

const activeCount = computed(() => props.vehicles.filter((v) => v.status === 'Active').length);
const idleCount = computed(() => props.vehicles.filter((v) => v.status === 'Idle').length);
const maintenanceCount = computed(() => props.vehicles.filter((v) => v.status === 'Maintenance').length);
</script>

<template>
    <div class="rounded-md border border-gray-200 bg-white p-3 sm:p-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Fleet Map</h3>
                <p class="text-xs text-gray-500">Schematic site overview. Click a vehicle for details.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-green-600"></span> Active {{ activeCount }}</span>
                <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-yellow-500"></span> Idle {{ idleCount }}</span>
                <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-gray-500"></span> Maintenance {{ maintenanceCount }}</span>
            </div>
        </div>

        <div class="mt-3 overflow-x-auto">
            <svg viewBox="0 0 640 420" class="h-auto w-full max-w-3xl">
                <rect x="0" y="0" width="640" height="420" fill="#f8fafc" rx="8" />

                <path
                    d="M 180 120 L 120 260 L 340 340 L 160 380 L 160 380 M 180 120 L 420 160 L 520 240 L 340 340 M 420 160 L 520 380"
                    fill="none"
                    stroke="#cbd5e1"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

                <g v-for="node in nodePositions" :key="node.id">
                    <circle :cx="node.x" :cy="node.y" r="18" fill="#ffffff" stroke="#94a3b8" stroke-width="2" />
                    <text :x="node.x" :y="node.y + 1" text-anchor="middle" dominant-baseline="middle" class="text-[10px] font-semibold fill-gray-700">{{ node.label }}</text>
                </g>

                <g v-for="vehicle in vehicles" :key="vehicle.id">
                    <circle
                        :cx="nodeMap[vehicle.current_location]?.x ?? 340"
                        :cy="nodeMap[vehicle.current_location]?.y ?? 340"
                        r="10"
                        :fill="statusColor(vehicle.status)"
                        stroke="#ffffff"
                        stroke-width="2"
                        class="cursor-pointer"
                        @click="selectedVehicle = vehicle"
                    />
                    <text
                        :x="(nodeMap[vehicle.current_location]?.x ?? 340) + 14"
                        :y="(nodeMap[vehicle.current_location]?.y ?? 340) + 4"
                        class="text-[10px] font-semibold fill-gray-800"
                    >
                        {{ vehicle.vehicle_code }}
                    </text>
                </g>
            </svg>
        </div>

        <div v-if="selectedVehicle" class="mt-3 rounded-md border border-gray-200 bg-gray-50 p-3 text-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-semibold text-gray-900">{{ selectedVehicle.vehicle_code }} - {{ selectedVehicle.model }}</div>
                    <div class="text-xs text-gray-600">{{ selectedVehicle.type }} &middot; {{ selectedVehicle.status }}</div>
                </div>
                <button class="text-xs font-medium text-gray-500 hover:text-gray-900" @click="selectedVehicle = null">Close</button>
            </div>
            <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-700 sm:grid-cols-4">
                <div><span class="text-gray-500">Fuel:</span> {{ selectedVehicle.fuel_capacity_l ?? '-' }} L</div>
                <div><span class="text-gray-500">Location:</span> {{ selectedVehicle.current_location ?? 'Unassigned' }}</div>
                <div><span class="text-gray-500">Total KM:</span> {{ selectedVehicle.total_km ?? '-' }}</div>
                <div><span class="text-gray-500">Ownership:</span> {{ selectedVehicle.ownership ?? '-' }}</div>
            </div>
        </div>
    </div>
</template>
