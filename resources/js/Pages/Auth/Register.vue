<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { useForm }from '@inertiajs/vue3';

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null
})

const submit = () => {
    // router.post('/register', form)
    form.post('/register', {
        onError: () => {
            form.reset('password', 'password_confirmation')
        }
    })
}
</script>

<style>
small {
    color: red;
}
</style>

<template>
    <Head title="| Register" />
    <h1 class="title">Register a new account</h1>

    <div class="w-2/4 mx-auto">
        <form @submit.prevent="submit">
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                <input v-model="form.name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" type="text" />
                <small>{{ form.errors.name }}</small>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input v-model="form.email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" type="text" />
                <small>{{ form.errors.email }}</small>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input v-model="form.password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" type="password" />
                <small>{{ form.errors.password }}</small>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Confirm password</label>
                <input v-model="form.password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" type="password" />
            </div>

            <div class="mt-6 flex items-center justify-between">
                <p class="text-slate-600">
                    Already a user?
                    <a href="#" class="text-blue-600 hover:text-blue-800 hover:underline ml-1">
                        Login
                    </a>
                </p>
                <button class="primary-btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Register
                </button>
            </div>
        </form>
    </div>
</template>