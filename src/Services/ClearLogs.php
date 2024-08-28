<?php

namespace Dmeys\RequestLogger\Services;

use DateInterval;
use DateTime;
use Exception;
use Dmeys\RequestLogger\Models\RequestLog;
use Dmeys\RequestLogger\Models\RequestLogFingerprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClearLogs
{
	private $keep_days;
	private $now;
	private $last_date;

	public function __construct()
	{
		$this->keep_days = config('request-logger.log_keep_days');
		$this->now = new DateTime();
	}

	public function all()
	{
		$this->last_date = (clone $this->now);
		$this->clearAllLogsFromDatabase();
		$this->clearLogsFromStorage(true);
	}

	/**
	 * @throws Exception
	 */
	public function old()
	{
		$this->last_date = (clone $this->now)
			->sub(new DateInterval('P' . ($this->keep_days - 1) . 'D'))
			->setTime(00, 00, 00, 000000);
		$this->clearOldLogsFromDatabase();
		$this->clearLogsFromStorage();
	}

	/**
	 * @throws Exception
	 */
	private function clearOldLogsFromDatabase()
	{
		DB::transaction(function () {

			$logs_will_be_deleted = RequestLog::query()
				->where('date', '<', $this->last_date)
				->pluck('fingerprint_id', 'id')
				->toArray();

			if (empty($logs_will_be_deleted)) {
				return;
			}

			RequestLog::query()
				->where('date', '<', $this->last_date)
				->delete();

			$logs_group_by_fingerprint = array_count_values($logs_will_be_deleted);
			$group_fingerprints_by_repeating = [];
			foreach ($logs_group_by_fingerprint as $fingerprint_id => $repeating) {
				$group_fingerprints_by_repeating[$repeating][] = $fingerprint_id;
			}

			foreach ($group_fingerprints_by_repeating as $repeating => $fingerprint_ids) {
				$fingerprint_ids_chunks = array_chunk($fingerprint_ids, 3000);
				foreach ($fingerprint_ids_chunks as $ids) {
					RequestLogFingerprint::query()
						->whereIn('id', $ids)
						->decrement('repeating', $repeating);
				}
			}

			RequestLogFingerprint::query()
				->where('repeating', 0)
				->delete();
		});
	}

	private function clearAllLogsFromDatabase()
	{
		RequestLog::query()->truncate();
		RequestLogFingerprint::query()->truncate();
	}

	/**
	 * @param false $all
	 */
	private function clearLogsFromStorage($all = false)
	{
		$dirs = Storage::directories('request-logs');
		foreach ($dirs as $dir) {
			list(, $date_dir) = explode('/', $dir);
			$date = DateTime::createFromFormat('Y-m-d', $date_dir);
			if ($date !== false) {
				$date = $date->setTime(00, 00, 00, 000000);
				$interval = $date->diff($this->now);
				if ($interval->days >= $this->keep_days || $all) {
					if ($interval->days === 0) {
						$time_dirs = Storage::directories($dir);
						foreach ($time_dirs as $time_dir) {
							list(, , $hour_minute_dir) = explode('/', $time_dir);
							list($hour, $minute) = explode('-', $hour_minute_dir);
							$hour = filter_var($hour, FILTER_SANITIZE_NUMBER_INT);
							$minute = filter_var($minute, FILTER_SANITIZE_NUMBER_INT);
							$now_hour = $this->now->format('H');
							$now_minute = $this->now->format('i');
							if ($hour < $now_hour || ($hour === $now_hour && $minute < $now_minute)) {
								Storage::deleteDirectory($time_dir);
							}
						}
					} else {
						Storage::deleteDirectory($dir);
					}
				}
			}
		}
	}
}
