<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\ContactMessagesController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GuidesController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PlansController;
use App\Http\Controllers\Admin\RecordsController;
use App\Http\Controllers\Admin\RecordTypesController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialLinksController;
use App\Http\Controllers\Admin\SubscriptionsController;
use App\Http\Controllers\Admin\NewsletterEmailsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\EmployeesController;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\PublicTryingController;
use App\Http\Controllers\Web\UserAuthController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'XssSanitizer']], function () {


    Route::get('/mail/html', function () {
        return view('mail.testTryingMail');
    });

    Route::get('/', [LandingController::class, 'index'])->name('web.get_landing');
    Route::get('contact', [LandingController::class, 'contact'])->name('web.get_contact');
    Route::get('pricing', [LandingController::class, 'pricing'])->name('web.get_pricing');
    Route::post('contact', [LandingController::class, 'addContact'])->name('web.post_contact');
    Route::get('about', [LandingController::class, 'about'])->name('web.get_about');
    Route::get('about_us', [LandingController::class, 'aboutUs'])->name('web.get_about_us');
    Route::get('terms', [LandingController::class, 'terms'])->name('web.get_terms');
    Route::get('faqs', [LandingController::class, 'faqs'])->name('web.get_faqs');
    Route::get('guides', [LandingController::class, 'guides'])->name('web.get_guides');
    Route::get('profiles', [LandingController::class, 'profiles'])->name('web.get_profiles');
    Route::get('corporate/{company:username}/employees/{search?}', [LandingController::class, 'corporateEmployees'])->name('web.get_corporate_employees');

    Route::get('page/{name}', [LandingController::class, 'page'])->name('web.page');
    Route::get('account/{username}/pay', [AccountController::class, 'getPaymentData'])->name('account.pay');
    Route::get('/email/verify/{id}/{hash}', [UserAuthController::class, 'sign'])->name('verification.verify');
    Route::get('/company/{company}/{email}', [EmployeesController::class, 'sign'])->middleware('signed')->name('corporate.employee');
    Route::post('invite-by-email', [LandingController::class, 'inviteByEmail'])->name('web.invite_by_email');

    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('web.get_login');
        Route::post('login', [UserAuthController::class, 'login'])->name('web.post_login');
        Route::get('register/{company?}/{hash?}', [UserAuthController::class, 'showRegisterForm'])->name('web.get_register');
        Route::get('register/@{username?}', [UserAuthController::class, 'showRegisterFormForInvited'])->name('web.get_invite_link');
        Route::post('register/{company?}/{hash?}/{email?}', [UserAuthController::class, 'register'])->name('web.post_register');

        Route::get('/forgot-password', [UserAuthController::class, 'showResetRequestForm'])->name('password.request');
        Route::post('/forgot-password', [UserAuthController::class, 'submitResetRequest'])->name('password.email');
        Route::get('/reset-password/{token}', [UserAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [UserAuthController::class, 'submitReset'])->name('password.update');

        Route::get('/try-service/{username?}', [PublicTryingController::class, 'index'])->name('web.tryService');
        Route::get('/try-service/{user_id}/profile', [PublicTryingController::class, 'getTryingUserProfile'])->name('web.getTryingUserProfile');
        Route::get('/unsubscribe/{t_user_id}/{t_user_email}', [PublicTryingController::class, 'unsubscribe'])->name('web.unsubscribe');
        Route::post('/unsubscribe/{t_user_id}/{t_user_email}', [PublicTryingController::class, 'unsubscribeTryingUser'])->name('web.unsubscribe_trying_user');
    });

    Route::group(['middleware' => 'auth:user'], function () {

        Route::post('logout', [UserAuthController::class, 'logout'])->name('web.post_logout');
        Route::get('/assure_login', [UserAuthController::class, 'assureLogin'])->name('web.get_assure_login');

        Route::group(['middleware' => 'not_verified'], function () {
            Route::get('/email/verify', [UserAuthController::class, 'verify'])->name('verification.notice');
            Route::post('/email/verification-notification', [UserAuthController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
        });

        Route::get('users/delete-me', [LandingController::class, 'deleteTestingUsers'])->name('web.delete_testing_users');

        Route::group(['middleware' => 'verified'], function () {
            // routes that has to be from users with verified emails go here
        });

        Route::get('profile/edit', [ProfileController::class, 'index'])->name('profile.edit');
        Route::get('account/edit', [AccountController::class, 'index'])->name('account.edit');
        Route::put('account/edit', [AccountController::class, 'editDetails'])->name('account.update');
        Route::put('account/password', [AccountController::class, 'changePassword'])->name('account.password.change');
        Route::get('account/status', [AccountController::class, 'accountStatus'])->name('account.status');
        Route::put('account/status', [AccountController::class, 'toggleStatus'])->name('account.toggle');
        Route::get('account/validity', [AccountController::class, 'checkAccountValidity'])->name('account.validity');
        Route::group(['middleware' => 'not_employee'], function(){
            Route::get('account/danger', [AccountController::class, 'accountDangerZone'])->name('account.danger');
            Route::post('account/danger', [AccountController::class, 'deleteAccount'])->name('account.deleteAccount');
            Route::get('/download-invoice', [AccountController::class, 'downloadInvoice'])->name('download_invoice_pdf');
        });
        Route::post('account/payment/prepare/credi', [AccountController::class, 'preparePaymentCredi'])->name('account.preparePayment.crediMax');

        Route::post('account/arrange-employees', [EmployeesController::class, 'arrangeEmployees'])->name('account.arrange_employees');

        Route::get('account/employees', [EmployeesController::class, 'employees'])->name('account.employees');
        Route::get('account/employees/public', [EmployeesController::class, 'PublicEmployeesArrange'])->name('account.employees_public_arrange');
        Route::post('account/employees', [EmployeesController::class, 'addEmployee'])->name('account.addEmployee');
        Route::post('account/employees/excel', [EmployeesController::class, 'addEmployeeExcel'])->name('account.addEmployeeExcel');
        Route::delete('account/employees/{employee}', [EmployeesController::class, 'deleteEmployee'])->name('account.deleteEmployee');
        Route::post('account/employees/{employee}/resend', [EmployeesController::class, 'resendEmployeeInvitation'])->name('account.resendEmployeeInvitation');
        Route::post('account/employees/{employee}/being-public-reminder', [EmployeesController::class, 'remindEmployeeToGoPublic'])->name('account.remindEmployeeToGoPublic');
    });

    Route::name('admin.')->prefix('admin')->group(function () {

        Route::group(['middleware' => 'guest'], function () {
            Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('fake_get_login');
            Route::post('login', [AdminAuthController::class, 'fake_login'])->name('fake_post_login');
        });

        Route::group(['middleware' => 'guest'], function () {
            Route::get('/sec-signing-form', [AdminAuthController::class, 'showLoginForm'])->name('get_login');
            Route::post('/sec-signing-form', [AdminAuthController::class, 'login'])->name('post_login');
        });

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/assure_login', [AdminAuthController::class, 'assureLogin'])->name('get_assure_login');
        });


        Route::name('2fa.')->prefix('2fa')->middleware(['auth:admin', '2fa_not_valid'])->group(function () {
            Route::get('/qr', [AdminAuthController::class, 'twoFactorAuthQr'])->name('twoFactorAuthQr');
            Route::get('/otp/{secret?}', [AdminAuthController::class, 'twoFactorAuthOTP'])->name('twoFactorAuthOTP');
            Route::post('/2fa', [AdminAuthController::class, 'validateOTP'])->name('valdiate');
        });


        Route::group(['middleware' => ['auth:admin', 'ensure_session_key_for_admin', '2fa_valid']], function () {
            Route::post('logout', [AdminAuthController::class, 'logout'])->name('post_logout');
            Route::get('/', [DashboardController::class, 'index'])->name('get_dashboard');
            Route::get('profile', [DashboardController::class, 'profile'])->name('get_profile');
            Route::put('profile', [DashboardController::class, 'updateProfile'])->name('post_profile');
            Route::resource('countries', CountriesController::class);
            Route::delete('countries', [CountriesController::class, 'batchDestroy'])->name('countries.batch_destroy');
            Route::resource('roles', RolesController::class);
            Route::delete('roles', [RolesController::class, 'batchDestroy'])->name('roles.batch_destroy');
            Route::resource('admins', AdminsController::class);
            Route::delete('admins', [AdminsController::class, 'batchDestroy'])->name('admins.batch_destroy');
            Route::resource('users', UsersController::class);

            Route::get('login_attempts', [UsersController::class, 'loginAttempts'])->name('login_attempts');

            Route::get('trial_users', [UsersController::class, 'listTrialUsers'])->name('trial_users');
            Route::delete('trial_users/{user}', [UsersController::class, 'deleteTrialUser'])->name('delete_trial_users');

            Route::post('users/{user}/fake_payment', [UsersController::class, 'fakePayment'])->name('users.fake_payment');
            Route::post('users/{user_id}/impersonate', [UsersController::class, 'impersonate'])->name('users.impersonate');
            Route::delete('users', [UsersController::class, 'batchDestroy'])->name('users.batch_destroy');
            Route::resource('record_types', RecordTypesController::class);
            Route::delete('record_types', [RecordTypesController::class, 'batchDestroy'])->name('record_types.batch_destroy');
            Route::resource('records', RecordsController::class);
            Route::delete('records', [RecordsController::class, 'batchDestroy'])->name('records.batch_destroy');
            Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::resource('contact_messages', ContactMessagesController::class);
            Route::delete('contact_messages', [ContactMessagesController::class, 'batchDestroy'])->name('contact_messages.batch_destroy');
            Route::resource('pages', PagesController::class);
            Route::delete('pages', [PagesController::class, 'batchDestroy'])->name('pages.batch_destroy');
            Route::resource('social_links', SocialLinksController::class);
            Route::delete('social_links', [SocialLinksController::class, 'batchDestroy'])->name('social_links.batch_destroy');
            Route::resource('faqs', FaqController::class);
            Route::delete('faqs', [FaqController::class, 'batchDestroy'])->name('faqs.batch_destroy');
            Route::resource('guides', GuidesController::class);
            Route::delete('guides', [GuidesController::class, 'batchDestroy'])->name('guides.batch_destroy');
            Route::resource('faq', FaqController::class);
            Route::delete('faq', [FaqController::class, 'batchDestroy'])->name('faq.batch_destroy');
            Route::resource('guide', GuidesController::class);
            // Route::delete('guide', [GuidesController::class, 'batchDestroy'])->name('guides.batch_destroy');
            Route::resource('subscriptions', SubscriptionsController::class);
            Route::delete('subscriptions', [SubscriptionsController::class, 'batchDestroy'])->name('subscriptions.batch_destroy');
            Route::resource('newsletter_emails', NewsletterEmailsController::class)->except(['edit', 'update']);
            Route::delete('newsletter_emails', [NewsletterEmailsController::class, 'batchDestroy'])->name('newsletter_emails.batch_destroy');
            Route::resource('plans', PlansController::class);
            Route::delete('plans', [PlansController::class, 'batchDestroy'])->name('plans.batch_destroy');
        });
    });
    // this have to be the last route no matter what
    Route::get('@{username}', [ProfileController::class, 'publicProfile'])->name('web.profile');
    Route::get('errors/{error}', function ($error) {
        abort($error);
    });
    // Route::get('mailable/{user_id}', function ($user_id) {
    //     // $user = new stdClass;
    //     // $user->username = auth()->guard('admin')->user()->username;
    //     // $user->created_at = \App\Models\TryingUser::first()->created_at;
    //     // $user->email = "nahlaglal@gmail.com";
    //     // $inviter = new stdClass;
    //     // $inviter->username = "ahmed";
    //     // $inviter->name = "ahmed";
    //     // $invitee = new stdClass;
    //     // $invitee->id = 1;
    //     // $trialPeriod = 15;
    //     // $email = 'deveislam95@gmail.com';
    //     // $expires_at = \App\Models\TryingUser::first()->created_at->addDays(15);
    //     // $name = "ahmed";
    //     // $username="nahlagalal";
    //     // $remaining = 1;
    //     // $remaining_text = "month";
    //     // dd($expires_at);
    //     // $days_diff = 5;
    //     // $content = "Dew";
    //     // $inviter_name = "mona";
    //     $company_username = "Company";
    //     $company_name = "Company";
    //     $link = "https://www.fw.com";
    //     return view('mail.corporate_email_invitation', compact('company_username', 'company_name', 'link'));
    // });
});
