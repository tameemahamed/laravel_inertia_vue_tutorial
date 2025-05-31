# Laravel with InertiaJs and VueJs

## Project Setup
```bash
composer create-project laravel/laravel laravel_inertia_vue_tutorial
cd laravel_inertia_vue_tutorial
```
- Install vue
```bash
npm i vue@latest
```

### Inertia Server side setup

- Install dependencies
```bash
composer require inertiajs/inertia-laravel
php artisan make:view app
```

- Root template <br>
Now in resources/views/app.blade.php setup the root template.
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

- Middleware <br>
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

- Creating Responses (Ignore it)
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

### Inertia Client Side Setup 

- Install dependencies <br>
```bash
npm install @inertiajs/vue3
```

- Initialize the Inertia app <br>

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

