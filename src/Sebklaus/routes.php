<?php

// Register new Route /_profiler
$this->app['router']->get('_profiler', function()
{
	$state = $this->app['session']->get('_profiler') ? FALSE : TRUE;
	$this->app['session']->set('_profiler', $state);
	return $this->app['redirect']->to('/');
});