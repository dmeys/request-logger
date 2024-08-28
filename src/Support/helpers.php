<?php

use Dmeys\RequestLogger\RequestLogger;

if (!function_exists('configureRequestLoggerCustomFields')) {
    /** Register callback for custom fields
     * @param callable $callback
     * @return void
     */
    function configureRequestLoggerCustomFields(callable $callback)
    {
        /** @var RequestLogger $requestLogger */
        $requestLogger = app(RequestLogger::class);
        $requestLogger->setCustomFieldsConfigure($callback);
    }
}