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
                    <Link :href="route('home')">Home</Link>
                    <Link :href="route('about')">About</Link>
                    <Link :href="route('register')">Register</Link>
                </div>
            </nav>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>