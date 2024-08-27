<?php

use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialLinksController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\PublicTryingController;
use App\Http\Controllers\Web\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('get_token', [UserAuthController::class, 'apiLogin'])->name('api.get_token');
Route::get('try-service/record_types', [PublicTryingController::class, 'recordTypes'])->name('api.trying.recordTypes');
Route::post('/try-services/{username?}', [PublicTryingController::class, 'tryingUser'])->name('api.tryingUser');
Route::get('/try-service/{user_id}/profile', [PublicTryingController::class, 'tryingUserProfile'])->name('api.tryingUserProfile');
Route::get('/plans/{account_type}', [UserAuthController::class, 'getPlansByAccountType'])->name('api.getPlansByType');
Route::group(['middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {
    Route::name('admin.')->prefix('admin')->middleware('auth:admin_api')->group(function () {
        Route::post('users/toggle_featured/{user_id}', [UsersController::class, 'toggleFeatured'])->name('api.users.toggle_featured');
        Route::post('countries/toggle_active/{country_id}', [CountriesController::class, 'toggle_active'])->name('api.countries.toggle_active');
        Route::post('users/toggle_active/{user_id}', [UsersController::class, 'toggle_active'])->name('api.users.toggle_active');
        Route::post('social_links/toggle_active/{social_link_id}', [SocialLinksController::class, 'toggle_active'])->name('api.social_links.toggle_active');
        Route::post('plans/toggle_active/{plan_id}', [\App\Http\Controllers\Admin\PlansController::class, 'toggle_active'])->name('api.plans.toggle_active');
        Route::put('settings/{setting}', [SettingsController::class, 'update'])->name('settings.update');
    });
    Route::name('web.api.')->middleware('auth:user_api')->group(function () {
        Route::get('profile/details', [ProfileController::class, 'details'])->name('profile.details');
        Route::post('profile/details', [ProfileController::class, 'saveRecord'])->name('profile.save_record');
        Route::post('account/payment/prepare', [AccountController::class, 'preparePayment'])->name('account.preparePayment');
    });
});
