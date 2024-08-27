<?php

namespace App\Listeners;

use App\Repositories\Interfaces\TryingRecordRepositoryInterface;
use App\Repositories\Interfaces\TryingUserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\NewTryingUser;
use App\Mail\EmailTrialUserInviter;
use App\Models\TryingUser;
use App\Models\User;
use AtmCode\ArPhpLaravel\ArPhpLaravel;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipStream\File;

// use Johntaa\Arabic\I18N_Arabic;

class GenerateTryingUserCard implements ShouldQueue
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
     * @param  NewTryingUser  $event
     * @return void
     */
    public function handle($event)
    {
        $record = $this->tryingRecordRepository->all(true)->where(['id' => $event->record->id])->first(['first_name', 'last_name', 'user_id']);
        $user = $this->tryingUserRepository->all(true)->where(['id' => $record->user_id])->first(['id', 'email', 'image', 'user_id', 'created_at']);
        if ($record) {
            $bold = function ($font) {
                $font->file(public_path('css/fonts/FuturaPTLight.woff'));
                $font->align('center');
                $font->color('#4f4c4d');
                $font->size(20);
            };

            $email_characters_number = Str::length($user->email);
            $email_font_size =  22 / $email_characters_number * 30; // calculate ratio to get suitable font size
            $email_font_size =  $email_font_size > 30 ? 30 : $email_font_size;

            $normal_for_email = function ($font) use ($email_font_size) {
                $font->file(public_path('css/fonts/FuturaPTMedium.woff'));
                $font->align('center');
                $font->valign('middle');
                $font->color('#4f4c4d');
                $font->size($email_font_size);
            };


            $normal = function ($font) {
                $font->file(public_path('css/fonts/FuturaPTMedium.woff'));
                $font->align('center');
                $font->valign('middle');
                $font->color('#4f4c4d');
                $font->size(30);
            };

            $arabic = function ($font) {
                $font->file(public_path('css/fonts/NotoSansArabic-VariableFont_wdth,wght.ttf'));
                $font->align('center');
                $font->valign('right');
                $font->color('#4f4c4d');
                $font->size(22);
            };

            $image = Image::make(storage_path("app/public/defaults/card_two_05_03.png"));
            $image->resize(800, 450, function ($constraint) {
                $constraint->aspectRatio();
            });

            $user_image = ($user->image) ? Image::make(storage_path('app/public/' . $user->image))->fit(140) : Image::make(storage_path('app/public/defaults/default-user.png'))->fit(140);
            // $logo = Image::make(storage_path('app/public/defaults/default-logo.png'))->resize(300, 100);
            $mask = Image::canvas(250, 250);

            $mask->circle(250, 125, 125, function ($draw) {
                $draw->background('FFFFFF');
            });

            $user_image->mask($mask, true);
            // $image->insert($user_image, 'top-left', 60, 100);
            $image->insert($user_image, 'top-left', 36, 34);
            // $image->circle(275, 185, 225, function ($draw) {
            //     $draw->border(8, '52c7ef');
            // });
            // $image->insert($logo, 'top-left', 321, 30);
            $first_name = $record->first_name;
            $last_name = $record->last_name;
            $font_1 = $normal;
            $font_2 = $normal;
            if (preg_match('/\p{Arabic}/u', $first_name)) {
                $first_name = ArPhpLaravel::utf8Glyphs($first_name);
                $font_1 = $arabic;
            }
            if (preg_match('/\p{Arabic}/u', $last_name)) {
                $last_name = ArPhpLaravel::utf8Glyphs($last_name);
                $font_2 = $arabic;
            }

            $image->text(Str::limit($first_name, 30),  400, 175, $font_1);
            $image->text($last_name, 400, 220, $font_2);
            $image->text($user->email,  400, 265, $normal_for_email);

            QrCode::format('png')
            ->merge(storage_path("app/public/defaults/Logo-For-Bardcod.png"), .8, true)
            ->size(90)->errorCorrection('H')
            ->generate(route('web.getTryingUserProfile', ['user_id'=>$record->user_id]),
            storage_path('app/public/uploads/profile/qr-'.$record->user_id.'.jpg'));

            $qr = Image::make(storage_path('app/public/uploads/profile/qr-'.$record->user_id.'.jpg'));
            $image->insert($qr, 'bottom-left', 61, 120);

            $image->text(route('web.getTryingUserProfile', ['user_id' => $user->id]), 400, 400, $normal);
            $image->save(storage_path('app/public/uploads/profile/card-' . $user->id . '.jpg'));

            if ($user->user_id) {
                $inviter = User::find($user->user_id);
                Mail::to($inviter->email)->queue(new EmailTrialUserInviter($inviter, $user));
            }
        }
    }
}
