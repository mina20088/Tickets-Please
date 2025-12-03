<?php

namespace App\traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function success(string $message , array $data): JsonResponse
    {
        return response()->json([
           'message' => $message,
           'status' => 'success',
           'data' => $data
        ]);
    }

    public function error(string $message, int $status = 500): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => 'error'
        ], $status);
    }


    public function ok(string $message, array $data = []): JsonResponse
    {
        if(isset($data)){
           return $this->success($message,$data);
        }
        return $this->success($message, 200);
    }
}
