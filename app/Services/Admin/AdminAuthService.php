<?php

namespace App\Services\Admin;

class AdminAuthService
{

    public function refreshApiToken()
    {
        session()->put('admin_auth_token', auth()->guard('admin')->user()->createToken('token')->plainTextToken);
    }

    public function login(array $credentials, bool $remember): bool
    {
        if (auth()->guard('admin')->attempt($credentials, $remember)) {
            request()->session()->regenerate();
            session()->put('admin_auth_token', auth()->guard('admin')->user()->createToken('token')->plainTextToken);
            return true;
        }
        return false;
    }

    public function logout()
    {
        $tokenId = explode('|', session()->get('admin_auth_token'))[0];
        auth()->guard('admin')->user()->tokens()->where('id', $tokenId)->delete();
        auth()->guard('admin')->logout();
        session()->remove('admin_auth_token');
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
