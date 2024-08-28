<?php

namespace Dmeys\RequestLogger\Tests;

use Dmeys\RequestLogger\Services\Concealer;

class ConcealerTest extends TestCase
{
    public function test_check_hiding_request_data_with_concealer()
    {
        $data = [
            'login' => 'test',
            'password' => 'test',
            'data' => [
                [
                    'value',
                    'value2',
                ],
                [
                    'token' => 'token',
                ],
                [
                    'value3' => [
                        'value4'
                    ]
                ]
            ]
        ];
        $hide_fields = [
            'password',
            'token',
        ];
        $data_replaced = [
            'login' => 'test',
            'password' => $this->app->config['request-logger']['replacer_hidden_fields'],
            'data' => [
                [
                    'value',
                    'value2',
                ],
                [
                    'token' => $this->app->config['request-logger']['replacer_hidden_fields'],
                ],
                [
                    'value3' => [
                        'value4'
                    ]
                ]
            ]
        ];

        $concealer = new Concealer();
        $result = $concealer->hide($data, $hide_fields);
        $this->assertEquals($data_replaced, $result);
    }
}