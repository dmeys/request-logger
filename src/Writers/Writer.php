<?php

namespace Dmeys\RequestLogger\Writers;

use Dmeys\RequestLogger\LogInfo;

interface Writer
{
    public function write(LogInfo $logInfo);
}
