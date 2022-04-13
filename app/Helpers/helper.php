<?php

use App\Http\Resources\ApiResource;

/**
 * Return s API response in a standard format
 *
 * @return ApiResource
 */
function apiResponse($data, int $status_code = 200, $errors = [], string $message = '', array $additional = [])
{
    if ($status_code >= 400) {
        return (new ApiResource($data))->additional(
            array_merge([
                'success' => false,
                'code' => $status_code,
                'errors' => $errors,
                'message' => $message,
            ], $additional)
        )->response()
        ->setStatusCode($status_code);
    }

    return (new ApiResource(collect($data)))->additional(
        array_merge([
            'success' => true,
            'code' => $status_code,
            'message' => $message,
        ], $additional)
    )->response()
    ->setStatusCode($status_code);
}
