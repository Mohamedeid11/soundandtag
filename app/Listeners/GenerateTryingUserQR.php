<?php

namespace App\Listeners;

use App\Repositories\Interfaces\TryingRecordRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Intervention\Image\Facades\Image;
use App\Events\NewTryingUser;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateTryingUserQR implements ShouldQueue
{
    use InteractsWithQueue;
    public $queue = 'default';
    private $tryingRecordRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TryingRecordRepositoryInterface $tryingRecordRepository)
    {
        $this->tryingRecordRepository = $tryingRecordRepository;
    }

    /**
     * Handle the event.
     *
     * @param  NewTryingUser  $event
     * @return void
     */
    public function handle($event)
    {
        $record = $this->tryingRecordRepository->all(true)->where(['id'=>$event->record->id])->first(['user_id']);
        if($record){
            // QrCode::size(200)->generate(route('web.getTryingUserProfile', ['user_id'=>$record->user_id]), storage_path('app/public/uploads/profile/qr-'.$record->user_id.'.jpg'));
            QrCode::format('png')
                ->merge(storage_path("app/public/defaults/Logo-For-Bardcod.png"), .8, true)
                ->size(200)->errorCorrection('H')
                ->generate(route('web.getTryingUserProfile', ['user_id'=>$record->user_id]), storage_path('app/public/uploads/profile/qr-'.$record->user_id.'.jpg'));
            $image = Image::canvas(300, 300, "#ffffff");
            $qr = Image::make(storage_path('app/public/uploads/profile/qr-'.$record->user_id.'.jpg'));
            $image->insert($qr, "top-left", 50, 50);
            $image->save(storage_path('app/public/uploads/profile/qr-'.$record->user_id.'.jpg'));
        }
    }
}
