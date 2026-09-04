<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Home" />

    <div class="min-h-screen bg-slate-950 text-white">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 via-slate-950 to-slate-950"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(245,158,11,0.15),transparent_40%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_80%,rgba(251,191,36,0.1),transparent_40%)]"></div>

            <nav class="relative mx-auto flex max-w-7xl items-center justify-between px-6 py-6">
                <div class="flex items-center gap-3">
                    <svg class="h-9 w-9 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M8 7l4-4 4 4M6 12h12M8 17l4 4 4-4" />
                    </svg>
                    <span class="text-xl font-bold tracking-tight text-white">MineOps</span>
                </div>

                <div v-if="canLogin" class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-slate-300 transition hover:text-white"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-slate-300 transition hover:text-white"
                        >
                            Sign in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-amber-400"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>

            <main class="relative mx-auto max-w-7xl px-6 py-24 sm:py-32">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-400">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-amber-500"></span>
                            </span>
                            Mining Operations Platform
                        </div>

                        <h1 class="mt-6 text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
                            <span class="block text-white">Command Your</span>
                            <span class="block bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Mining Fleet</span>
                        </h1>

                        <p class="mt-6 text-base leading-7 text-slate-400 sm:text-lg">
                            Real-time telemetry, dispatch control, and maintenance intelligence for heavy vehicle operations. Built for scale, designed for clarity.
                        </p>

                        <div class="mt-10 flex flex-wrap items-center gap-4">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-amber-500 px-6 py-3 text-base font-semibold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400"
                            >
                                Start Free Trial
                            </Link>

                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="rounded-lg border border-slate-700 bg-slate-900/50 px-6 py-3 text-base font-semibold text-white backdrop-blur-sm transition hover:border-slate-600 hover:bg-slate-900"
                            >
                                Sign In
                            </Link>
                        </div>

                        <div class="mt-10 flex items-center gap-8 text-sm text-slate-500">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Real-time Tracking
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Fleet Analytics
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Maintenance AI
                            </div>
                        </div>
                    </div>

                    <div class="relative hidden lg:block">
                        <div class="relative mx-auto w-full max-w-lg">
                            <div class="absolute -inset-4 rounded-2xl bg-gradient-to-r from-amber-500/20 to-orange-500/20 blur-2xl"></div>
                            <div class="relative rounded-2xl border border-slate-800 bg-slate-900/80 p-6 backdrop-blur-xl">
                                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                                    <div>
                                        <p class="text-sm font-medium text-slate-400">Fleet Status</p>
                                        <p class="text-2xl font-bold text-white">12 Vehicles</p>
                                    </div>
                                    <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-400">8 Active</span>
                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                                        <p class="text-xs text-slate-500">Avg Fuel</p>
                                        <p class="mt-1 text-lg font-semibold text-amber-400">2,312 L</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                                        <p class="text-xs text-slate-500">Open Alerts</p>
                                        <p class="mt-1 text-lg font-semibold text-rose-400">0 Critical</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-3 rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500/10">
                                        <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">Live Telemetry</p>
                                        <p class="text-xs text-slate-500">Updated just now</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="relative border-t border-slate-800/60">
                <div class="mx-auto max-w-7xl px-6 py-8">
                    <p class="text-center text-sm text-slate-500">
                        MineOps Platform. Built for heavy vehicle operations.
                    </p>
                </div>
            </footer>
        </div>
    </div>
</template>
