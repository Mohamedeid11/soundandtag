<?php

namespace App\Console\Commands;

use App\Mail\EmailTUserAfterExpiry;
use App\Mail\EmailTUserBeforeExpiry;
use App\Models\TryingUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailTryingUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:TUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'notify give it a try user before his profile expiry, or email him after expiry to register';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        $users = TryingUser::get();
        foreach ($users as $user) {

            $expire_date = $user->created_at->addDays(14);
            $days_diff = $expire_date->diffInDays(Carbon::now());
//            dump($expire_date . ' | ' .  $days_diff . ' | ' .fmod($days_diff, 7) . ' | ' . Carbon::now() ) ;
            if ($expire_date > Carbon::now() && ($days_diff == 1 || $days_diff == 7)) {

                Mail::to($user->email)->send(new EmailTUserBeforeExpiry($user->id, $days_diff));
                $this->info(__('global.email_sent_successfully'));

            } else if ($expire_date < Carbon::now() && (fmod($days_diff, 7) == 0)) {

                Mail::to($user->email)->send(new EmailTUserAfterExpiry($user->id, $user->email));
                $this->info( __('global.email_sent_successfully'));
            }

        }

    }
}
