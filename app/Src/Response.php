<?php

namespace App\Src;

class Response
{
    public static function error(string $message = 'unknown error', int $code = 509, $payload = [])
    {
        return response([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'payload' => $payload
        ], $code);
    }

    public static function success(string $message = 'success', int $code = 200, $payload = [])
    {
        return response([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'payload' => $payload
        ], $code);
    }
}
