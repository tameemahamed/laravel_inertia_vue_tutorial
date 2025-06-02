<script setup>
defineProps({
    user: String // get the props
})
</script>

<template>
    <Head title=" - About"/>
    
    <h1>About {{ user }}</h1>
</template>