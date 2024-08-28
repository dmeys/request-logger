<?php

namespace Dmeys\RequestLogger\Support;

use Illuminate\Routing\Controller as LaravelController;
use Laravel\Lumen\Routing\Controller as LumenController;

if (class_exists(LumenController::class)) {
    class BaseController extends LumenController
    {

    }
} else {
    class BaseController extends LaravelController
    {

    }
}

