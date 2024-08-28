<?php

namespace Dmeys\RequestLogger\Console\Commands;

use Exception;
use Dmeys\RequestLogger\Services\ClearLogs;
use Illuminate\Console\Command;

class ClearRequestLogs extends Command
{
	/**
	 * @var string
	 */
	protected $signature = 'request-logs:clear {--all}';

	/**
	 * @var string
	 */
	protected $description = 'Clear request logs';

	/**
	 * @param ClearLogs $clear_logs
	 * @throws Exception
	 */
	public function handle(ClearLogs $clear_logs)
	{
		$all = $this->option('all');
		if ($all) {
			$clear_logs->all();
		} else {
			$clear_logs->old();
		}
	}
}
