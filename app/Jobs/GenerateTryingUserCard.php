<?php

namespace App\Jobs;

use App\Mail\EmailTrialUserInviter;
use App\Mail\TryingUser as MailTryingUser;
use App\Models\TryingRecord;
use App\Models\TryingUser;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class GenerateTryingUserCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userImage;
    protected $userEmail;
    protected $userId;
    protected $firstName;
    protected $lastName;
    public $timeout = 7200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userImage, $userEmail, $userId, $firstName, $lastName)
    {
        $this->userImage = $userImage;
        $this->userEmail = $userEmail;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bold = function ($font) {
            $font->file(public_path('css/fonts/FuturaPTHeavy.woff'));
            $font->valign('middle');
            $font->size(20);
        };
        $normal = function ($font) {
            $font->file(public_path('css/fonts/FuturaPTLight.woff'));
            $font->valign('middle');
            $font->size(22);
        };
        $image = Image::make(storage_path("app/public/defaults/default-card.jpg"));

        $user_image = ($this->userImage) ? Image::make(storage_path('app/public/' . $this->userImage))->resize(250, 250) : Image::make(storage_path('app/public/defaults/default-user.png'))->resize(250, 250);
        $logo = Image::make(storage_path('app/public/defaults/default-logo.png'))->resize(325, 100);
        $mask = Image::canvas(250, 250);

        $mask->circle(250, 125, 125, function ($draw) {
            $draw->background('FFFFFF');
        });
        $user_image->mask($mask, false);
        $image->insert($user_image, 'top-left', 60, 100);
        $image->circle(275, 185, 225, function ($draw) {
            $draw->border(8, '52c7ef');
        });
        $image->insert($logo, 'top-left', 350, 60);
        $image->text("First Name",  350, 185, $normal);
        $image->text(": " . Str::limit($this->firstName, 30),  475, 185, $bold);
        $image->text("Last Name",  350, 220, $normal);
        $image->text(": " . $this->lastName,  475, 220, $normal);
        $image->text("Email",  350, 255, $normal);
        $image->text(": " . $this->userEmail,  475, 255, $normal);

        $image->text(route('web.getTryingUserProfile', ['user_id' => $this->userId]),  25, 415, $normal);
        $image->save(storage_path('app/public/uploads/profile/card-' . $this->userId . '.jpg'));

        // $email = $this->userEmail;

        // Mail::send('mail.trying_services', ['user_id' => $this->userId], function($message) use ($email){
        //     $message->to($email)
        //         ->subject("Trying our services");
        // });
        Mail::to($this->userEmail)->queue(new MailTryingUser($this->userId));

        // check the invitor is found
        $invitor = TryingUser::where('id', $this->userId)->first();
        if ($invitor && $invitor->user_id) {

            $user = User::find($invitor->user_id);
            Mail::to($user->email)->queue(new EmailTrialUserInviter($user, $this->userId));
        }
    }
}
