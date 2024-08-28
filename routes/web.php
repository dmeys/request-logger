<?php

use Dmeys\RequestLogger\Http\Controllers\RequestLoggerController;
use Dmeys\RequestLogger\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

$middleware = array_merge(config('request-logger.middleware'), [SetLocale::class]);

if (app() instanceof Application) { //Laravel
    Route::prefix('request-logs')->name('request-logs.')->middleware($middleware)->group(function () {
        Route::get('/', [RequestLoggerController::class, 'index'])->name('index');
        Route::get('/{id}/download', [RequestLoggerController::class, 'download'])->name('download');
        Route::get('/{id}/show', [RequestLoggerController::class, 'show'])->name('show');
        Route::get('/clear-all-logs', [RequestLoggerController::class, 'clearAllLogs'])->name('clearAllLogs');
    });
} else { //Lumen
    Route::group(['as' => 'request-logs', 'prefix' => 'request-logs', 'middleware' => $middleware], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'Dmeys\RequestLogger\Http\Controllers\RequestLoggerController@index']);
        Route::get('/{id}/download', ['as' => 'download', 'uses' => 'Dmeys\RequestLogger\Http\Controllers\RequestLoggerController@download']);
        Route::get('/{id}/show', ['as' => 'show', 'uses' => 'Dmeys\RequestLogger\Http\Controllers\RequestLoggerController@show']);
        Route::get('/clear-all-logs', ['as' => 'clearAllLogs', 'uses' => 'Dmeys\RequestLogger\Http\Controllers\RequestLoggerController@clearAllLogs']);
    });
}
