<?php namespace Juy\Profiler\Loggers;

class Time {

	protected $start;
	protected $end;
	protected $times = array();

	public function __construct()
	{
		$this->start = $this->getStartTime();
	}

	/**
	 * Start a timer
	 *
	 * @param $key
	 * @return array
	 */
	public function start($key)
	{
		$this->times[$key]['start'] = $this->getMicrotime();
	}

	/**
	 * End a timer
	 *
	 * @param $key
	 * @return array
	 */
	public function end($key)
	{
		$this->times[$key]['end'] = $this->getMicrotime();
		$this->times[$key]['total'] = $this->calcTotal($key);
	}

	/**
	 * Calculate the total of a user provided timer
	 *
	 * @param $key
	 * @return array
	 */
	public function calcTotal($key)
	{
		if (!isset($this->times[$key]['end']))
		{
			return 'No end time recorded for '.$key;
		}

		if (!isset($this->times[$key]['start']))
		{
			return 'No start time recorded for '.$key;
		}

		return $this->times[$key]['end'] - $this->times[$key]['start'];
	}

	/**
	 * Get times from $this->times
	 *
	 * @return array
	 */
	public function getTimes()
	{
		return $this->times;
	}

	/**
	 * Calculate total time and push to array
	 *
	 * @return void
	 */
	public function totalTime()
	{
		$this->end = $this->getMicrotime();
		$this->times['total'] = $this->end - $this->start;
	}

	/**
	 * Get the start time
	 *
	 * @return int
	 */
	protected function getStartTime()
	{
		if (defined('LARAVEL_START'))
		{
			return LARAVEL_START;
		}
		return $this->getMicrotime();
	}

	/**
	 * Get the time from microtime in seconds
	 *
	 * @return int
	 */
	protected function getMicrotime()
	{
		$time = explode(' ', microtime());
		return $time[1] + $time[0];
	}
}
