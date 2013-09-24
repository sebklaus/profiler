<?php namespace Sebklaus\Profiler;

use Sebklaus\Profiler\Loggers\Time;

class Profiler {

    protected $view_data = array();

    protected $logs = array();

    public $time;

	protected $includedFiles = array();

    public function __construct(Time $time)
    {
        $this->time = $time;
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
        if (\Config::get('profiler::profiler'))
        {
            // Sort the view data alphabetically
            ksort($this->view_data);

            $this->time->totalTime();

            $data = array(
                'times'		=> $this->time->getTimes(),
                'view_data'	=> $this->view_data,
                'sql_log'	=> array_reverse(\DB::getQueryLog()),
                'app_logs'	=> $this->logs,
                'includedFiles'	=> get_included_files(),
		'counts'	=> $this->getCounts(),
		'assetPath' => __DIR__.'/../../assets/',
            );
	    if (\Config::get('profiler::btns.storage'))
			$data['storageLogs'] = $this->getStorageLogs(24);
	    if (\Config::get('profiler::btns.config'))
			$data['config'] = array_dot(\Config::getItems());

            return \View::make('profiler::profiler.core', $data);
        }
    }
    
    /**
     * return all scripts for btn count
     *
     * @return Array
     */
    private function getCounts()
    {
		return array(
            'environment'=> function(){ return \App::environment(); },
            'memory'=>      function(){ return Profiler::getMemoryUsage(); },
            'controller'=>  function(){ return \Route::currentRouteAction(); },
            'routes'=>      function(){ return count(\Route::getRoutes()); },
            'log'=>         function($app_logs){ return count($app_logs); },
            'sql'=>         function($sql_log){ return count($sql_log); },
            'checkpoints'=> function($times){ return round($times['total'], 3); },
            'file'=>        function($includedFiles){ return count($includedFiles); },
            'view'=>        function($view_data){ return count($view_data); },
            'session'=>     function(){ return count(\Session::all()); },
			'storage'=>     function($storageLogs){ return count($storageLogs); },
			'config'=>    	function($config){ return count($config); },
            'auth'=>        function() { return ''; },
            'auth-sentry'=> function() { \Sentry::getUser()->email; },
        );
    }
    
    /**
     * get logs apache in app/storage/logs 
     * only 24 last of current day
     *
     * @return Array
     */
    private function getStorageLogs($max=24)
    {
	$file=
	$log = array();
	$pattern = "/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.*/";
	$log_levels=array(
	    'emergency' => 'EMERGENCY',
	    'alert' => 'ALERT',
	    'critical' => 'CRITICAL',
	    'error' => 'ERROR',
	    'warning' => 'WARNING'
	    //'notice' => 'NOTICE',
	    //'info' => 'INFO',
	    //'debug' => 'DEBUG'
	);
	$log_file = app_path().'/storage/logs/log-'.php_sapi_name().'-'.date('Y-m-d').'.txt';
 	if ( file_exists($log_file)) {
	    $file = \File::get($log_file);
 
	    preg_match_all($pattern, $file, $headings);
	    $log_data = preg_split($pattern, $file);
 
	    unset($log_data[0]);            
	    foreach ($headings as $h) {
		for ($i=0; $i < count($h); $i++) {
		    foreach ($log_levels as $ll) {
			if (strpos(strtolower($h[$i]), strtolower('log.' . $ll))) {
			    $log[$i+1] = array('level' => $ll, 'header' => $h[$i], 'stack' => $log_data[$i+1]);
			}
		    }
		}
	    }
	}
 
	unset($headings);
	unset($log_data);
	$log = array_slice( array_reverse($log), 0, $max);//news to old and 24 max
	return $log;
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
    public function getMemoryUsage()
    {
        return $this->formatBytes(memory_get_usage());
    }

    /**
     * Breaks bytes into larger chunks (e.g. B => MB)
     *
     * @param sting $bytes
     * @return string
     */
    protected function formatBytes($bytes)
    {
        $measures = array('B', 'KB', 'MB', 'DB');
        $bytes = memory_get_usage();
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
}