<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    tickets: Object,
    vehicles: Array,
});

const showForm = ref(false);

const form = useForm({
    vehicle_id: '',
    issue_description: '',
    priority: 'Medium',
});

const submit = () => {
    form.post(route('maintenance.store'), {
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const statusBadge = (status) => {
    const colors = {
        Open: 'bg-gray-100 text-gray-800',
        'In Progress': 'bg-gray-100 text-gray-800',
        Resolved: 'bg-gray-100 text-gray-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const priorityBadge = (priority) => {
    const colors = {
        Low: 'bg-gray-100 text-gray-800',
        Medium: 'bg-gray-100 text-gray-800',
        Critical: 'bg-gray-100 text-gray-800',
    };
    return colors[priority] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Maintenance Queue</h2>
                <button @click="showForm = !showForm" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    {{ showForm ? 'Cancel' : 'New Ticket' }}
                </button>
            </div>
        </template>

        <div v-if="showForm" class="mb-6 rounded-md border border-gray-200 bg-white p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-gray-900">Report Issue</h3>
            <form @submit.prevent="submit" class="mt-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                    <select v-model="form.vehicle_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                        <option value="">Select a vehicle</option>
                        <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.vehicle_code }} - {{ v.model }}</option>
                    </select>
                    <div v-if="form.errors.vehicle_id" class="mt-1 text-sm text-red-600">{{ form.errors.vehicle_id }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Issue Description</label>
                    <textarea v-model="form.issue_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900"></textarea>
                    <div v-if="form.errors.issue_description" class="mt-1 text-sm text-red-600">{{ form.errors.issue_description }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                    <select v-model="form.priority" class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
                <button type="submit" :disabled="form.processing" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 disabled:opacity-50">
                    {{ form.processing ? 'Submitting...' : 'Submit Ticket' }}
                </button>
            </form>
        </div>

        <div class="rounded-md border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900">Tickets</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Vehicle</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3">Reported</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="ticket in tickets.data" :key="ticket.id">
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">{{ ticket.vehicle.vehicle_code }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                <span :class="[priorityBadge(ticket.priority), 'inline-flex rounded-full px-2 text-xs font-semibold']">{{ ticket.priority }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                <span :class="[statusBadge(ticket.status), 'inline-flex rounded-full px-2 text-xs font-semibold']">{{ ticket.status }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ ticket.issue_description }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ new Date(ticket.reported_at).toLocaleString() }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium">
                                <Link v-if="ticket.status !== 'Resolved'" :href="route('maintenance.update', ticket.id)" method="patch" :data="{ status: 'Resolved' }" as="button" class="text-gray-700 hover:text-gray-900">Resolve</Link>
                            </td>
                        </tr>
                        <tr v-if="!tickets.data.length">
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No tickets found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between border-t border-gray-200 px-4 py-3">
                <Link v-if="tickets.prev_page_url" :href="tickets.prev_page_url" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</Link>
                <div v-else></div>
                <Link v-if="tickets.next_page_url" :href="tickets.next_page_url" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</Link>
                <div v-else></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
