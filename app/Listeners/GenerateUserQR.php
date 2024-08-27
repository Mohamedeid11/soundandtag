<?php

namespace App\Listeners;

use App\Events\UserStateChanged;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateUserQR implements ShouldQueue
{
    use InteractsWithQueue;
    public $queue = 'default';
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
     * @param  UserStateChanged  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $this->userRepository->all(true)->public()->where(['id'=>$event->user->id])->first();
        if($user){
            // QrCode::size(200)->generate(route('web.profile', ['username'=>$user->username]), storage_path('app/public/uploads/profile/qr-'.$user->username.'.jpg'));
            QrCode::format('png')
                ->merge(storage_path("app/public/defaults/Logo-For-Bardcod.png"), .8, true)
                ->size(200)->errorCorrection('H')
                ->generate(route('web.profile', ['username'=>$user->username]), storage_path('app/public/uploads/profile/qr-'.$user->username.'.jpg'));
            $image = Image::canvas(300, 300, "#ffffff");
            $qr = Image::make(storage_path('app/public/uploads/profile/qr-'.$user->username.'.jpg'));
            $image->insert($qr, "top-left", 50, 50);
            $image->save(storage_path('app/public/uploads/profile/qr-'.$user->username.'.jpg'));
        }
    }
}
