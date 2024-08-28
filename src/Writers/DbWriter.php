<?php

namespace Dmeys\RequestLogger\Writers;

use Dmeys\RequestLogger\LogInfo;
use Dmeys\RequestLogger\Models\RequestLog;
use Dmeys\RequestLogger\Models\RequestLogFingerprint;
use Dmeys\RequestLogger\Support\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class DbWriter implements Writer
{
    /**
     * @var FileHelper $file_helper
     */
    protected $file_helper;

    /**
     * @param FileHelper $file_helper
     */
    public function __construct(FileHelper $file_helper)
    {
        $this->file_helper = $file_helper;
    }

    public function write(LogInfo $logInfo)
    {
        try {
            DB::transaction(function () use ($logInfo) {
                $fingerprint = RequestLogFingerprint::query()->firstOrCreate([
                    'fingerprint' => $logInfo->fingerprint,
                ], [
                    'repeating' => 0,
                ]);

                RequestLog::query()->create(array_merge([
                    'fingerprint_id' => $fingerprint->id,
                    'url' => Str::limit($logInfo->request->fullUrl(), 250),
                    'method' => $logInfo->request->method(),
                    'ip' => $logInfo->request->getClientIp(),
                    'log_file' => $this->file_helper->getLogPath($logInfo),
                    'date' => $logInfo->loggerStart->format('Y-m-d H:i:s.u'),
                    'response_status_code' => $logInfo->response->getStatusCode(),
                    'duration_ms' => $logInfo->durationMs,
                    'memory' => $logInfo->memoryUsage,
                ], $logInfo->customFields));

                $fingerprint->increment('repeating');
            });
        } catch (Throwable $e) {
            Log::error($e);
        }
    }
}
