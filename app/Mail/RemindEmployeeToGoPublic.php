<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class RemindEmployeeToGoPublic extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $name;
    public $username;
    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $email
     */
    public function __construct($name, $username)
    {
        $this->name = $name;
        $this->username = $username;
        $this->subject('Remind Employee To Go Public And Fix His Account Issues');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.remind-employee-to-go-public')->text("mail.remind-employee-to-go-public")->with(['name'=> $this->name, 'username'=> $this->username]);
    }
}
