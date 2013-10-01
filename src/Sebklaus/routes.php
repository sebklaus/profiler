<?php

// Register new Route /_profiler
$this->app['router']->get('_profiler', function()
{
    // Check current state of profiler (enabled/ disabled?) and set to opposite
	$state = $this->app['session']->get('_profiler') ? FALSE : TRUE;
	// Apply state to profiler config
	$this->app['session']->set('_profiler', $state);
	// Redirect to root
	return $this->app['redirect']->to('/');
});