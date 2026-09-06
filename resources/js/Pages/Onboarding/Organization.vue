<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';

defineOptions({ layout: GuestLayout });

const form = useForm({
    name: '',
    industry: '',
    timezone: 'UTC',
    location_name: '',
});

function submit() {
    form.post(route('onboarding.organization.store'), {
        onFinish: () => {},
    });
}

const industries = ['Mining', 'Construction', 'Logistics', 'Other'];
const timezones = [
    'UTC',
    'Pacific/Auckland',
    'Australia/Sydney',
    'Asia/Shanghai',
    'Asia/Tokyo',
    'Asia/Singapore',
    'Asia/Kolkata',
    'Europe/London',
    'Europe/Paris',
    'America/New_York',
    'America/Los_Angeles',
];
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Organization Setup</h1>
                <p class="mt-1 text-sm text-slate-400">Step 1 of 3 - Set up your workspace</p>
            </div>
            <ThemeToggle />
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="name" value="Organization Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                    required
                    autofocus
                />
                <InputError :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="industry" value="Industry / Sector" />
                <select
                    id="industry"
                    v-model="form.industry"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                >
                    <option value="">Select industry</option>
                    <option v-for="ind in industries" :key="ind" :value="ind">{{ ind }}</option>
                </select>
                <InputError :message="form.errors.industry" />
            </div>

            <div>
                <InputLabel for="timezone" value="Timezone" />
                <select
                    id="timezone"
                    v-model="form.timezone"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                >
                    <option v-for="tz in timezones" :key="tz" :value="tz">{{ tz }}</option>
                </select>
                <InputError :message="form.errors.timezone" />
            </div>

            <div>
                <InputLabel for="location_name" value="Primary Location Name (optional)" />
                <TextInput
                    id="location_name"
                    v-model="form.location_name"
                    type="text"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                />
                <InputError :message="form.errors.location_name" />
            </div>

            <div class="flex items-center justify-end">
                <PrimaryButton :disabled="form.processing">
                    Continue
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
