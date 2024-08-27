<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    private function customRedirectTo($request, $guards)
    {
        if (!$request->expectsJson()) {
            if (in_array('admin', $guards)) {
                return route('admin.fake_get_login');
            }
            return route('web.get_login');
        }
    }
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            __('admin.unauthenticated'),
            $guards,
            $this->customRedirectTo($request, $guards)
        );
    }
}
