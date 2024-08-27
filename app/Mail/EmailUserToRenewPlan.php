<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUserToRenewPlan extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $name;
    public $remaining;
    public $remaining_text = "days";
    public $subject = "Renew Plan";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user , $remaining)
    {
        $this->name = $user->username;
        $this->remaining = $remaining;
        $this->remaining_text = ($this->remaining) > 1 ? "Days" : "Day";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.renew_plan');
    }
}
