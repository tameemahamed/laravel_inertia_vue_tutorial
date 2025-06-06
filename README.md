# Laravel with InertiaJs and VueJs

## Project Setup
```bash
composer create-project laravel/laravel laravel_inertia_vue_tutorial
cd laravel_inertia_vue_tutorial
```
### Install vue
```bash
npm i vue@latest
```

## Inertia Server side setup

### Install dependencies
```bash
composer require inertiajs/inertia-laravel
php artisan make:view app
```

### Root template <br>
Now in `resources/views/app.blade.php` setup the root template.
```php
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @vite('resources/js/app.js')
    @inertiaHead
  </head>
  <body>
    @inertia
  </body>
</html>
```
By default, Inertia's Laravel adapter will assume your root template is named app.blade.php. If you would like to use a different root view, you can change it using the Inertia::setRootView() method.

### Middleware <br>
```bash
php artisan inertia:middleware
```
Once the middleware has been published, append the HandleInertiaRequests middleware to the web middleware group in your application's bootstrap/app.php file.

```php
use App\Http\Middleware\HandleInertiaRequests;

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        HandleInertiaRequests::class,
    ]);
})
```

### Creating Responses (Ignore it)
```php
use Inertia\Inertia;

class EventsController extends Controller
{
    public function show(Event $event)
    {
        return Inertia::render('Event/Show', [
            'event' => $event->only(
                'id',
                'title',
                'start_date',
                'description'
            ),
        ]);
    }
}
```

## Inertia Client Side Setup 

### Install dependencies 
```bash
npm install @inertiajs/vue3
```

### Initialize the Inertia app 

in resources/js/app.js
```js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})
```

### Make a page
```bash
npm install -D @vitejs/plugin-vue
```
In `vite.config.js`
```js
import vue from '@vitejs/plugin-vue';
        vue() // in the plugin array
```
Create file `resources/js/Pages/Home.vue`
```php
<template>
    <div>
        <h1>Hello</h1>
    </div>
</template>
```
Now in `routes/web.php`
```php
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});
```
```bash
npm run build
php artisan serve
```

### Install Tailwind
```bash
npm install tailwindcss @tailwindcss/vite
```
in `vite.config.js`
```js
import tailwindcss from '@tailwindcss/vite';
        tailwindcss(),
```
in `resources/css/app.css`
```css
@source '../views';
```
in app.js
```js
import '../css/app.css';
```

## Pages

### Creating pages
Inertia pages are simply JavaScript components. Pages receive data from our application's controllers as props.

create `resources/js/Pages/About.vue`
```vue
<script setup>
defineProps({
    user: String // recieve data
})
</script>

<template>
    <div>
        <h1>About {{ user }}</h1>
    </div>
</template>
```

### Routes
We can render that page by returning an inertia response in routes/web.php. We can use any of the three following methods.
```php
// Method 1
Route::get('/about', function() {
    return Inertia::render('About', [
        'user' => 'Tameem' // props
    ]);
});

// Method 2
Route::get('/about', function () {
    return inertia('About', [
        'user' => 'Tameem' // props
    ]);
});

// Method 3
Route::inertia('/about', 'About', [
    'user' => 'Tameem' // props
]);
```

## Layouts
create `resource/js/Layouts/Layout.vue`
```vue
<template>
    <div>
        <header class="bg-indigo-500 text-white">
            <nav class="flex items-center justify-between p-4 max-w-screen-lg mx-auto">
                <div>
                    <a href="/">Home</a>
                    <a href="/about">About</a>
                </div>
            </nav>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>
```

Now add that Layout in Home and About page

```vue
<script setup>
import Layout from '../Layouts/Layout.vue';
</script>

<template>
    <Layout>
        <h1>In home page</h1>
    </Layout>
</template>
```
```vue 
<script setup>
import Layout from '../Layouts/Layout.vue'
defineProps({
    user: String // get the props
})
</script>

<template>
    <Layout>
        <h1>About {{ user }}</h1>
    </Layout>
</template>
```

But everytime importing that 'Layout' doesn't seems so efficient. So for Efficiency go to `app.js` and add the followings

```js
import Layout from './Layouts/Layout.vue';


  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || Layout
    return page
  }
```
make changes in `Home.vue` and `About.vue`

```vue
<script setup>
// just simple get rid of that import Layout everytime
</script>

<template>
        <h1>Hello</h1>
</template>
```
```vue 
<script setup>
import GreenNav from '../Layouts/GreenNav.vue';

defineProps({
    user: String // get the props
})

// in case you want to use some other Navigation bar
defineOptions({ layout: GreenNav })

</script>

<template>
        <h1>About {{ user }}</h1>
</template>
```

