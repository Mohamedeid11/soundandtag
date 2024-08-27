<?php

namespace App\Http\Middleware;

use App\Services\Admin\AdminAuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class EnsureSessionKeyForAdminIsSet
{
    protected $authAdmin;

    public function __construct(AdminAuthService $authAdmin)
    {
        $this->authAdmin = $authAdmin;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check() && Route::is('admin.*')) {
            if (!Session::has('admin_auth_token')) {
                $this->authAdmin->refreshApiToken();
            }
        }

        return $next($request);
    }
}
