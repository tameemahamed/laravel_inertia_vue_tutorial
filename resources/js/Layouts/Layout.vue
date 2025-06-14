<script></script>
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
                    <Link 
                        v-if="$page.props.auth.user" 
                        :href="route('dashboard')" 
                        class="nav-Link"
                        :class="{ 'bg-slate-700' :$page.component === 'Dashboard' }"
                        >
                        Dashboard
                    </Link>
                    <Link 
                        :href="route('home')"
                        class="nav-Link"
                        :class="{ 'bg-slate-700' :$page.component === 'Home' }"
                        >
                        Home
                    </Link>
                </div>
                <div v-if="$page.props.auth.user">
                    <Link 
                    :href="route('logout')" 
                    method="post"
                    as="button"
                    type="button"
                    class="nav-link"
                    >
                        Logout
                    </Link>
                </div>
                <div v-else class="space-x-6">
                    <Link 
                        :href="route('register')"
                        class="nav-Link"
                        :class="{ 'bg-slate-700' :$page.component === 'Auth/Register' }"
                        >Register
                    </Link>
                    <Link 
                        :href="route('login')"
                        class="nav-Link"
                        :class="{ 'bg-slate-700' :$page.component === 'Auth/Login' }"
                        >
                        Login
                    </Link>
                </div>
            </nav>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>