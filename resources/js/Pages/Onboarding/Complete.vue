<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({ layout: AuthenticatedLayout });
</script>

<template>
    <Head title="Onboarding Complete" />

    <div class="max-w-2xl mx-auto text-center py-12">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full border border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-800">
            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Your workspace is ready</h1>
        <p class="mt-3 text-base text-gray-600 dark:text-gray-400">
            Your organization has been set up with your vehicles and operators. You can now start using MineOps.
        </p>
        <div class="mt-8">
            <Link
                :href="route('dashboard')"
                class="inline-flex items-center rounded-md border border-gray-900 bg-gray-900 px-6 py-3 text-sm font-semibold text-white hover:bg-gray-800"
            >
                Go to Dashboard
            </Link>
        </div>
    </div>
</template>
