<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailTUserAfterExpiry extends Mailable
{
    use Queueable, SerializesModels;
    public $user_id;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_id, $email)
    {
        $this->user_id = $user_id;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Register on Sound&Tag")->view('mail.notify_trying_user_after_expiry')->text('mail.notify_trying_user_after_expiry')->with(['user_id' => $this->user_id, 'email' => $this->email]);

    }
}
