<?php

return [
    'locale' => env('REQUEST_LOGGER_LOCALE', 'en'), // available 'en', 'ua'
    'middleware' => [
        \Dmeys\RequestLogger\Http\Middleware\BaseAuth::class,
    ],
    'base_auth' => [
        'login' => env('REQUEST_LOGGER_LOGIN', 'login'),
        'password' => env('REQUEST_LOGGER_PASSWORD', 'password'),
    ],
    'timezone' => env('REQUEST_LOGGER_TIMEZONE', env('APP_TIMEZONE', config('app.timezone', 'UTC'))),
    'date_format' => env('REQUEST_LOGGER_DATE_FORMAT', 'Y-m-d'),
    'time_format' => env('REQUEST_LOGGER_TIME_FORMAT', 'H:i:s.u'),
    'log_keep_days' => env('REQUEST_LOGGER_KEEP_DAYS', 14),
    'table_name' => 'request_logs',
    'enabled' => env('REQUEST_LOGGER_ENABLED', true),
    'ignore_paths' => [
        'request-logs*',
        'telescope*',
        'nova-api*',
    ],
    'hide_fields' => [
        'request' => [
            'headers' => [
                'authorization',
                'php-auth-user',
                'php-auth-pw',
            ],
            'content' => [
                'password',
                'token',
                'access_token',
            ],
        ],
        'response' => [
            'content' => [
                'password',
                'token',
                'access_token',
            ],
        ],
    ],
    'replacer_hidden_fields' => '|^_-|',
];
