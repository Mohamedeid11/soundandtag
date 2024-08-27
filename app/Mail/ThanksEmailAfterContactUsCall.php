<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ThanksEmailAfterContactUsCall extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $name;
    public $subject = "Thanks for Contacting Us";

    /**
     * Create a new message instance.
     *
     * @param string $email
     */
    public function __construct(string $name)
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
        return $this->view('mail.thanks_email_after_contact_us_call');
    }
}