## Link Element
To create links to other pages within an Inertia app, you will typically use the Inertia `<Link>` component. This component is a light wrapper around a standard anchor `<a>` link that intercepts click events and prevents full page reloads. 
in `Layout.vue`
```vue
<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<!-- rest of the code -->
                    <Link href="/">Home</Link>
                    <Link href="/about">About</Link>
<!-- rest of the code -->
```
## Head Elements
Since Inertia powered JavaScript apps are rendered within the document `<body>`, they are unable to render markup to the document `<head>`, as it's outside of their scope. To help with this, Inertia ships with a `<Head>` component which can be used to set the page `<title>`, `<meta>` tags, and other `<head>` elements.

### Title Tag
in `Layout.vue`
```vue
<script setup>
import { Link,Head } from '@inertiajs/vue3';
</script>
<!-- rest of the code -->
    <Head>
        <title>My App</title>
    </Head>
<!-- rest of the code -->
```
Now we can see `My App` in the title of every pages.
Also we can use a title shorthand like the following
```vue
<Head title="My App" />
```
- Title callback
We can globally modify the page `<title>` using the title callback in the createInertiaApp setup method. Typically, this method is invoked in our application's main JavaScript file. A common use case for the title callback is automatically adding an app name before or after each page title.

in app.js add the following
```js
  title: (title) => `My App ${title}`,
```
in `About.vue`
```vue
<script setup>
import { Head } from '@inertiajs/vue3';
defineProps({
    user: String // get the props
})
</script>

<template>
    <Head title=" - About"/>
    
    <h1>About {{ user }}</h1>
</template>
```
now it will show `My App - About` in the page title upon rendering that page.

### Meta Tag
in `Layout.vue`
```vue
<!-- rest of the code -->
    <Head>
        <title>My App</title>
        <meta name="description" 
              content="This is Layout meta tag"
        >
    </Head>
<!-- rest of the code -->
```
Also in `Home.vue`
```vue
<!-- rest of the code -->
        <Head>
                <title>Home</title>
                <meta name="description" 
                        content="This is home page meta tag"
                >
        </Head>
<!-- rest of the code -->
```
now upon inspect elements in `Homepage` we will see two meta tags.

To show only the homepage meta tag add `head-key="description"` inside the meta tag in both 'Layout.vue' and `Home.vue`

To get rid of the `Head` and `Link` import in every pages, we can declear it globally
in `app.js`
```js
import { Head, Link } from '@inertiajs/vue3'

// rest of the code
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('Link', Link)
      .component('Head', Head)
      .mount(el)
  },
// rest of the code
```

## Progress indicators
Add some delay for the Home page

in `web.php`
```php
Route::get('/', function () {
    sleep(2); // will make a 2 seconds delay
    return Inertia::render('Home');
});
```
copy and paste this in `app.js`
```js
createInertiaApp({
  progress: {
    // The delay after which the progress bar will appear, in milliseconds...
    delay: 250, // you may remove this

    // The color of the progress bar...
    color: '#29d',

    // Whether to include the default NProgress styles...
    includeCSS: true,

    // Whether the NProgress spinner will be shown...
    showSpinner: false, // after changing it to true, we can see a loading type thing
  },
  // ...
})
```

## Shared data
Sometimes you need to access specific pieces of data on numerous pages within your application. For example, you may need to display the current user in the site header. Passing this data manually in each response across your entire application is cumbersome. Thankfully, there is a better option: shared data.

### Sharing data
Inertia's server-side adapters all provide a method of making shared data available for every request. This is typically done outside of your controllers. Shared data will be automatically merged with the page props provided in your controller.

In Laravel applications, this is typically handled by the `HandleInertiaRequests` middleware that is automatically installed when installing the server-side adapter.

in `app/http/middleware/HandleInertiaRequests.php`
```php
class HandleInertiaRequests extends Middleware
{
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            // Synchronously...
            'appName' => config('app.name'),

            // Lazily...
            'auth.user' => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'email')
                : null,
        ]);

        // or use the following
        return [
            ...parent::share($request),
            'user' => 'Tameem'
            
            //
        ];
    }
}
```
Alternatively, you can manually share data using the `Inertia::share` method.
```php
use Inertia\Inertia;

// Synchronously...
Inertia::share('appName', config('app.name'));

// Lazily...
Inertia::share('user', fn (Request $request) => $request->user()
    ? $request->user()->only('id', 'name', 'email')
    : null
);
```

