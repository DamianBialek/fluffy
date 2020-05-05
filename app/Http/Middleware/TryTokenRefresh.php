<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TryTokenRefresh extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
//        $newToken = $this->tryRefresh();
        $newToken = false;
        if ($newToken) {
            // in case there's anything further to be done with the token
            // we want that code to have a valid one
            $request->headers->set('Authorization', 'Bearer ' . $newToken);
        }

        $response = $next($request);

        if ($newToken) {
            // send new token back to frontend
            $response->headers->set('Authorization', $newToken);
        }

        return $response;
    }

    // Refresh the token
    protected function tryRefresh()
    {
        try {
            $token = $this->auth->parseToken()->refresh();
            return $token;
        } catch (JWTException $e) {
            // token expired? force logout on frontend
            throw new AuthenticationException();
        }

        return null;
    }
}
