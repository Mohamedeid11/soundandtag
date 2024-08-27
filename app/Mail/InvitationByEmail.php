<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationByEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = auth()->guard('user')->user();
        $username = $user->username;
        $inviter_name = $user->name;
        return $this->subject("Invitation to join sound&tag")->view('mail.invitation_by_email')->text('mail.invitation_by_email')->with(['name' => $this->name, 'username' => $username, 'inviter_name'=> $inviter_name]);

    }
}
