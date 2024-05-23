<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateApi extends Middleware
{
    /**
     *  Custom API auth via token from field {api_token}
     *
     *  [Override]
     *
     * @param $request
     * @param array $guards
     * @return true|void
     * @throws AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        $token = $request->query('api_token');

        if (empty($token)) {
            $token = $request->input('api_token');
        }

        if (empty($token)) {
            $token = $request->bearerToken();
        }

        if (trim($token) === (string) config('api_tokens')['auth_token']) {
            return true;
        }

        $this->unauthenticated($request, $guards);
    }
}
