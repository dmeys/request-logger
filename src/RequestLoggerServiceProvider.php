<?php

namespace Dmeys\RequestLogger;

use Dmeys\RequestLogger\Console\Commands\ClearRequestLogs;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class RequestLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->configure();

        if ($this->app instanceof \Laravel\Lumen\Application) { //Lumen
            require_once 'Support/lumenHelpers.php';
        }

        $this->app->singleton(\Dmeys\RequestLogger\RequestLogger::class, \Dmeys\RequestLogger\RequestLogger::class);
    }

    public function boot()
    {
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerTranslations();
        $this->offerPublishing();
        $this->setPaginator();
    }

    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/request-logger.php',
            'request-logger'
        );
    }

    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'request-logs');
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearRequestLogs::class
            ]);
        }
    }

    protected function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'request-logger');
    }

    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/request-logger.php' => config_path('request-logger.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/request-logger'),
            ], 'public');
        }
    }

    private function setPaginator()
    {
        if (method_exists(Paginator::class, 'useBootstrap')) {
            Paginator::useBootstrap();
        }
    }
}
