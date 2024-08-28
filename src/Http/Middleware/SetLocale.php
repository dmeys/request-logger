<?php

namespace Dmeys\RequestLogger\Http\Middleware;

use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $app = app();
        if ($app instanceof \Laravel\Lumen\Application) {
            $app = app('translator');
        }

        $app->setLocale(config('request-logger.locale'));

        return $next($request);
    }
}
