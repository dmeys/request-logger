<?php

namespace Dmeys\RequestLogger;

use DateTime;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class LogInfo
{
    /** @var Request $request */
    public $request;

    /** @var Response $response */
    public $response;

    /** @var DateTime $loggerStart */
    public $loggerStart;

    /** @var int $durationMs */
    public $durationMs;

    /** @var float $memoryUsage */
    public $memoryUsage;

    /** @var string $fingerprint */
    public $fingerprint;

    /** @var array $customFields */
    public $customFields;

    public function __construct(
        Request $request,
        Response $response,
        DateTime $loggerStart,
        int $durationMs,
        float $memoryUsage,
        string $fingerprint,
        array $customFields
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->loggerStart = $loggerStart;
        $this->durationMs = $durationMs;
        $this->memoryUsage = $memoryUsage;
        $this->fingerprint = $fingerprint;
        $this->customFields = $customFields;
    }
}
