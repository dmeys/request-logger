<?php

namespace Dmeys\RequestLogger\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Dmeys\RequestLogger\RequestLogger as RequestLoggerService;

class RequestLogger
{
    public $request_logger;

    public function __construct(RequestLoggerService $request_logger)
    {
        $this->request_logger = $request_logger;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $this->request_logger->save($request, $response);
        return $response;
    }
}
