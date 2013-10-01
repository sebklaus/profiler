# Continued
This is a continuation of [juy/profiler](https://github.com/juy/profiler).  
It includes Pull Requests [#34](https://github.com/juy/profiler/pull/34) &amp; [#6](https://github.com/juy/profiler/pull/6) plus several minor additions.

# Profiler

A PHP 5.3 profiler for [Laravel 4](http://www.laravel.com).  
Backend based on sorora/omni, frontend based on loic-sharma/profiler. Some features inspired by papajoker/profiler, some features by juy/profiler with minor adjustments by myself.

[![](http://i.imm.io/19tLC.png)](http://i.imm.io/19tLC.png "Click for big picture")

## Features

- Laravel Environment info
- Total memory usage
- Current controller/action info
- Routes
- Log events
- SQL Query Log with syntax highlighting
- Total execution time
    - Custom "checkpoints", see [Custom Timers](#custom-timers) for more details.
- Includes all application files
- All variables passed to the current view
- Session variables
- Laravel Config settings
- Laravel webserver error logs
- Laravel auth variables (if used)
- Sentry auth variables (if used)
- Enabling/ Disabling via URL (add **/_profiler** to root URL (eg. *http://localhost/_profiler*))

## Installation
To add Profiler to your Laravel application, add the below line to your `composer.json` file, either in `"require": { … }` or `"require-dev": { … }`:

    "sebklaus/profiler" : "dev-master"

Then run `composer update` or `composer install`, if it is the first time you install packages.  

The final step is to add the below line to the end of the `providers` array in `app/config/app.php` (or the appropriate environment `app.php`):

    'Sebklaus\Profiler\Providers\ProfilerServiceProvider',

## Configuration

In order to be able to change the default settings of Profiler, publish the config file by running:

    php artisan config:publish sebklaus/profiler

To change the default settings, edit `app/config/packages/sebklaus/profiler/config.php`.

### Profiler

Set this option to `FALSE` to deactivate Profiler or `TRUE` to activate it.  
The default `NULL` uses the `debug` setting of `app/config/app.php` (or the appropriate environment `app.php`) to activate/ deactivate.

    // config.php
    'profiler' => NULL,

To be able to activate Profiler via URL (add **/_profiler** to root URL (eg. *http://localhost/_profiler*)), set the `urlToggle` option to `TRUE`:

	// config.php
	'urlToggle' => FALSE,

If you wish to disable the profiler during runtime, add the below code to your script:

    Config::set('profiler::profiler', false);
    
You can (re-)order, disable and adjust label and title for the various info sections of Profiler by editing values of the `btns` array:

    // config.php
	'btns' => array(
		'environment'=> array('label'=>'ENV','title'=>'Environment'),
		'memory'=>		array('label'=>'MEM','title'=>'Memory'),
		'controller'=>	array('label'=>'CTRL','title'=>'Controller'),
		'routes'=>		array('label'=>'ROUTES'),
		'log'=>			array('label'=>'LOG'),
		'sql'=>			array('label'=>'SQL'),
		'checkpoints'=> array('label'=>'TIME'),
		'file'=>		array('label'=>'FILES'),
		'view'=>		array('label'=>'VIEW'),
		'session'=>		array('label'=>'SESSION'),
		'config'=>		array('label'=>'CONFIG'),
		'storage'=>		array('label'=>'LOGS','title'=>'Logs in storage'),
		'auth'=>		array('label'=>'AUTH'),
		'auth-sentry'=> array('label'=>'AUTH')
	),
        
Add a link to your preferred (Laravel) documentation (or really anywhere you want) by changing the value of `doc`.  
The link is active on the Laravel logo on the left hand side of the opened Profiler.

    // config.php
    'doc'=>'http://laravel.com/docs',

### jQuery

Profiler makes use of [jQuery](http://jquery.com), which is automatically loaded and included, if it can't be detected.

## Usage

### Custom Timers

To start a timer, all you need to do is:
    
    Profiler::start('my timer key');

To end the timer, simply call the end function like so:

    Profiler::end('my timer key');

## Logging

Profiler utilizes Laravels built in logging system and captures logged events. To log events, you can use (as you would with Laravel) any of these:

    Log::debug('Your message here');
    Log::info('Your message here');
    Log::notice('Your message here');
    Log::warning('Your message here');
    Log::error('Your message here');
    Log::critical('Your message here');
    Log::alert('Your message here');
    Log::emergency('Your message here');

These are colour coded in the `Log` part of Profiler - colours may change in the future to more accurately reflect the log type.