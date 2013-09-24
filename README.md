# Continued
This is the continuation of [juy/profiler](https://github.com/juy/profiler).  
It includes Pull Requests [#34](https://github.com/juy/profiler/pull/34) &amp; [#6](https://github.com/juy/profiler/pull/6).

# Profiler

A PHP 5.3 profiler for Laravel 4. Backend based on sorora/omni, fronted based on loic-sharma/profiler, some features inspirated from papajoker/profiler, some feature original by myself.

[![](http://i.imm.io/19tLC.png)](http://i.imm.io/19tLC.png "Click for big picture")

## Features

- Environment info
- Current controller/action info
- Routes
- Log events
- SQL Query Log with syntax highlighting
- Total execution time
    - Custom "checkpoints", see [this section](#custom-timers)
- Total memory usage
- Includes files (I think not realy need this)
- All variables passed to views
- Session variables
- Laravel auth variables (Need test)
- Sentry auth veriables


## Installation
To add Profiler to your Laravel application, add the following to your `composer.json` file:

    "juy/profiler" : "dev-master"

Then run `composer update` or `composer install` if you have not already installed packages. One final step is needed, add the below to the `providers` array in `app/config/app.php` configuration file:

    'Juy\Profiler\Providers\ProfilerServiceProvider',

## Configuration

You will want to run the following command to publish the config to your application, otherwise it will be overwritten in updates.

    php artisan config:publish juy/profiler

### Profiler

Set this option to *false* to disable the profiler. It is `true` by default.

    // Config.php
    'profiler' => true

If you wish to disable the profiler during your application, just do:

    Config::set('profiler::profiler', false);
    
you can order,disable and set label and title

    'btns' => array(
            'environment'=> array('label'=>'','title'=>'Environment'),
            'memory'=>      array('label'=>'','title'=>'Memory'),
            'controller'=>  array('label'=>'CTRL','title'=>'Controller'),
            'routes'=>      array('label'=>'ROUTE'),
            'log'=>         array('label'=>'LOG'),
            'sql'=>         array('label'=>'SQL'),
            'checkpoints'=> array('label'=>'TIME'),
            'file'=>        array('label'=>'FILES'),
            'view'=>        array('label'=>'VIEW'),
            'session'=>     array('label'=>'SESSION'),
            //'config'=>      array('label'=>'CONFIG'),
            'storage'=>      array('label'=>'LOGS','title'=>'Logs in storage'),
            'auth'=>        array('label'=>'AUTH'),
            'auth-sentry'=> array('label'=>'AUTH')
        ),
        
Add a link on your favorite doc

    'doc'=>'http://laravel.com/docs'

>**Note::** This will only disable the output, it will still do it's background listening but will not output it to the browser.

### jQuery

Set this option to `false` to not pull in jQuery from within the profiler. This is useful if you already have jQuery present on your page requests. Set to `true` by default.

    // config.php
    'jquery' => true

## Usage

### Custom Timers

To start a timer, all you need to do is:
    
    Profiler::start('my timer key');

To end the timer, simply call the end function like so:

    Profiler::end('my timer key');

## Logging

Profiler utilizes Laravels built in logging system and captures logged events. To log events, you can do (as you would with laravel) any of these:

    Log::debug('Your message here');
    Log::info('Your message here');
    Log::notice('Your message here');
    Log::warning('Your message here');
    Log::error('Your message here');
    Log::critical('Your message here');
    Log::alert('Your message here');
    Log::emergency('Your message here');

These are colour coded in the Logs part of the profiler - colours may change in future to more accurately reflect the log type.