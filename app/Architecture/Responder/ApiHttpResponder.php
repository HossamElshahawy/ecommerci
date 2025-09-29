<?php

namespace App\Architecture\Responder;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiHttpResponder implements IApiHttpResponder
{
    public function sendSuccess(array $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    public function sendError(string $message = null,int $code = 404, array $data = []): JsonResponse
    {
        // TODO :: change according exception type
        if(is_null($message))
        {
            $message = trans('messages.error_occurred');
        }

        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
