<?php

namespace App\Listeners;

use App\Events\UserStateChanged;
use App\Models\User;
use App\Repositories\Interfaces\TryingRecordRepositoryInterface;
use App\Repositories\Interfaces\TryingUserRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use AtmCode\ArPhpLaravel\ArPhpLaravel;

class GenerateTryingUserShortCard implements ShouldQueue
{
    public $queue = 'default';
    private $tryingUserRepository;
    private $tryingRecordRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TryingUserRepositoryInterface $tryingUserRepository, TryingRecordRepositoryInterface $tryingRecordRepository)
    {
        $this->tryingUserRepository = $tryingUserRepository;
        $this->tryingRecordRepository = $tryingRecordRepository;
    }

    /**
     * Handle the event.
     *
     * @param  UserStateChanged  $event
     * @return void
     */
    public function handle($event)
    {
        $record = $this->tryingRecordRepository->all(true)->where(['id'=>$event->record->id])->first(['first_name', 'last_name', 'user_id']);
        $user = $this->tryingUserRepository->all(true)->where(['id'=>$record->user_id])->first(['id', 'email', 'image', 'created_at']);
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

            $user_image->mask($mask, true);

            $image->insert($user_image, 'center-center', -22, 0);

            $image->save(storage_path('app/public/uploads/profile/short-card-' . $user->id . '.png'));
        }
    }
}
