## LaraWire

This package is intended to merge Laravel with ProcessWire to get the best of both worlds!

__Be aware: This package is work in progress!__

----

### Installation

Require this package with composer using the following command:

```bash
composer require hettiger/larawire
```

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

```php
'Hettiger\Larawire\LarawireServiceProvider',
```

You can now install larawire, to merge Laravel with ProcessWire:

```bash
php artisan larawire:install
```

Follow the instructions by your terminal.

### What's next?

You are able to require `public/laravel.php` in your template files now. Just think a second about it. This gives you a lot of power ...

#### Example 1

1. Create a template file for your 404-Error-Page
2. Require `public/laravel.php` in your new template file
3. Use Laravel to take care of 404-Error's
4. Feel free to use Laravel the way you're used to
5. Be aware that ProcessWire URL's can "overwrite" Laravel's routes
6. Enjoy full access to ProcessWire's API within Laravel

#### Example 2

1. Create a "Laravel" template file
2. Require `public/laravel.php` in your new template file
3. Use your new "Laravel" template file for each template that should be "Laravel powered"
4. Generate routes in Laravel using ProcessWire Selectors (template=name)
5. Feel free to use Laravel the way you're used to
6. Enjoy full access to ProcessWire's API within Laravel

#### Example 3

1. Mix `Example 1` and `Example 2`
2. Go crazy :-)

### Future

* Optimized ProcessWire site
* Supporting services for route generation
* Proper Apache and nginx configuration files
* ...

Pull Requests / any form of help are very welcome.
