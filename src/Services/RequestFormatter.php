<?php

namespace Dmeys\RequestLogger\Services;

use Illuminate\Http\Request;

class RequestFormatter
{
    /**
     * @var Concealer $concealer
     */
    protected $concealer;

    public function __construct(Concealer $concealer)
    {
        $this->concealer = $concealer;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getHeaders(Request $request): array
    {
        $headers = $request->headers->all();
        foreach ($headers as $header => $values) {
            $headers[$header] = implode(',', $values);
        }

        return $this->concealer->hide($headers, config('request-logger.hide_fields.request.headers'));
    }

    /**
     * @param Request $request
     * @return array|false|resource|string|null
     */
    public function getContent(Request $request)
    {
        if ($request->isJson()) {
            $request_content = json_decode($request->getContent(), true);
            if (is_array($request_content)) {
                $request_content = $this->concealer->hide($request_content, config('request-logger.hide_fields.request.content'));
            }
        } else {
            $request_content = $request->getContent();
        }

        return $request_content;
    }
}
