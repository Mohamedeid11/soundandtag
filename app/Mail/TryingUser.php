<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TryingUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $user_id; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Trying our services")->view('mail.trying_services')->text('mail.trying_services')->with(['user_id' => $this->user_id]);
    }
}
