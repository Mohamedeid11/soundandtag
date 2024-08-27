<?php

namespace App\Listeners;

use App\Mail\InvitedUserRegistered;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PerformUserRegisteredActions
{
    private $userRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  IlluminateAuthEventsRegistered  $event
     * @return void
     */
    public function handle($event)
    {
        $inviter = $this->userRepository->get($event->user->invited_by);
        if ($inviter) {
            Mail::to($inviter->email)->send(new InvitedUserRegistered($event->user, $inviter));
        }
    }
}
