<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
             'ibrahim', 'ali', 'mohamed', 'islam', 'nahla'
        ];

        $plan = Plan::inRandomOrder()->first();

        foreach($users as $i=> $user)
        {
            $default = 'default-user.png' ;
            $image = "uploads/profile/". ucfirst($user).time().".png";
            Storage::disk('public')->copy("defaults/".$default, $image);

            $users = User::factory()->create(
                [
                    'account_type' => 'personal',
                    'name' => ucfirst($user),
                    "username" => $user,
                    'email' => $user . '@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make($user), // password
                    'remember_token' => Str::random(10),
                    'country_id'=>Country::inRandomOrder()->first()->id,
                    'image' =>$image,
                    'featured' => true,
                    'plan_id' => $plan->id,

                ]
            );

            if($i != 0)
            {

                $payment = Payment::factory()->create(
                    [
                        'user_id'=> $users['id'],
                        'payment_type'=> 'credit',
                        'value' => $plan->price,
                        'confirmed' => true,
                    ]);

                $users->user_plans()->create(['plan_id' => $users['plan_id'], 'payment_id' => $payment['id'], 'start_date' => Carbon::now(), 'end_date' => Carbon::now()->addYears($plan->years)]);

            }
        }
        $dispatcher = User::getEventDispatcher();
        User::unsetEventDispatcher();
        User::factory()->count(5)->create();
        User::setEventDispatcher($dispatcher);

    }
}
