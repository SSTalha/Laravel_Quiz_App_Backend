<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function successResponse($message, $data = [], $statuscode = null)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $statuscode);
    }

    public static function errorResponse($message, $statuscode)
    {
        return response()->json([
            'message' => $message,
        ], $statuscode);
    }
}
