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

const features = [
    {
        title: 'Real-Time Telemetry',
        description: 'Monitor fuel consumption, engine hours, location, and load weight as events stream in from the field.',
        icon: 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z',
    },
    {
        title: 'Dispatch Control',
        description: 'Assign shifts, track active routes, and ensure every haul is matched to the right operator and vehicle.',
        icon: 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 1.657-4.03 3-9 3s-9-1.343-9-3',
    },
    {
        title: 'Maintenance Intelligence',
        description: 'Predict failures before they happen with service interval tracking, alerting, and maintenance queue management.',
        icon: 'M11.42 15.17 17.25 21A2.25 2.25 0 0021 17.25l-5.877-5.877M11.42 15.17l-3.248-3.248a1.125 1.125 0 010-1.591l.53-.53a1.125 1.125 0 011.59 0l2.586 2.586M11.42 15.17l2.586 2.586m0 0L15.59 17.25m-2.586-2.586L8.832 11.415m5.878 5.878 3.248 3.248',
    },
    {
        title: 'Fleet Analytics',
        description: 'Visualize utilization, downtime, and cost-per-ton with built-in charts and exportable reporting.',
        icon: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 013 15.375v-2.25zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125v-8.25zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V15m-9-1.125h.008v.008H7.5v-.008zm0 3.75h.008v.008H7.5v-.008zm0 3.75h.008v.008H7.5v-.008z',
    },
    {
        title: 'Booking & Approvals',
        description: 'Streamline vehicle reservations and multi-step approval workflows with role-based access control.',
        icon: 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
    {
        title: 'Operator Management',
        description: 'Track certifications, shift history, and performance metrics for every operator on site.',
        icon: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-3.512-7.671c-.57.22-1.197.344-1.852.372a48.56 48.56 0 00-6.182-1.029C9.086 10.186 8 11.65 8 13.5V21h12v-6.872zM12 13.5a3 3 0 100-6 3 3 0 000 6z',
    },
];

const pricingPlans = [
    {
        name: 'Starter',
        price: '$499',
        period: '/month',
        description: 'For small teams getting started with digital fleet operations.',
        features: ['Up to 10 vehicles', 'Real-time telemetry', 'Basic maintenance alerts', 'Email support', '1 operator seat'],
        cta: 'Start Trial',
        highlighted: false,
    },
    {
        name: 'Professional',
        price: '$1,199',
        period: '/month',
        description: 'For growing operations that need dispatch and analytics.',
        features: ['Up to 50 vehicles', 'Dispatch control', 'Fleet analytics dashboard', 'Booking & approvals', 'Priority support', '5 operator seats'],
        cta: 'Start Trial',
        highlighted: true,
    },
    {
        name: 'Enterprise',
        price: 'Custom',
        period: '',
        description: 'For large-scale mining operations with advanced requirements.',
        features: ['Unlimited vehicles', 'Custom integrations', 'Advanced analytics & exports', 'SSO & role management', 'Dedicated success manager', 'Unlimited seats'],
        cta: 'Contact Sales',
        highlighted: false,
    },
];

const faqs = [
    {
        question: 'How quickly can we get started?',
        answer: 'Most teams are fully deployed within 2-4 weeks. We handle data ingestion, user provisioning, and integration with your existing systems.',
    },
    {
        question: 'Do you support offline mode for remote sites?',
        answer: 'Yes. The edge agent buffers telemetry locally and syncs automatically when connectivity is restored, ensuring no data loss.',
    },
    {
        question: 'Can we integrate with our existing ERP or accounting tools?',
        answer: 'Professional and Enterprise plans include webhooks, REST APIs, and pre-built connectors for common mining ERP systems.',
    },
    {
        question: 'Is there a free trial available?',
        answer: 'Yes. All plans include a 14-day free trial with full feature access. No credit card is required to start.',
    },
    {
        question: 'What kind of support do you offer?',
        answer: 'Starter includes email support, Professional includes priority chat support, and Enterprise includes a dedicated success manager and 24/7 phone support.',
    },
];
</script>

<template>
    <Head title="MineOps - Mining Fleet Platform" />

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

            <main class="relative mx-auto max-w-7xl px-6">
                <section class="py-20 sm:py-32">
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
                </section>

                <section class="py-20">
                    <div class="mx-auto max-w-7xl px-6">
                        <div class="mx-auto max-w-2xl text-center">
                            <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Platform capabilities</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">Everything your site needs</h2>
                            <p class="mt-4 text-base text-slate-400">From the bench to the boardroom, MineOps gives you visibility and control over every haul, service event, and operator shift.</p>
                        </div>

                        <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            <div v-for="feature in features" :key="feature.title" class="group relative rounded-2xl border border-slate-800 bg-slate-900/50 p-6 transition hover:border-amber-500/30">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400 transition group-hover:bg-amber-500/20">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="feature.icon" />
                                    </svg>
                                </div>
                                <h3 class="mt-5 text-lg font-semibold text-white">{{ feature.title }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-400">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="py-20">
                    <div class="mx-auto max-w-7xl px-6">
                        <div class="mx-auto max-w-2xl text-center">
                            <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Pricing</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">Plans that scale with your operation</h2>
                            <p class="mt-4 text-base text-slate-400">Start small and expand as your fleet grows. All plans include a 14-day free trial.</p>
                        </div>

                        <div class="mt-16 grid gap-8 lg:grid-cols-3">
                            <div v-for="plan in pricingPlans" :key="plan.name" :class="['flex flex-col rounded-2xl border bg-slate-900/50 p-8', plan.highlighted ? 'border-amber-500/40 shadow-[0_0_40px_rgba(245,158,11,0.15)]' : 'border-slate-800']">
                                <div class="flex items-baseline justify-between">
                                    <h3 class="text-lg font-semibold text-white">{{ plan.name }}</h3>
                                    <span v-if="plan.highlighted" class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-400">Popular</span>
                                </div>
                                <p class="mt-4 flex items-baseline gap-1">
                                    <span class="text-4xl font-bold tracking-tight text-white">{{ plan.price }}</span>
                                    <span class="text-sm text-slate-400">{{ plan.period }}</span>
                                </p>
                                <p class="mt-2 text-sm text-slate-400">{{ plan.description }}</p>
                                <ul class="mt-6 space-y-3 text-sm text-slate-300">
                                    <li v-for="item in plan.features" :key="item" class="flex gap-3">
                                        <svg class="h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ item }}
                                    </li>
                                </ul>
                                <Link
                                    :href="canRegister ? route('register') : route('login')"
                                    :class="['mt-8 w-full rounded-lg px-4 py-2.5 text-center text-sm font-semibold transition', plan.highlighted ? 'bg-amber-500 text-slate-950 hover:bg-amber-400' : 'border border-slate-700 text-white hover:border-slate-600 hover:bg-slate-900']"
                                >
                                    {{ plan.cta }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="py-20">
                    <div class="mx-auto max-w-3xl px-6">
                        <div class="text-center">
                            <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">FAQ</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">Common questions</h2>
                        </div>

                        <div class="mt-12 divide-y divide-slate-800 rounded-2xl border border-slate-800 bg-slate-900/50">
                            <div v-for="item in faqs" :key="item.question" class="px-6 py-5">
                                <h3 class="text-base font-semibold text-white">{{ item.question }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-400">{{ item.answer }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="py-20">
                    <div class="mx-auto max-w-5xl rounded-3xl border border-amber-500/30 bg-gradient-to-br from-amber-500/10 via-slate-900/50 to-slate-950 px-6 py-16 text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Ready to modernize your fleet?</h2>
                        <p class="mt-4 text-base text-slate-400">Start your 14-day free trial today. No credit card required.</p>
                        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-amber-500 px-6 py-3 text-base font-semibold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400"
                            >
                                Get Started
                            </Link>
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="rounded-lg border border-slate-700 bg-slate-900/50 px-6 py-3 text-base font-semibold text-white backdrop-blur-sm transition hover:border-slate-600 hover:bg-slate-900"
                            >
                                Sign In
                            </Link>
                        </div>
                    </div>
                </section>

                <footer class="border-t border-slate-800/60">
                    <div class="mx-auto max-w-7xl px-6 py-12">
                        <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                            <div class="flex items-center gap-3">
                                <svg class="h-8 w-8 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M8 7l4-4 4 4M6 12h12M8 17l4 4 4-4" />
                                </svg>
                                <span class="text-lg font-bold tracking-tight text-white">MineOps</span>
                            </div>
                            <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-400">
                                <span>Features</span>
                                <span>Pricing</span>
                                <span>Documentation</span>
                                <span>Support</span>
                            </div>
                            <p class="text-sm text-slate-500">MineOps Platform. Built for heavy vehicle operations.</p>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>
</template>
