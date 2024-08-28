<?php

namespace Dmeys\RequestLogger\Tests;

use Dmeys\RequestLogger\RequestLoggerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PDO;

abstract class TestCase extends OrchestraTestCase
{
    private static $setupDatabase = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('request-logger.table_name', 'request-logs');
        $app['config']->set('database.default', 'mysql');
    }

    protected function getPackageProviders($app): array
    {
        return [RequestLoggerServiceProvider::class];
    }

    protected function getServerBasicAuthParams(): array
    {
        $login = 'login';
        $password = 'password';

        $this->app['config']->set('request-logger.base_auth', [
            'login' => $login,
            'password' => $password,
        ]);

        return [
            'PHP_AUTH_USER' => $login,
            'PHP_AUTH_PW' => $password
        ];
    }

    private function setupDatabase()
    {
        if (self::$setupDatabase) {
            $this->createDatabase();
            $this->artisan('migrate:fresh');
        }
        self::$setupDatabase = false;
    }

    private function createDatabase()
    {
        $host = config('database.connections.mysql.host');
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');
        $database = config('database.connections.mysql.database', 'request-logger-test');

        $query = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET $charset COLLATE $collation;";

        $pdo = new PDO(
            "mysql:host=$host",
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password')
        );
        $pdo->query($query);
    }
}
