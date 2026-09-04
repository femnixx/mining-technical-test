<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    tickets: Object,
    vehicles: Array,
})

const showForm = ref(false)

const form = useForm({
    vehicle_id: '',
    issue_description: '',
    priority: 'Medium',
})

const submit = () => {
    form.post(route('maintenance.store'), {
        onSuccess: () => {
            form.reset()
            showForm.value = false
        },
    })
}

const resolveForm = (id) => useForm({ status: 'Resolved' })

const statusBadge = (status) => {
    const colors = {
        Open: 'bg-yellow-100 text-yellow-800',
        'In Progress': 'bg-blue-100 text-blue-800',
        Resolved: 'bg-green-100 text-green-800',
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
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Maintenance Queue</h2>
                <button @click="showForm = !showForm" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                    {{ showForm ? 'Cancel' : 'New Ticket' }}
                </button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="showForm" class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Report Issue</h3>
                        <form @submit.prevent="submit" class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                                <select v-model="form.vehicle_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select a vehicle</option>
                                    <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.vehicle_code }} - {{ v.model }}</option>
                                </select>
                                <div v-if="form.errors.vehicle_id" class="mt-1 text-sm text-red-600">{{ form.errors.vehicle_id }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Issue Description</label>
                                <textarea v-model="form.issue_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <div v-if="form.errors.issue_description" class="mt-1 text-sm text-red-600">{{ form.errors.issue_description }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority</label>
                                <select v-model="form.priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            </div>
                            <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 disabled:opacity-50">
                                {{ form.processing ? 'Submitting...' : 'Submit Ticket' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Tickets</h3>
                        <div class="mt-4 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Vehicle</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Priority</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Description</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Reported</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="ticket in tickets.data" :key="ticket.id">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ ticket.vehicle.vehicle_code }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                <span :class="[priorityBadge(ticket.priority), 'inline-flex rounded-full px-2 text-xs font-semibold leading-5']">{{ ticket.priority }}</span>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                <span :class="[statusBadge(ticket.status), 'inline-flex rounded-full px-2 text-xs font-semibold leading-5']">{{ ticket.status }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ ticket.issue_description }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ new Date(ticket.reported_at).toLocaleString() }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                                <Link v-if="ticket.status !== 'Resolved'" :href="route('maintenance.update', ticket.id)" method="patch" :data="{ status: 'Resolved' }" as="button" class="text-green-600 hover:text-green-900">Resolve</Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <Link v-if="tickets.prev_page_url" :href="tickets.prev_page_url" class="rounded-md bg-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-300">Previous</Link>
                            <div v-else></div>
                            <Link v-if="tickets.next_page_url" :href="tickets.next_page_url" class="rounded-md bg-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-300">Next</Link>
                            <div v-else></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
