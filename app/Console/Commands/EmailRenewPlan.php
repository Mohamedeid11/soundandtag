<?php

namespace App\Console\Commands;

use App\Events\DeletingUser;
use App\Mail\EmailUserBeforDeleting;
use App\Mail\EmailUserToRenewPlan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailRenewPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:RenewPlanUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email user before his plan expiry';

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
        $plans = UserPlan::has('user')->get();

        foreach ($plans as $plan) {

            $end_30_date = Carbon::parse($plan->end_date)->addDays(30);
            $now         = Carbon::now();
            $remaining        = $now->diffInDays($end_30_date,false);

//            dump($plan->user->name . ' | ' .  $remaining. ' | ' .  $plan->user->email   ) ;

            if ($remaining == 14 || $remaining == 7 || $remaining == 1 ) {
                Mail::to($plan->user->email)->send(new EmailUserToRenewPlan($plan->user , $remaining));
                $this->info(__('global.email_sent_successfully'));
            }

        }



        // return Command::SUCCESS;
//        $users = User::all();
//
//        foreach ($users as $user) {
//            $remaining_days = $user->plan ? $user->plan->remaining : 0;
//            // echo  $user->username . " : " . $remaining_days . "-----";
//            if ($user->can_renew && $remaining_days < 15) {
//                // Enable to send Email
//                if ($remaining_days == 14) {
//                    Mail::to($user->email)->send(new EmailUserToRenewPlan($user));
//                } else if ($remaining_days == 7) {
//                    Mail::to($user->email)->send(new EmailUserToRenewPlan($user));
//                } else if ($remaining_days == 1) {
//                    Mail::to($user->email)->send(new EmailUserToRenewPlan($user));
//                }
//            }
//        }

    }
}
