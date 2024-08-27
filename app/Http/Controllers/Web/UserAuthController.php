<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\RegistrationRequest;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\TryingUser;
use App\Models\User;
use App\Services\Web\AuthService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserAuthController extends BaseWebController
{
    private $authService;
    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }
    public function showLoginForm()
    {
        return view('web.auth.login');
    }
    public function assureLogin()
    {
        Session::put('web_auth_token', Auth::guard('user')->user()->createToken('token')->plainTextToken);
        return redirect()->back();
    }
    public function showRegisterForm($username = null)
    {
        if ($username) {
            $username = str::remove('@', $username);
            $user = $this->authService->getUser($username);
        } else {
            $user = null;
        }

        $company = request() ? request()->input('company') : null;
        $hash = request() ? request()->input('hash') : null;
        $email = request() ? request()->input('email') : null;
        if ($company && $hash && $email) {

            if(User::where('email', $email)->count() > 0){
                session()->flash('error', __('global.email_registered'));
                return redirect(route('web.get_landing'))->withInput();
            }

            $company = User::where(['id' => $company])->first();

            if ($company) {
                $hash = hash_equals($hash, sha1($company->email)) ? $hash : null;
                if (!$hash) {
                    session()->flash('error', __('global.expired_link_ask_to_resend'));
                    return redirect(route('web.get_register'))->withInput();
                }
            }
        }
        $trialPeriod = Setting::where(['name' => 'trial_days'])->first()->value;
        return view('web.auth.register', compact('company', 'hash', 'trialPeriod', 'user', 'email'));
    }
    public function showRegisterFormForInvited($username)
    {
        $user = $this->authService->getUser($username);
        if (!$user) {
            return redirect(route('web.get_register'));
        }
        $trialPeriod = Setting::where(['name' => 'trial_days'])->first()->value;
        $company = null;
        $hash = null;
        $email = null;
        return view('web.auth.register', compact('company', 'hash', 'email', 'trialPeriod', 'user'));
    }
    public function register(RegistrationRequest $request)
    {
        $tryingUser = TryingUser::where('email', $request->email)->first();
        $tryingUser ? $tryingUser->delete() : "";
        $reg = $this->authService->register($request->only(['account_type', 'username', 'email', 'password', 'records']), $request->input('plan'));
        if ($reg) {
            if($request->account_type == 'employee'){
                session()->flash('success', __('global.created_complete_registration'));
            } else {
                session()->flash('success', __('global.created_and_email_sent'));
            }
            return redirect(route('web.get_login'));
        }
        return back()->withInput();
    }
    public function getPlansByAccountType($account_type)
    {
        return json_encode(Plan::where('account_type', $account_type)->get());
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), array(
            'g-recaptcha-response' => 'sometimes|required',
        ));
        if($validator->fails()){
            return back()->withErrors($validator);
        }

        $credentials = $request->only('username', 'password');
        $remember = $request->filled('remember');
        if (Auth::guard('user')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Session::put('web_auth_token', Auth::guard('user')->user()->createToken('token')->plainTextToken);
            return redirect()->intended(route('web.get_landing'));
        }
        return back()->withErrors([
            'username' => __('global.wrong_credentials'),
        ])->withInput();
    }

    /**
     * @param Request $request
     * @return array
     * @unauthenticated
     * @bodyParam username string the username
     * @bodyParam password string the password
     */
    public function apiLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $remember = $request->filled('remember');
        if (Auth::guard('user')->attempt($credentials, $remember)) {
            return [
                'status' => 1,
                'token' => Auth::guard('user')->user()->createToken('token')->plainTextToken
            ];
        }
        return [
            'status' => 0,
            'username' => __('global.wrong_credentials'),
        ];
    }
    public function logout(Request $request)
    {
        Auth::guard('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('web.get_landing'));
    }
    public function showResetRequestForm()
    {
        return view('web.auth.forgot-password');
    }
    public function submitResetRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)])->withInput();
    }
    public function showResetForm($token)
    {
        return view('web.auth.reset-password', ['token' => $token]);
    }
    public function submitReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('web.get_login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]])->withInput();
    }
    public function verify()
    {
        return view('web.auth.verify-email-notice');
    }
    public function resend(Request $request)
    {
        // $request->user()->sendEmailVerificationNotification();
        $user = auth()->guard('user')->user();
        $this->authService->sendEmailVerification($user);
        return back()->with('success', __('global.verification_link_sent'));
    }
    public function sign($id, string $hash)
    {
        $user = User::find($id);
        $user_id = $user->id;
        if (!hash_equals(
            (string) $user_id,
            (string) $user->getKey()
        )) {
            abort(403);
        }

        if (!hash_equals(
            $hash,
            sha1($user->getEmailForVerification())
        )) {
            abort(403);
        }
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }
        session()->flash('success', __('global.email_confirm_and_use_full_functionality'));
        return redirect(route('web.get_landing'));
    }
}
