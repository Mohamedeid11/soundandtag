<?php

namespace App\Listeners;

use App\Events\DeletingUser;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserDeletingCleanUp
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DeletingUser  $event
     * @return void
     */
    public function handle(DeletingUser $event)
    {
        $user = $event->user;
        if (count($user->records) > 0 ) {
            Storage::disk('public')->delete(...$user->records->pluck('file_path'));
        }

        $user->records()->delete();
        $user->record_types()->delete();
        $user->hits()->delete();
        if($user->account_type != 'employee'){
            $user->user_plans()->delete();
        }
        if($user->account_type == 'corporate'){
            foreach($user->employees as $employee){
                $employee->delete();
            }
        }
        Storage::disk('public')->delete($user->image);
        $card = 'uploads/profile/card-'.$user->username.'.jpg';
        Storage::disk('public')->delete($card);
        $short_card = 'uploads/profile/short-card-'.$user->username.'.png';
        Storage::disk('public')->delete($short_card);
        $qr = 'uploads/profile/qr-'.$user->username.'.jpg';
        Storage::disk('public')->delete($qr);
    }
}
