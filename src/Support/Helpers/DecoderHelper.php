<?php

namespace Dmeys\RequestLogger\Support\Helpers;

final class DecoderHelper
{
    /**
     * @param mixed $json
     * @param mixed $default
     * @return array|mixed
     */
    public static function decodeJson($json, $default = [])
    {
        $value = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $default;
        }

        return $value ?? $default;
    }
}