<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception): JsonResponse
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    private function handleApiException($request, Throwable $exception): JsonResponse
    {
        $statusCode = 500;
        $message = 'Internal Server Error';

        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }

        switch ($statusCode) {
            case 400:
                $message = 'Bad Request';
                break;
            case 401:
                $message = 'Unauthorized';
                break;
            case 403:
                $message = 'Forbidden';
                break;
            case 404:
                $message = 'Resource Not Found';
                break;
            case 405:
                $message = 'Method Not Allowed';
                break;
            case 422:
                $message = $exception->original['message'] ?? 'Unprocessable Entity';
                break;
            case 429:
                $message = 'Too Many Requests';
                break;
            case 500:
                $message = 'Internal Server Error';
                break;
            case 503:
                $message = 'Service Unavailable';
                break;
            default:
                $message = $exception->getMessage();
                break;
        }

        $response = [
            'error' => [
                'code' => $statusCode,
                'message' => $message,
            ]
        ];

        if (config('app.debug')) {
            $response['error']['trace'] = $exception->getTrace();
            $response['error']['file'] = $exception->getFile();
            $response['error']['line'] = $exception->getLine();
        }

        return response()->json($response, $statusCode);
    }
}
