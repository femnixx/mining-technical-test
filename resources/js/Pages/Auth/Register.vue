<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create account" />

        <div class="w-full">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-white">Create your account</h2>
                <p class="mt-2 text-sm text-slate-400">Join MineOps and start managing your fleet</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <InputLabel for="name" value="Full name" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-900/80 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-amber-500"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Email address" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-900/80 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-amber-500"
                        v-model="form.email"
                        required
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
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm password"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-900/80 text-white placeholder-slate-500 focus:border-amber-500 focus:ring-amber-500"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div>
                    <InputLabel for="role" value="Register as" />
                    <select
                        id="role"
                        v-model="form.role"
                        class="mt-1 block w-full rounded-lg border-slate-700 bg-slate-900/80 text-white focus:border-amber-500 focus:ring-amber-500"
                    >
                        <option value="admin">Admin (Pool Manager)</option>
                        <option value="approver">Approver (Manager)</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.role" />
                </div>

                <div class="flex items-center justify-end gap-2 text-sm text-slate-400">
                    <span>Already registered?</span>
                    <Link
                        :href="route('login')"
                        class="font-medium text-amber-400 transition hover:text-amber-300"
                    >
                        Sign in
                    </Link>
                </div>

                <PrimaryButton
                    class="w-full justify-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Create account
                </PrimaryButton>
            </form>
        </div>
    </GuestLayout>
</template>
