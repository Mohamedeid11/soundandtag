<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailTUserBeforeExpiry extends Mailable
{
    use Queueable, SerializesModels;
    public $user_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_id, $days_diff)
    {
        $this->user_id = $user_id;
        $this->days_diff = $days_diff;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Free Trial ends tomorrow")->view('mail.notify_trying_user_before_expiry')
            ->text('mail.notify_trying_user_before_expiry')
            ->with(['user_id' => $this->user_id, 'days_diff' => $this->days_diff]);
    }
}
