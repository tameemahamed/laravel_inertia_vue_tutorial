<script setup>
</script>

<template>
        <Head :title="`| ${$page.component}`" />
        <h1>Hello {{ $page.props.user }}</h1>
        <h1>component name is {{ $page.component }}</h1>
        <Link class="mt-[1400px] block" href="/" preserve-scroll>
                Refresh
        </Link>
</template>