<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class EmailUserBeforDeleting extends Mailable
{
    use Queueable, SerializesModels;
    public $user;

    public $diff;
    public $diff_text = "days";
    public $subject = "Reminder before Deleting account";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user , $diff)
    {
        $this->user = $user;
        $this->diff = $diff;
        $this->diff_text = ($this->diff) > 1 ? "days" : "day";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $users_with_plan = $this->user->user_plans;

        if (count($users_with_plan) > 0){

            $message_text = '
                        Your plan has expired and your account will be deleted in 1 day , make sure you\'re logged-in then check your
                        <a href=" '. route('account.status') .' " style="color: #63caf7; text-decoration: none"> account status</a>
                        page to renew your plan .';
        } else {

            $message_text = 'Your account will be deleted in ' . $this->diff . ' ' . $this->diff_text . ', make sure you\'re logged-in then subscribe from <a href=" '. route('account.status') .' " style="color: #63caf7; text-decoration: none"> account status</a> page .';

        }


        return $this->view('mail.email_user_before_deleting')->with([
            'user_name' => $this->user->name,
            'message_text' => $message_text,
        ]);
    }
}
