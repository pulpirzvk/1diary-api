<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class Response
{
    public static function success(string $message = null, int $code = 200): JsonResponse
    {
        $data = [
            'status' => true,
        ];

        if ($message) {
            $data['message'] = $message;
        }

        return response()->json($data, $code);
    }

    public static function fail(string $message = null, int $code = 200): JsonResponse
    {
        $data = [
            'status' => false,
        ];

        if ($message) {
            $data['message'] = $message;
        }

        return response()->json($data, $code);
    }
}


