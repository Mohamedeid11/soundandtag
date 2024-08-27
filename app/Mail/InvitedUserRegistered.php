<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitedUserRegistered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $inviter;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $inviter)
    {
        $this->user = $user;
        $this->inviter = $inviter;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Invitation Accepted")->view('mail.invitation_accepted')->text('mail.invitation_accepted');
    }
}
