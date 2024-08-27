<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class PasswordReset extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = $notifiable->getEmailForPasswordReset();
        $user = User::where('email', $email)->firstOrFail();
        // return (new MailMessage)
        //     ->subject(Lang::get('Reset Password Notification'))
        //     ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
        //     ->line(Lang::get("Your username is " . $user->username))
        //     ->line(Lang::get('If you want to reset your password, click the below button'))
        //     ->action(Lang::get('Reset Password'), url(route('password.reset', [
        //         'token' => $this->token,
        //         'email' => $email,
        //     ], false)))
        //     ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
        //     ->line(Lang::get('If you did not request a password reset, no further action is required.'));

        return (new MailMessage)
            ->subject('Reset Password')
            ->view('mail.reset-password-email', ['username' => $user->username, 'token'=> $this->token, 'email'=> $email, 'count'=> config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
