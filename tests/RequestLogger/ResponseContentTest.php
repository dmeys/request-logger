<?php

namespace Dmeys\RequestLogger\Tests\RequestLogger;

use Dmeys\RequestLogger\RequestLogger;
use Dmeys\RequestLogger\Services\ResponseFormatter;
use Dmeys\RequestLogger\Support\Helpers\FileHelper;
use Dmeys\RequestLogger\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseContentTest extends TestCase
{
    use WithFaker;

    public function test_check_response_json_content()
    {
        $payload = [
            'message' => 'request logger is cool'
        ];
        $request = Request::create('test', 'post', [], [], [], [], 'test');
        $response = new Response($payload, 200);

        /** @var FileHelper $file_helper */
        $file_helper = $this->app->make(FileHelper::class);
        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);
        $request_logger->save($request, $response);

        $file_content = Storage::get($file_helper->getLogPath($request_logger->log_info));

        $result = json_decode($file_content, true);
        $result_response_content = Arr::get($result, 'response.content');

        $this->assertEquals($payload, $result_response_content);
    }

    public function test_check_not_supported_streamed_response_content()
    {
        $request = Request::create('test', 'post', [], [], [], [], 'test');
        $response = new StreamedResponse(null, 200);

        /** @var FileHelper $file_helper */
        $file_helper = $this->app->make(FileHelper::class);
        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);
        $request_logger->save($request, $response);

        $file_content = Storage::get($file_helper->getLogPath($request_logger->log_info));

        $result = json_decode($file_content, true);
        $result_response_content = Arr::get($result, 'response.content');

        $this->assertEquals(ResponseFormatter::$defaultContent, $result_response_content);
    }

    public function test_check_not_supported_regular_html_response_content()
    {
        $html = $this->faker->randomHtml();

        $request = Request::create('test', 'get', [], [], [], [], 'test');
        $response = new Response($html, 200);

        /** @var FileHelper $file_helper */
        $file_helper = $this->app->make(FileHelper::class);
        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);
        $request_logger->save($request, $response);

        $file_content = Storage::get($file_helper->getLogPath($request_logger->log_info));

        $result = json_decode($file_content, true);
        $result_response_content = Arr::get($result, 'response.content');

        $this->assertEquals(ResponseFormatter::$defaultContent, $result_response_content);
    }
}