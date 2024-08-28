<?php

namespace Dmeys\RequestLogger;

use Closure;
use DateTime;
use DateTimeZone;
use Dmeys\RequestLogger\Writers\LogWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class RequestLogger
{
    /**
     * @var LogInfo $log_info
     */
    public $log_info;

    /**
     * @var LogWriter $log_writer
     */
    protected $log_writer;
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Closure
     */
    private $customFieldsConfigure;

    /**
     * @param LogWriter $log_writer
     */
    public function __construct(LogWriter $log_writer)
    {
        $this->log_writer = $log_writer;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function save(Request $request, Response $response)
    {
        if (!config('request-logger.enabled') || $this->shouldIgnoreRequest($request)) {
            return;
        }

        $this->request = $request;

        $customFields = $this->getCustomFields();

        $this->log_info = new LogInfo(
            $request,
            $response,
            $this->getLoggerStart(),
            $this->getDuration(),
            $this->getMemoryUsage(),
            $this->getFingerprint(),
            $customFields
        );

        $this->log_writer->write($this->log_info);
    }

    /**
     * @return float
     */
    protected function getStartTime(): float
    {
        $start_time = defined('LARAVEL_START') ? LARAVEL_START : (float)$this->request->server('REQUEST_TIME_FLOAT');
        if (!strpos($start_time, '.')) {
            $start_time .= '.0001';
        }
        return (float)$start_time;
    }

    /**
     * @return DateTime
     */
    protected function getLoggerStart(): DateTime
    {
        $start_time = $this->getStartTime();
        $logger_start = DateTime::createFromFormat('U.u', $start_time);

        try {
            $timezone = new DateTimeZone(config('request-logger.timezone'));
            $logger_start->setTimezone($timezone);
        } catch (Throwable $e) {
            Log::error($e);
        }

        return $logger_start;
    }

    /**
     * @return float
     */
    protected function getDuration(): float
    {
        $start_time = $this->getStartTime();
        return round((microtime(true) - $start_time) * 1000);
    }

    /**
     * @return float
     */
    protected function getMemoryUsage(): float
    {
        return round(memory_get_peak_usage(true) / 1024 / 1024, 1);
    }

    /**
     * @return string
     */
    public function getFingerprint(): string
    {
        return sha1(implode('|', [
            $this->request->method(),
            $this->request->fullUrl(),
            $this->request->getContent(),
        ]));
    }

    /**
     * Determine if the request should be ignored.
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldIgnoreRequest(Request $request): bool
    {
        $ignore_paths = config('request-logger.ignore_paths');
        $ignore_paths = is_array($ignore_paths) ? $ignore_paths : [];
        $ignore_paths = array_unique(array_merge($ignore_paths, ['request-logs*']));

        foreach ($ignore_paths as $ignore_path) {
            if ($request->is($ignore_path)) {
                return true;
            }
        }

        return false;
    }

    protected function getCustomFields(): array
    {
        if (is_callable($this->customFieldsConfigure)) {
            return call_user_func($this->customFieldsConfigure) ?? [];
        }

        return [];
    }

    /**
     * @param mixed $customFieldsConfigure
     */
    public function setCustomFieldsConfigure($customFieldsConfigure): void
    {
        $this->customFieldsConfigure = $customFieldsConfigure;
    }
}
