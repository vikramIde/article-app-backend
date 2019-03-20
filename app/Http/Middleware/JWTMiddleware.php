<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware as Middleware;

class JWTMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (! $token = $this->auth()->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 4010);
        }

        try {
            $user = $this->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'Token expired. Please log in again.', 4011, [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'Token invalid. Please log in again.', 4012, [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'Invlaid user. Please try again later.', 4014);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }


    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        // return $response ?: $this->response->json(['error' => $error], $status);
        return $response ?: $this->response->json(['error' => ['type' => 'auth_error', 'details' => $event], 'data' => [], 'display_message' => $error, 'status_code' => $status]);
    }



    public function authenticate($token = false)
    {
        $payLoad = JWTAuth::getPayload($token)->get();

        if (! User::find($payLoad['id'])) {
            return false;
        }
        // echo "string";
        return $payLoad;
    }
}
