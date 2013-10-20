<?php

// Use 'before' filter to make sure all previously configured routes are still executed
// Register new wildcard route and check if it contains '_profiler'
$this->app->before(function() {
	if ($this->app['config']->get('profiler::urlToggle'))
	{
		$this->app['router']->get('{path?}', function($path)
		{
			if (stristr($path, '_profiler') !== FALSE)
			{
				// Check if a '_profiler' session key exists and reverse its value
				$state = $this->app['session']->get('_profiler') ? FALSE : TRUE;
				// Apply new state to profiler config
				$this->app['session']->set('_profiler', $state);
				// Redirect back to origin
				$origin = str_ireplace('_profiler', '', $path);
				return $this->app['redirect']->to($origin);
			}
		})->where('path', '.+');
	}
});