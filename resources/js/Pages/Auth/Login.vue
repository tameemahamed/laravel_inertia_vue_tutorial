<script setup>
import { useForm }from '@inertiajs/vue3';
import TextInput from '../Components/TextInput.vue';

const form = useForm({
    email: null,
    password: null,
    remember:null
})

const submit = () => {
    // router.post('/register', form)
    form.post('/login', {
        onError: () => {
            form.reset('password')
        }
    })
}
</script>

<template>
    <Head title="| Login" />
    <h1 class="title">Login to your account</h1>
    <div class="w-2/4 mx-auto">
        <form @submit.prevent="submit">
            <TextInput 
                name="email" 
                type="email" 
                v-model="form.email" 
                :message="form.errors.email" 
            />

            <TextInput 
                name="password" 
                type="password" 
                v-model="form.password" 
                :message="form.errors.password" 
            />
            
            <div class="mt-6 flex items-center justify-between">
                <p class="text-slate-600">
                    Do not have an account?
                    <Link :href="route('register')" class="text-blue-600 hover:text-blue-800 hover:underline ml-1">
                        Register
                    </Link>
                </p>
                <p>
                    <label>Remember me</label>
                    <input type="checkbox" v-model="form.remember">
                </p>
                <button
                    class="primary-btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Login
                </button>
            </div>
        </form>
    </div>
</template>