<?php namespace Sebklaus\Profiler;

use Sebklaus\Profiler\Loggers\Time;
use Illuminate\Foundation\Application;

class Profiler {

	protected $view_data = array();
	protected $logs = array();
	protected $storageLogs = array();
	protected $laravelConfig = array();
	protected $includedFiles = array();

	protected $laravelVersion;
	public $time;

	public function __construct(Time $time, Application $app)
	{
		global $app;
		$this->time = $time;
		$this->app = $app;
		$this->laravelVersion = $app::VERSION;
	}

	/**
	 * Returns view data
	 *
	 * @return string
	 */
	public function getViewData()
	{
		return $this->view_data;
	}

	/**
	 * Sets View data if it meets certain criteria
	 *
	 * @param array $data
	 * @return void
	 */
	public function setViewData($data)
	{
		foreach($data as $key => $value)
		{
			if (! is_object($value))
			{
				$this->addKeyToData($key, $value);
			}
			else if(method_exists($value, 'toArray'))
			{
				$this->addKeyToData($key, $value->toArray());
			}
		}
	}

	/**
	 * Adds data to the array if key isn't set
	 *
	 * @param string $key
	 * @param string|array $value
	 * @return void
	 */
	protected function addKeyToData($key, $value)
	{
		if (is_array($value))
		{
			if(!isset($this->view_data[$key]) or (is_array($this->view_data[$key]) and !in_array($value, $this->view_data[$key])))
			{
				$this->view_data[$key][] = $value;
			}
		}
		else
		{
			$this->view_data[$key] = $value;
		}
	}

	/**
	 * Outputs gathered data to make Profiler
	 *
	 * @return html?
	 */
	public function outputData()
	{
		$this->time->totalTime();

		// Sort the view data alphabetically
		ksort($this->view_data);

		// Check if btns.storage config option is set
		if ($this->app['config']->get('profiler::btns.storage'))
		{
			// get last 24 webserver log entries
			$this->storageLogs = $this->getStorageLogs(24);
		}

		// Check if btns.config config option is set
		if ($this->app['config']->get('profiler::btns.config'))
		{
			// get all Laravel config options and store in array
			$this->laravelConfig = array_dot($this->app['config']->getItems());
		}

		$data = array(
			'times'         => $this->time->getTimes(),
			'view_data'     => $this->view_data,
			'app_logs'      => $this->logs,
			'storageLogs'   => $this->storageLogs,
			'config'        => $this->laravelConfig,
			'includedFiles' => get_included_files(),
			'counts'        => $this->getCounts(),
			'assetPath'     => __DIR__.'/../../../public/',
			'sql_log'       => $this->getDbLog(),
		);

		return $this->app['view']->make('profiler::profiler.core', $data);
	}

	/**
	 * return all scripts for btn count
	 *
	 * @return Array
	 */
	private function getCounts()
	{
		// Get the environment name
		$environment = $this->app->environment();

		// Determine the current controller name
		if ($this->isLaravelVersion('4.2,4.1'))
		{
			$controllerName = $this->app['router']->current()->getActionName();
		}
		elseif ($this->isLaravelVersion('4.0'))
		{
			$controllerName = $this->app['router']->currentRouteAction();
		}

		// Get the times
		$times = $this->time->getTimes();

		$counts = array(
			'environment' => $this->app->environment(),
			'memory'      => $this->getMemoryUsage(),
			'controller'  => $controllerName,
			'routes'      => count($this->app['router']->getRoutes()),
			'log'         => count($this->logs),
			'sql'         => count($this->getDbLog()),
			'checkpoints' => round($times['total'], 3),
			'file'        => count(get_included_files()),
			'view'        => count($this->getViewData()),
			'session'     => count($this->app['session']->all()),
			'storage'     => count($this->storageLogs),
			'config'      => count($this->laravelConfig),
		);

		if (isset($this->app['sentry']))
		{
			$counts['auth-sentry'] = ($authedUser = $this->app['sentry']->getUser()) ? $authedUser->email : 'User';
		}
		else
		{
			$counts['auth'] = ($authedUser = $this->app['auth']->user()) ? $authedUser->email : 'User';
		}

		return $counts;
	}
	
