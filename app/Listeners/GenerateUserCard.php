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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipStream\File;

class GenerateUserCard implements ShouldQueue
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
            Log::debug($user->name);

            $bold = function ($font) {
                $font->file(public_path('css/fonts/FuturaPTHeavy.woff'));
                $font->align('center');
                $font->color('#4f4c4d');
                $font->size(30);
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

            $name = $user->full_name_updated;

            if (preg_match('/\p{Arabic}/u', $name)) {
                $name = ArPhpLaravel::utf8Glyphs($name);
                $normal = $arabic;
            }

            $width = 800; // your max width
            $height = 450; // your max height

            if ($user->company_id) {
                $image = Image::make(storage_path("app/public/defaults/card_two_05_02.png"));
            } else {
                $image = Image::make(storage_path("app/public/defaults/card_two_05_03.png"));
            }

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });


            if ($user->company_id) {

                $mask = Image::canvas(250, 250);

                $mask->circle(250, 125, 125, function ($draw) {
                    $draw->background('ffffff');
                });

                $company = User::find($user->company_id);
                $company_image = ($company->image) ?
                    Image::make(storage_path('app/public/' . $company->image))->fit(140) :
                    Image::make(storage_path('app/public/defaults/default-corporate.png'))->fit(140);
                $company_image->mask($mask, false);
                $image->insert($company_image, 'top-right', 36, 34);

                $user_image = ($user->image) ?
                    Image::make(storage_path('app/public/' . $user->image))->fit(140) :
                    Image::make(storage_path('app/public/defaults/default-user.png'))->fit(140);
                $user_image->mask($mask, false);
                $image->insert($user_image, 'top-left', 36, 34);

                $image->text($name,  400, 200, $bold);
                $image->text($user->email,  400, 245, $normal_for_email);
                $image->text($user->country->name,  400, 290, $normal);
                $image->text($user->position,  400, 335, $normal);

            } else {

                $mask = Image::canvas(250, 250);

                $mask->circle(190, 120, 115, function ($draw) {
                    $draw->background('FFFFFF');
                });

                $user_image = ($user->image) ?
                    Image::make(storage_path('app/public/' . $user->image))->fit(190) :
                    Image::make(storage_path('app/public/defaults/default-user.png'))->fit(190);

                $user_image->mask($mask, true);
                $image->insert($user_image, 'top-left', 15, 15);
                $image->text(Str::ucfirst($name),  400, 175, $bold);
                $image->text($user->email,  400, 220, $normal_for_email);
                $image->text($user->country->name,  400, 265, $normal);

                if ($user->phone) {

                    $image->text($user->phone,  400, 310, $normal);
                }

                if ($user->account_type == 'corporate') {
                    foreach ($user->employees as $employee) {
                        $employee = User::where('email', $employee->email)->where('company_id', $employee->user_id)->first();
                        if ($employee) {
                            event(new UserStateChanged($employee));
                        }
                    }
                }
            }


            // generate qr code
            QrCode::format('png')
                ->merge(storage_path("app/public/defaults/Logo-For-Bardcod.png"), .8, true)
                ->size(90)->errorCorrection('H')
                ->generate(route('web.profile', ['username'=>$user->username]), storage_path('app/public/uploads/profile/qr-'.$user->username.'.jpg'));

            $qr = Image::make(storage_path('app/public/uploads/profile/qr-'.$user->username.'.jpg'));
            $image->insert($qr, 'bottom-left', 61, 120);


            $image->text(route('web.profile', ['username' => $user->username]),  400, 400, $normal);
            $image->save(storage_path('app/public/uploads/profile/card-' . $user->username . '.jpg'));
            Log::debug('User Changed: .' . $event->user->name);
        } else {
            Log::debug("USER NOT PUBLIC");
        }
    }
}
