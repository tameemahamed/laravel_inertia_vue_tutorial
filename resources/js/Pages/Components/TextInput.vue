<script setup>
const model = defineModel({
    type: null,
    required: true
})

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'text',
    },
    message: String
})
</script>

<style>
small {
    color: red;
}
</style>

<template>
    <div class="mb-6">
        <label class="block mb-2 text-sm font-medium text-gray-900">{{ name }}</label>
        <input v-model="model"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            :class="{'border-red-500' :message}"
            :type="type" />
        <small class="error" v-if="message">{{ message }}</small>
    </div>
</template>