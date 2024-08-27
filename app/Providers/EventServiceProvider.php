<?php

namespace App\Providers;

use App\Events\DeletingTryingUser;
use App\Events\DeletingUser;
use App\Events\NewTryingUser;
use App\Events\UserRecordsChanged;
use App\Events\UserStateChanged;
use App\Listeners\GenerateTryingUserCard;
// use App\Listeners\GenerateTryingUserQR;
use App\Listeners\GenerateTryingUserShortCard;
use App\Listeners\GenerateUserCard;
use App\Listeners\GenerateUserShortCard;
// use App\Listeners\GenerateUserQR;
use App\Listeners\PerformUserRegisteredActions;
use App\Listeners\TryingUserDeletingCleanUp;
use App\Listeners\WelcomeUser;
use App\Listeners\UserDeletingCleanUp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            // SendEmailVerificationNotification::class,
            PerformUserRegisteredActions::class
        ],
        UserStateChanged::class => [
            GenerateUserCard::class,
            GenerateUserShortCard::class,
            // GenerateUserQR::class
        ],
        UserRecordsChanged::class => [
            GenerateUserCard::class,
            GenerateUserShortCard::class,
            // GenerateUserQR::class
        ],
        DeletingUser::class => [
            UserDeletingCleanUp::class
        ],
        DeletingTryingUser::class => [
            TryingUserDeletingCleanUp::class
        ],
        Verified::class => [
            WelcomeUser::class
        ],
        NewTryingUser::class => [
            GenerateTryingUserCard::class,
            GenerateTryingUserShortCard::class,
            // GenerateTryingUserQR::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
