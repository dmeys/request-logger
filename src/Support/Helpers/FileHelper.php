<?php

namespace Dmeys\RequestLogger\Support\Helpers;

use Dmeys\RequestLogger\LogInfo;

class FileHelper
{
    /**
     * @param LogInfo $log_info
     * @return string
     */
    public function getFolderLogs(LogInfo $log_info): string
    {
        return "request-logs/"
            . "{$log_info->loggerStart->format('Y')}-{$log_info->loggerStart->format('m')}-{$log_info->loggerStart->format('d')}/"
            . "{$log_info->loggerStart->format('H')}h-{$log_info->loggerStart->format('i')}m/";
    }

    /**
     * @param LogInfo $log_info
     * @return string
     */
    public function getLogFileName(LogInfo $log_info): string
    {
        $url = str_replace('/', '.', $log_info->request->path());
        return "$url # {$log_info->request->method()} # {$log_info->response->getStatusCode()} # {$log_info->loggerStart->format('H.i.s.u')}.log";
    }

    /**
     * @param LogInfo $log_info
     * @return string
     */
    public function getLogPath(LogInfo $log_info): string
    {
        return $this->getFolderLogs($log_info) . $this->getLogFileName($log_info);
    }
}