	/**
	 * return the last 24 entries of the webserver logs stored in app/storage/logs
	 *
	 * @return Array
	 */
	private function getStorageLogs($max=24)
	{
		$file = "";
		$log = array();
		// Search for lines starting with [Y-m-d H:i:s]
		$pattern = "/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.*/";
		// Include the below levels in output
		$log_levels=array(
			'emergency' =>	'EMERGENCY',
			'alert' =>		'ALERT',
			'critical' =>	'CRITICAL',
			'error' =>		'ERROR',
			'warning' =>	'WARNING'
			//'notice' =>	'NOTICE',
			//'info' =>		'INFO',
			//'debug' =>	'DEBUG'
		);
		// path to webserver logfile for current day
		$log_file = app_path().'/storage/logs/log-'.php_sapi_name().'-'.date('Y-m-d').'.txt';
		if ( file_exists($log_file))
		{
			// get logfile
			$file = \File::get($log_file);
			// scan logfile using $pattern and store lines in $headings
			preg_match_all($pattern, $file, $headings);
			// create array containing all logile entries, split using $pattern
			$log_data = preg_split($pattern, $file);
			// delete first array element, if empty
			if (empty($log_data[0]))
			{
				unset($log_data[0]);
			}
			// loop through all found lines
			foreach ($headings as $h)
			{
				for ($i=0; $i < count($h); $i++)
				{
					// Compare all found lines to specified log levels and store in $log array, containing error level, error description and stack trace, if match
					foreach ($log_levels as $ll)
					{
						if (strpos(strtolower($h[$i]), strtolower('log.' . $ll)))
						{
							$log[$i+1] = array('level' => $ll, 'header' => $h[$i], 'stack' => $log_data[$i+1]);
						}
					}
				}
			}
		}
		// Cleanup
		unset($headings);
		unset($log_data);
		// Sort from old to new and restrict to 24 entries
		$log = array_slice(array_reverse($log), 0, $max);
		// Return log entries
		return $log;
	}
	
	/**
	 * Get database logs
	 * @return array
	 */
	public function getDbLog()
	{
		// Check if SQL connection can be established
		try
		{
			return $this->app['db']->getQueryLog();
		}
		// Catch exception and return empty array
		catch (\PDOException $exception)
		{
			return array();
		}
	}

	/**
	 * Cleans an entire array (escapes HTML)
	 *
	 * @param array $data
	 * @return array
	 */
	public function cleanArray($data)
	{
		array_walk_recursive($data, function (&$data)
		{
			if (!is_object($data))
			{
				$data = htmlspecialchars($data);
			}
		});

		return $data;
	}

	/**
	 * Gets the memory usage
	 *
	 * @return string
	 */
	public static function getMemoryUsage()
	{
		return self::formatBytes(memory_get_usage());
	}

	/**
	 * Breaks bytes into larger chunks (e.g. B => MB)
	 *
	 * @param sting $bytes
	 * @return string
	 */
	protected static function formatBytes($bytes)
	{
		$measures = array('B', 'KB', 'MB', 'GB');
		for($i = 0; $bytes >= 1024; $i++)
		{
			$bytes = $bytes/1024;
		}
		return number_format($bytes,($i ? 2 : 0),'.', ',').$measures[$i];
	}

	/**
	 * Store log for later
	 *
	 * @param string $type
	 * @param string|object $message
	 */
	public function addLog($type, $message)
	{
		$this->logs[] = array($type, $message);
	}

	/**
	 * Start timer
	 *
	 * @param string $key
	 */
	public function start($key)
	{
		$this->time->start($key);
	}

	/**
	 * End timer
	 *
	 * @param string $key
	 */
	public function end($key)
	{
		$this->time->end($key);
	}

	/**
	 * Determine if the Laravel version start's with the given version number
	 * @param  string  $version
	 * @return boolean
	 */
	public function isLaravelVersion($version)
	{
		$versions = explode(',', $version);
		foreach ($versions as $version)
		{
			if (stripos($this->laravelVersion, $version) !== false)
			{
				return true;
			}
		}

		return false;
	}
}
