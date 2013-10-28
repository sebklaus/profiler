<?php

// Use 'before' filter to make sure all previously configured routes are still executed
// Register new wildcard route and check if it contains '_profiler'
$this->app->before(function() {
	if ($this->app['config']->get('profiler::urlToggle'))
	{
		// Register GET route
		$this->app['router']->get('{path?}', function($path)
		{
			if (stristr($path, '_profiler') !== FALSE)
			{
				// Origin
				$origin = str_ireplace('_profiler', '', $path);
				// Check, if production is current environment and Profiler not specifically enabled
				if (\App::environment() == 'production' && $this->app['session']->get('_profiler') !== TRUE)
				{
					// Data to send to view
					$data = array(
						'assetPath' =>	__DIR__.'/../../public/',
						'path' => $path,
						'origin' => $origin,
					);
					// Show Password view
					return View::make('profiler::profiler.password', $data);
				}
				// Resume normal Profiler enabling/ disabling
				else
				{
					// Check if a '_profiler' session key exists and reverse its value
					$state = $this->app['session']->get('_profiler') ? FALSE : TRUE;
					// Apply new state to profiler config
					$this->app['session']->set('_profiler', $state);
					// Redirect back to origin
					return $this->app['redirect']->to($origin);
				}
			}
		})->where('path', '.+');
		// Register POST route (only in production environment)
		if (\App::environment() == 'production')
		{
			$this->app['router']->post('{path?}', function($path)
			{
				if (stristr($path, '_profiler') !== FALSE)
				{
					// Origin
					$origin = str_ireplace('_profiler', '', $path);
					// Check if a '_profiler' session key exists and reverse its value
					$state = $this->app['session']->get('_profiler') ? FALSE : TRUE;
					// Check, if submitted passwaord matches config password
					if (\Hash::check(\Input::get('password'), $this->app['config']->get('profiler::urlTogglePassword'))) {
						// Apply new state to profiler config
						$this->app['session']->set('_profiler', $state);
					}
					// Redirect back to origin
					return $this->app['redirect']->to($origin);
				}
			})->where('path', '.+');
		}
	}
});