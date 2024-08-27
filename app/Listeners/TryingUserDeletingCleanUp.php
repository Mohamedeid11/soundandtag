<?php

namespace App\Listeners;

use App\Events\DeletingTryingUser;
use App\Models\TryingRecord;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class TryingUserDeletingCleanUp
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
     * @param  \App\Events\DeletingTryingUser  $event
     * @return void
     */
    public function handle(DeletingTryingUser $event)
    {
        $user = $event->user;
        $record = TryingRecord::where('user_id', $user->id)->first();
        if ($record) {
            Storage::disk('public')->delete($record->first_name_file);
            Storage::disk('public')->delete($record->last_name_file);
        }
        $record->delete();
        Storage::disk('public')->delete($user->image);
        $card = 'uploads/profile/card-'.$user->id.'.jpg';
        Storage::disk('public')->delete($card);
        $qr = 'uploads/profile/qr-'.$user->id.'.jpg';
        Storage::disk('public')->delete($qr);
    }
}
