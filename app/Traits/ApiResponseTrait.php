<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    public function apiResponse($data = null, $status = null, $message = null): JsonResponse
    {
        $array = [
            'data' => $data,
            'status' => $status ?? Response::HTTP_OK,
            'message' => $message,
        ];

        return response()->json($array, $status); // Use the provided status code
    }

    public function notFoundResponse($message = 'Resource not found'): JsonResponse
    {
        return $this->apiResponse(null, Response::HTTP_NOT_FOUND, $message);
    }
}
