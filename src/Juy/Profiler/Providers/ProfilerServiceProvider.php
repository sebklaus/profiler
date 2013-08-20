<?php namespace Juy\Profiler\Providers;

use Illuminate\Support\ServiceProvider;
use Juy\Profiler\Facades\Profiler;

class ProfilerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = FALSE;

	protected $profiler = TRUE;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('juy/profiler');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->shareWithApp();
		$this->registerAlias();
		$this->loadConfig();
		$this->registerViews();
		$this->activateProfiler();
	}

	/**
	 * Share the package with application
	 *
	 * @return void
	 */
	protected function shareWithApp()
	{
		$this->app['profiler'] = $this->app->share(function($app)
		{
			return new \Juy\Profiler\Profiler(
				new \Juy\Profiler\Loggers\Time
			);
		});
	}

	/**
	 * Register the alias for package.
	 *
	 * @return void
	 */
	protected function registerAlias()
	{
		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Profiler', 'Juy\Profiler\Facades\Profiler');
		});
	}

	/**
	 * Load the config for the package
	 *
	 * @return void
	 */
	protected function loadConfig()
	{
		$this->app['config']->package('juy/profiler', __DIR__.'/../../../config');
	}

	/**
	 * Register views
	 *
	 * @return void
	 */
	protected function registerViews()
	{
   		$this->app['view']->addNamespace('profiler', __DIR__.'/../../../views');
	}

	/**
	 * Activates the profiler
	 *
	 * @return void
	 */
	protected function activateProfiler()
	{
		// If the profiler config is NULL, get value from app.debug
		if (is_null($this->app['config']->get('profiler::profiler')))
		{
			$this->app['config']->set('profiler::profiler', $this->app['config']->get('app.debug'));
		}

		// Check console isn't running and profiler is enabled
		$this->profiler = (!$this->app->runningInConsole() and !$this->app['request']->ajax()) ? $this->app['config']->get('profiler::profiler') : false;

		if ($this->profiler)
		{
			$this->afterListener();
			$this->listenViewComposing();
			$this->listenLogs();
		}
	}

	/**
	 * Output data on route after
	 *
	 * @return void
	 */
	protected function afterListener()
	{
		$this->app['router']->after(function ($request, $response)
		{
			// Do not display profiler on non-HTML responses.
			if (\Str::startsWith($response->headers->get('Content-Type'), 'text/html'))
			{
				$content = $response->getContent();
				$output = Profiler::outputData();
				$body_position = strripos($content, '</body>');

				if ($body_position !== FALSE)
				{
					$content = substr($content, 0, $body_position) . $output . substr($content, $body_position);
				}
				else
				{
					$content .= $output;
				}

				$response->setContent($content);
			}
		});
	}

	/**
	 * Listen to view composing events
	 *
	 * @return void
	 */
	protected function listenViewComposing()
	{
		$this->app['events']->listen('composing:*', function($data)
		{
			Profiler::setViewData($data->getData());
		});
	}

	/**
	 * Listen to logging events
	 *
	 * @return void
	 */
	protected function listenLogs()
	{
		$this->app['events']->listen('illuminate.log', function($type, $message)
		{
			Profiler::addLog($type, $message);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('profiler');
	}

}
