<?php

namespace Dmeys\RequestLogger\Writers;

use Dmeys\RequestLogger\LogInfo;
use Dmeys\RequestLogger\Services\RequestFormatter;
use Dmeys\RequestLogger\Services\ResponseFormatter;
use Dmeys\RequestLogger\Support\Helpers\FileHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileWriter implements Writer
{
    /**
     * @var RequestFormatter $request_formatter
     */
    private $request_formatter;

    /**
     * @var ResponseFormatter $response_formatter
     */
    private $response_formatter;

    /**
     * @var FileHelper $file_helper
     */
    private $file_helper;

    /**
     * @var LogInfo $log_info
     */
    private $log_info;

    /**
     * @param RequestFormatter $request_formatter
     * @param ResponseFormatter $response_formatter
     * @param FileHelper $file_helper
     */
    public function __construct(
        RequestFormatter  $request_formatter,
        ResponseFormatter $response_formatter,
        FileHelper        $file_helper)
    {
        $this->request_formatter = $request_formatter;
        $this->response_formatter = $response_formatter;
        $this->file_helper = $file_helper;
    }

    /**
     * @param LogInfo $logInfo
     */
    public function write(LogInfo $logInfo)
    {
        $this->log_info = $logInfo;
        try {
            Storage::put(
                $this->file_helper->getLogPath($this->log_info),
                $this->getContentLogFile()
            );
        } catch (Throwable $e) {
            Log::error($e);
        }
    }

    /**
     * @return false|string
     */
    private function getContentLogFile()
    {
        $data = [
            'request' => [
                'fingerprint' => $this->log_info->fingerprint,
                'url' => $this->log_info->request->fullUrl(),
                'method' => $this->log_info->request->method(),
                'headers' => $this->request_formatter->getHeaders($this->log_info->request),
                'ip' => $this->log_info->request->getClientIp(),
                'content' => $this->request_formatter->getContent($this->log_info->request),
            ],
            'response' => [
                'headers' => $this->response_formatter->getHeaders($this->log_info->response),
                'status' => $this->log_info->response->getStatusCode(),
                'content' => $this->response_formatter->getContent($this->log_info->response),
            ],
            'duration' => "{$this->log_info->durationMs} milliseconds",
            'memory' => "{$this->log_info->memoryUsage} MB",
        ];

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
