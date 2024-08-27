<?php

namespace App\Console\Commands;

use App\Events\DeletingUser;
use App\Mail\EmailUserBeforDeleting;
use App\Mail\EmailUserEndTrial;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class DeleteAccountAfterEndPLan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:EndPlan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email the user when his plan ends after 29 days and delete it after 30 days.';

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

        $users = User::all();

        foreach ($users as $user){

            $users_with_plan =$user->user_plans;

            if (count($users_with_plan) > 0){

                $end_30_date = Carbon::parse($users_with_plan->pluck('end_date')[0])->addDays(30);

            } else {

                $end_30_date = Carbon::parse($user->created_at)->addDays(30);

            }

            $now         = Carbon::now();
            $diff        = $now->diffInDays($end_30_date,false);
//            dump($diff . ' - ' . $user->name);

            if ($diff == 1 || (count($users_with_plan) == 0 && ($diff == 7 || $diff == 14 || $diff == 21) ) ) {
                Mail::to($user->email)->send(new EmailUserBeforDeleting($user , $diff));
//                echo 'email sent ';
            }elseif($diff == 0){
                event(new DeletingUser($user));
                $user->delete();
//                echo 'account deleted';
            }

        }

    }
}
