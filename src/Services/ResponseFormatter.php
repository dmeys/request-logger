<?php

namespace Dmeys\RequestLogger\Services;

use Dmeys\RequestLogger\Support\Helpers\DecoderHelper;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
    public static $defaultContent = [
        'message' => 'Not supported response type'
    ];

    /**
     * @var Concealer $concealer
     */
    protected $concealer;

    public function __construct(Concealer $concealer)
    {
        $this->concealer = $concealer;
    }

    /**
     * @param Response $response
     * @return array|mixed
     */
    public function getContent(Response $response)
    {
        return DecoderHelper::decodeJson($response->getContent(), self::$defaultContent);
    }

    /**
     * @param Response $response
     * @return array
     */
    public function getHeaders(Response $response): array
    {
        $headers = $response->headers->all();
        foreach ($headers as $header => $values) {
            $headers[$header] = implode(',', $values);
        }
        return $headers;
    }
}
