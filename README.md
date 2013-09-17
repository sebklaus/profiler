# Profiler

A profiler for Laravel 4. Backend based on sorora/omni, frontend based on loic-sharma/profiler, some features inspired by papajoker/profiler, some features original by myself.

- [Profiler on Packagist](https://packagist.org/packages/juy/profiler)
- [Profiler on GitHub](https://github.com/juy/profiler)

[![](https://dl.dropboxusercontent.com/u/76869590/laravel-package/juy-profiler.png)](https://dl.dropboxusercontent.com/u/76869590/laravel-package/juy-profiler.png "Click for big picture")

## Features

- Environment info
- Current controller/action info
- Routes
- Log events
- SQL query log with syntax highlighting
- Total execution time
    - Custom "checkpoints", see [this section](#custom-timers)
- Total memory usage
- Includes files (I don't think this is really needed)
- All variables passed to views
- Session variables
- Laravel auth variables (Need to test)
- Sentry auth variables


## Installation
To add Profiler to your Laravel application, follow these three steps:

Add the following to your `composer.json` file:

```json
"juy/profiler" : "dev-master"
```

Then, run `composer update` or `composer install` if you have not already installed packages.

Add the below line to the `providers` array in `app/config/app.php` configuration file (add at the end):

```php
'Juy\Profiler\Providers\ProfilerServiceProvider',
```

Add the below line to the `aliases` array in `app/config/app.php` configuration file (add at the end):

```php
'Profiler' => 'Juy\Profiler\Facades\Profiler',
```

## Configuration

You will want to run the following command to publish the config to your application, otherwise it will be overwritten when the package is updated.

```shell
php artisan config:publish juy/profiler
```

### Profiler

Set this option to `FALSE` to disable the profiler. By default, it is `NULL`, which makes the profiler refer to the app debug option in `config/app.php`.

```php
// config.php
'profiler' => NULL
```

If you wish to disable the profiler during your application, just do:

```php
Config::set('profiler::profiler', FALSE);

// or

Profiler::disable();
```

>**Note::** This will only disable the output, it will still do it's background listening but will not output it to the browser.

## Usage

### Custom Timers

To start a timer, all you need to do is:
    
```php
Profiler::start('my timer key');
```

To end the timer, simply call the end function like so:

```php
Profiler::end('my timer key');
```

## Logging

Profiler utilizes Laravel's built in logging system and captures logged events. To log events, you can do (as you would with Laravel) any of these:

```php
Log::debug('Your message here');
Log::info('Your message here');
Log::notice('Your message here');
Log::warning('Your message here');
Log::error('Your message here');
Log::critical('Your message here');
Log::alert('Your message here');
Log::emergency('Your message here');
```

These are color-coded in the Logs part of the profiler – colors may change in future to more accurately reflect the log type.
