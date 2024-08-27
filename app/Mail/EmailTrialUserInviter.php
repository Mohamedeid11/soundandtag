<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailTrialUserInviter extends  Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $inviter;
    public $invitee;
    public $subject = "Request Pronunciations has been accepted";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inviter, $invitee)
    {
        $this->inviter = $inviter;
        $this->invitee  = $invitee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.email_trial_user_inviter');
    }
}
