<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-slate-950 pt-6 sm:justify-center sm:pt-0">
        <div class="relative">
            <div class="absolute -inset-4 rounded-2xl bg-amber-500/10 blur-xl"></div>
            <Link href="/" class="relative block">
                <ApplicationLogo class="h-20 w-20 fill-current text-amber-500" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/80 px-6 py-6 shadow-2xl backdrop-blur-xl sm:max-w-md sm:px-8"
        >
            <slot />
        </div>
    </div>
</template>
