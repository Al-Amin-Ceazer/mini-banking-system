<?php

namespace App\Http\Middleware;


use App\Http\Resources\API\ErrorResponse;
use Closure;
use Illuminate\Http\Response;

class CheckAcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->expectsJson()) {
            $payload = [
                'code'         => Response::HTTP_NOT_ACCEPTABLE,
                'app_message'  => 'api can only respond with application/json',
                'user_message' => 'API does not support requested response type',
            ];

            return new ErrorResponse('auth', $payload, Response::HTTP_NOT_ACCEPTABLE);
        }

        return $next($request);
    }
}
