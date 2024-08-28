<?php

namespace Dmeys\RequestLogger\Http\Middleware;

use Closure;

class BaseAuth
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed|void
     */
    public function handle($request, Closure $next)
    {
        $user = $request->server->get('PHP_AUTH_USER', '');
        $pass = $request->server->get('PHP_AUTH_PW', '');

        $is_authenticated = false;
        $has_supplied_credentials = !(empty($user) && empty($pass));

        if ($has_supplied_credentials
            && $user === config('request-logger.base_auth.login')
            && $pass === config('request-logger.base_auth.password')) {
            $is_authenticated = true;
        }

        if (!$is_authenticated) {
            return response('Error Authorization Required', 401, ['WWW-Authenticate' => ' Basic realm="Access denied"']);
        }

        return $next($request);
    }
}
