<?php

namespace Dmeys\RequestLogger\Tests;

class AuthenticationTest extends TestCase
{
    public function test_render_index_page_with_basic_auth_successfully()
    {
        $this->withoutMix();

        $this->call('GET', '/request-logs', [], [], [], $this->getServerBasicAuthParams())->assertStatus(200);
    }

    public function test_show_error_unauthorized_requests()
    {
        $this->withoutMix();

        $this->get('/request-logs')->assertStatus(401);
    }

    public function test_show_error_with_bad_credentials_requests()
    {
        $this->withoutMix();

        $server = $this->getServerBasicAuthParams();
        $server ['PHP_AUTH_PW'] = 'test';

        $this->call('GET', '/request-logs', [], [], [], $server)->assertStatus(401);
    }
}