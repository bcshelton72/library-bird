<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputText from 'primevue/inputtext';
import { useForm } from '@inertiajs/vue3';
import Select from 'primevue/select';

defineProps({
    roles: {
        type: Object,
    }
});

const form = useForm({
    preview: null,
    profile_picture: '',
    name: '',
    email: '',
    role: '',
    password: '',
    password_confirmation: '',
});

const change = (e) => {
    form.profile_picture = e.target.files[0];
    form.preview = URL.createObjectURL(e.target.files[0]);
}

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div class="grid place-items-center">
                <div class="relative w-28  h-28 rounded-full overflow-hidden border border-slate-300">
                    <label for="profile_picture" class="absolute inset-0 grid content-end cursor-pointer">
                        <span class="bg-white/70 pb-2 text-center">Profile Pic</span>
                    </label>
                    <input type="file" id="profile_picture" @input="change" hidden />
                    <img class="object-cover w-28 h-28" :src="form.preview ?? '/storage/profile_pictures/default.jpg'" />
                </div>

                <InputError class="mt-2" :message="form.errors.profile_picture" />
            </div>

            <div class="mt-4">
                <InputLabel for="name" value="Name" />

                <InputText
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <InputText
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="role" value="Role" />

                <Select
                    inputId="role"
                    v-model="form.role"
                    :options="roles"
                    optionLabel="name"
                    optionValue="name"
                    placeholder="Select a Role"
                    class="mt-1 block w-full md:w-56 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />

                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <InputText
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <InputText
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
