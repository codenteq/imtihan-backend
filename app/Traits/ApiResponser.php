<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    /**
     * Build a successful response
     */
    protected function successResponse($data, int $code = 200): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Build an error response
     */
    protected function errorResponse(array|string $message, int $code): JsonResponse
    {
        return response()->json($message, $code);
    }
}
