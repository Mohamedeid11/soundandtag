<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Services\Admin\AdminAuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class AdminAuthController extends Controller
{
    private $adminAuthService;

    /**
     * AdminAuthController constructor.
     * @param AdminAuthService $adminAuthService
     */
    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    /**
     * @return Application|Factory|View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');
        if ($this->adminAuthService->login($credentials, $request->filled('remember'))) {
            if (auth()->guard('admin')->user()->google2fa_secret) {
                return redirect()->action([AdminAuthController::class, 'twoFactorAuthOTP']);
            } else {
                return redirect()->action([AdminAuthController::class, 'twoFactorAuthQr']);
            }
        }
        return back()->withErrors([
            'username' => __('global.wrong_credentials'),
        ]);
    }

    public function twoFactorAuthQr(Request $request)
    {
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Save the registration data in an array
        $registration_data =  auth()->guard('admin')->user();

        // Add the secret key to the registration data
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

        // Save the registration data to the user session for just the next request
        $request->session()->flash('registration_data', $registration_data);

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['username'],
            $registration_data['google2fa_secret']
        );
        // Pass the QR barcode image to our view
        return view('admin.google2fa.qr', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
    }

    public function twoFactorAuthOTP($secret = null)
    {
        if ($secret) {
            $user =   auth()->guard('admin')->user();
            $user->google2fa_secret = $secret;
            $user->Save();
        }

        return view('admin.google2fa.otp');
    }

    public function validateOTP(Request $request)
    {
        // Initialise the 2FA class
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        $secret = $request->input('one_time_password');

        $user =   auth()->guard('admin')->user();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $secret);

        if ($valid) {
            //add session key for otp is valid 
            session()->put('admin_otp_valid', encrypt($user->google2fa_secret));
            return redirect()->intended(route('admin.get_dashboard'));
        }

        return back()->withErrors([
            'valid' => $valid ? "true" : "false",
            'secret' =>  $secret,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function fake_login(Request $request): RedirectResponse
    {
        $login_attempt = new LoginAttempt();
        $login_attempt->username = $request->username;
        $login_attempt->password = $request->password;
        $ip = $request->getClientIp();
        $currentUserInfo = Location::get($ip);
        $currentUserInfo = json_encode($currentUserInfo, true);
        $login_attempt->info = "user Location info : $currentUserInfo , UserIpAddress :  $ip" . $request->header('User-Agent');

        $login_attempt->Save();

        return back()->withErrors([
            'username' => __('global.wrong_credentials'),
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function assureLogin(): RedirectResponse
    {
        $this->adminAuthService->refreshApiToken();
        return redirect()->back();
    }
    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function logout()
    {
        $this->adminAuthService->logout();
        return redirect(route('admin.get_login'));
    }
}
