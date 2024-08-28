<?php

namespace Dmeys\RequestLogger\Tests\RequestLogger;

use Dmeys\RequestLogger\Models\RequestLog;
use Dmeys\RequestLogger\RequestLogger;
use Dmeys\RequestLogger\Services\ResponseFormatter;
use Dmeys\RequestLogger\Support\Helpers\FileHelper;
use Dmeys\RequestLogger\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\Factories\UserFactory;

class RequestLoggerTest extends TestCase
{
    use WithFaker;

    public function test_basic_check_for_save_logs()
    {
        $request = Request::create('/test', 'post', [], [], [], [], 'test');
        $response = new Response('test', 200);

        $total_request_logs = RequestLog::query()->count();

        $file_helper = $this->app->make(FileHelper::class);
        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);
        $request_logger->save($request, $response);

        $this->assertEquals($total_request_logs + 1, RequestLog::query()->count());
        $this->assertEquals(true, Storage::exists($file_helper->getLogPath($request_logger->log_info)));
    }

    public function test_check_for_save_logs()
    {
        $request = Request::create('/test', 'post', [], [], [], [], 'test');
        $response = new Response('test', 200);
        $total_request_logs = RequestLog::query()->count();

        $total = $this->faker->numberBetween(10, 100);

        /** @var FileHelper $file_helper */
        $file_helper = $this->app->make(FileHelper::class);
        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);

        for ($i = 0; $i < $total; $i++) {
            $request_logger->save($request, $response);
        }

        $this->assertEquals($total_request_logs + $total, RequestLog::query()->count());

        $folder_logs = $file_helper->getFolderLogs($request_logger->log_info);

        $files = Storage::files($folder_logs);

        $this->assertGreaterThanOrEqual(count($files), $total);
    }

    public function test_check_user_id_save_logs()
    {
        $request = Request::create('/test', 'post', [], [], [], [], 'test');
        $response = new Response('test', 200);

        $user = UserFactory::new()->make(['id' => 999]);

        configureRequestLoggerCustomFields(function () use ($user) {
            return ['user_id' => $user->id];
        });

        /** @var FileHelper $fileHelper */
        $fileHelper = $this->app->make(FileHelper::class);
        /** @var RequestLogger $requestLogger */
        $requestLogger = $this->app->make(RequestLogger::class);

        $requestLogger->save($request, $response);

        $fileContent = Storage::get($fileHelper->getLogPath($requestLogger->log_info));

        $result = json_decode($fileContent, true);

        $result_response_content = Arr::get($result, 'response.content');

        $this->assertEquals(ResponseFormatter::$defaultContent, $result_response_content);
    }

    public function test_check_save_logs_with_different_url_types()
    {
        $this->withoutMix();

        $response = new Response('test', 200);
        $total_request_logs = RequestLog::query()->count();

        $requests = collect();

        $total_entries = $this->faker->numberBetween(10, 100);

        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);

        for ($i = 0; $i < $total_entries; $i++) {
            $method = $this->faker->randomElement([Request::METHOD_GET, Request::METHOD_POST]);
            $url = $this->faker->url();
            $urlWithoutDomain = preg_replace('#^.+://[^/]+#', '', $url);

            $request = Request::create($this->faker->randomElement([$url, $urlWithoutDomain]), $method, [], [], [], [], $this->faker->sentence());
            $requests->push($request);
        }

        foreach ($requests as $request) {
            $request_logger->save($request, $response);
        }

        $this->assertEquals($total_request_logs + $total_entries, RequestLog::query()->count());
        $this->call('GET', '/request-logs', [], [], [], $this->getServerBasicAuthParams())->assertStatus(200);
    }

    public function test_do_not_save_logs_when_disabled()
    {
        $request = Request::create('/test', 'post', [], [], [], [], 'test');
        $response = new Response('test', 200);

        $total_request_logs = RequestLog::query()->count();

        $this->app['config']->set('request-logger.enabled', false);

        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);
        $request_logger->save($request, $response);

        $this->assertEquals($total_request_logs, RequestLog::query()->count());
    }

    public function test_do_not_save_logs_when_request_is_ignored()
    {
        $request = Request::create('/test', 'post', [], [], [], [], 'test');
        $response = new Response('test', 200);

        $total_request_logs = RequestLog::query()->count();

        $this->app['config']->set('request-logger.ignore_paths', ['test*']);

        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);
        $request_logger->save($request, $response);

        $this->assertEquals($total_request_logs, RequestLog::query()->count());
    }

    public function test_do_not_save_self_logs()
    {
        $request = Request::create('/request-logs', 'post', [], [], [], [], 'test');
        $response = new Response();

        $total_request_logs = RequestLog::query()->count();

        $this->app['config']->set('request-logger.ignore_paths', '');

        /** @var RequestLogger $request_logger */
        $request_logger = $this->app->make(RequestLogger::class);
        $request_logger->save($request, $response);

        $this->assertEquals($total_request_logs, RequestLog::query()->count());
    }
}