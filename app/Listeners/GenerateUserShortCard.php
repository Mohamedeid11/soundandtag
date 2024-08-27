<?php

namespace App\Listeners;

use App\Events\UserStateChanged;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use AtmCode\ArPhpLaravel\ArPhpLaravel;

class GenerateUserShortCard implements ShouldQueue
{
    public $queue = 'default';
    private $userRepository;

    /**
     * Create the event listener.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  UserStateChanged  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $this->userRepository->all(true)->public()->where(['id' => $event->user->id])->first();
        // Log::debug($user);
        if ($user) {
            // Log::debug("ShortCard " . $user->name);

            $width = 800; // your max width
            $height = 450; // your max height

            $image = Image::make(storage_path("app/public/defaults/card_two_05_01.png"));

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $mask = Image::canvas(250, 250);

            $mask->circle(250, 125, 125, function ($draw) {
                $draw->background('FFFFFF');
            });

            $user_image = ($user->image) ? Image::make(storage_path('app/public/' . $user->image))->fit(420) : Image::make(storage_path('app/public/defaults/default-user.png'))->fit(420);

            $user_image->mask($mask, false);

            $image->insert($user_image, 'center-center', -22, 0);

            $image->save(storage_path('app/public/uploads/profile/short-card-' . $user->username . '.png'));
            Log::debug('User shortCard Changed: .' . $event->user->name);
        } else {
            Log::debug("USER NOT PUBLIC");
        }
    }
}
