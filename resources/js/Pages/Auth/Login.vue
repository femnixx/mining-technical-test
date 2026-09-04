<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign in" />

        <div class="w-full">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-white">Welcome back</h2>
                <p class="mt-2 text-sm text-slate-400">Sign in to your MineOps account</p>
            </div>

            <div v-if="status" class="mb-4 rounded-lg border border-amber-500/30 bg-amber-500/10 p-3 text-sm font-medium text-amber-400">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <InputLabel for="email" value="Email address" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-900/80 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-amber-500"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@company.com"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-900/80 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-amber-500"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="text-sm text-slate-400">Remember me</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-amber-400 transition hover:text-amber-300"
                    >
                        Forgot password?
                    </Link>
                </div>

                <PrimaryButton
                    class="w-full justify-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Sign in
                </PrimaryButton>

                <div class="text-center text-sm text-slate-400">
                    Don't have an account?
                    <Link :href="route('register')" class="font-medium text-amber-400 transition hover:text-amber-300">
                        Create one
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
