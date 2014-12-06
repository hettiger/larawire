## LaraWire

This package is intended to merge Laravel with ProcessWire to get the best of both worlds!

__Be aware: This package is work in progress!__

----

### Installation

The installation instructions might seem a bit unfamiliar to you but are required as there is no composer package of processwire. Also the releases on GitHub are off so I've forked the original package maintaining the releases myself. I hope you understand that I refuse to put a fork on packagist.

Open your `composer.json` file and add the following block right above the require section:

```json
// composer.json

...

"repositories": [
    {
        "type": "package",
        "package": {
            "name": "ryancramerdesign/processwire",
            "version": "2.5.3",
            "dist": {
                "url": "https://github.com/hettiger/ProcessWire/archive/2.5.3.zip",
                "type": "zip"
            }
        }
    }
],
"require": {

...
```

Now append the require section with following packages:

```json
// composer.json

...

"require": {

    ...

    "ryancramerdesign/processwire": "2.5.*",
    "hettiger/larawire": "0.*"
},

...
```

Run a composer update in your terminal:

```bash
composer update -o
```

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

```php
// app/config/app.php

...

'providers' => array(

    ...

    'Hettiger\Larawire\LarawireServiceProvider',

),

...
```

You can now install larawire, to merge Laravel with ProcessWire using your terminal:

```bash
php artisan larawire:install
```

Follow the instructions prompted by your terminal.

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
