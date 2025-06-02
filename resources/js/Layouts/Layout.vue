<script setup>
</script>

<template>
    <Head>
        <title>My App</title>
        <meta head-key="description" 
              name="description" 
              content="This is Layout meta tag"
        >
    </Head>
    <div>
        <header class="bg-indigo-500 text-white">
            <nav class="flex items-left justify-between p-4 max-w-screen-lg
                mx-auto">
                <div class="space-x-6">
                    <Link href="/">Home</Link>
                    <Link href="/about">About</Link>
                </div>
            </nav>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>