### Accessing shared data
Once you have shared the data server-side, you will be able to access it within any of your pages or components. Here's an example of how to access shared data in a layout component.

```vue
<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const user = computed(() => page.props.auth.user)
</script>

<template>
  <main>
    <header>
      You are logged in as: {{ user.name }}
    </header>
    <article>
      <slot />
    </article>
  </main>
</template>
```

## Named Route
Ziggy provides a JavaScript `route()` function that works like Laravel's, making it a breeze to use your named Laravel routes in JavaScript.
### Installation
Install Ziggy in your Laravel app with Composer:
```bash
composer require tightenco/ziggy
```

### Setup
import it in `app.js`
```js
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(ZiggyVue) // add it
  },
```
in `app.blade.php` add `@routes` inside the `head` element

### Usage
Now we need to name our route in `web.php`
```php
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::inertia('/about', 'About', [
    'user' => 'Tameem' 
])->name('about');
```
Now in `Layout.vue` edit these
```vue
        <div class="space-x-6">
            <Link :href="route('home')">Home</Link>
            <Link :href="route('about')">About</Link>
        </div>
```

## Scroll Management

add these in `Home.vue`
```vue
        <Link class="mt-[1400px] block" href="/">
                Refresh
        </Link>
```
Now you can see a `Refresh` button in the bottom of the page. Upon clicking this it will take you back to the top.

to resolve this, just simply add `preserve-scroll` in `Link` element.
```vue
        <Link class="mt-[1400px] block" href="/" preserve-scroll>
            Refresh
        </Link>
```

## Forms
Create a registration form in `resources/js/Pages/Register.vue`
```vue
<script setup>
import { reactive } from 'vue';

const form = reactive({
    name: null,
    email: null,
    password: null,
    password_confirmation: null
})

const submit = () => {
    console.log(form)
}
</script>

<template>
    <Head title="| Register" />
    <h1 class="title">Register a new account</h1>

    <div class="w-2/4 mx-auto">
        <form @submit.prevent="submit">
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                <input v-model="form.name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" type="text" />
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input v-model="form.email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" type="text" />
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input v-model="form.password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" type="password" />
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
```
In `Layout.vue` add these also
```vue
                    <Link :href="route('register')">Register</Link>
```
In `web.php` add that route
```php
Route::inertia('/register', 'Auth/Register')
->name('register');
```

## Inertia form helper
```bash
php artisan make:controller AuthController
```

paste it in `app/http/controllers/AuthController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        // Validate
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed']
        ]);

        // Register
        $user = User::create($fields);

        // Login 
        Auth::login($user);

        // Redirect 
        return redirect()->route('home');

    }
}
```

In `web.php`
```php
Route::post('/register', [AuthController::class, 'register']);
```

### use `router` method

In `Register.vue`
```vue
<script setup>
import { router } from '@inertiajs/vue3';
const submit = () => {
    router.post('/register', form)
}
</script>

<!-- also we can add these to show error message -->
                <small>{{ form.errors.name }}</small>

```

### use form helper method

in `Register.vue`

```vue
<script setup>
import { useForm }from '@inertiajs/vue3';

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null
})

const submit = () => {
    form.post('/register', {
        onError: () => {
            form.reset('password', 'password_confirmation')
        }
    })
}
</script>
```

the code are pretty self-explanatory.

## Text input component

Create a component in `resources/js/Pages/Components/TextInput.vue`

```vue
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
```

now update `Register.vue`
```vue
<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { useForm }from '@inertiajs/vue3';
import TextInput from '../Components/TextInput.vue';

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

<template>

    <Head title="| Register" />
    <h1 class="title">Register a new account</h1>

    <div class="w-2/4 mx-auto">
        <form @submit.prevent="submit">
            <TextInput 
                name="name" 
                v-model="form.name" 
                :message="form.errors.name" 
            />

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

            <TextInput 
                name="confirm password" 
                type="password"
                v-model="form.password_confirmation" 
            />
            <div class="mt-6 flex items-center justify-between">
                <p class="text-slate-600">
                    Already a user?
                    <a href="#" class="text-blue-600 hover:text-blue-800 hover:underline ml-1">
                        Login
                    </a>
                </p>
                <button
                    class="primary-btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Register
                </button>
            </div>
        </form>
    </div>
</template>
```


