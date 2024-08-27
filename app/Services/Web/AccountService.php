<?php

namespace App\Services\Web;

use App\Lib\Payments\Credimax\PaymentPreparer;
use App\Lib\Payments\Credimax\PaymentProcessor;
use App\Models\Payment;
use App\Models\RecordType;
use App\Models\User;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Traits\UserValidityTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AccountService
{
    use UserValidityTrait;
    private $countryRepository;
    private $userRepository;
    private $settingRepository;
    private $planRepository;
    public function __construct(
        CountryRepositoryInterface $countryRepository,
        UserRepositoryInterface $userRepository,
        SettingRepositoryInterface $settingRepository,
        PlanRepositoryInterface $planRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->settingRepository = $settingRepository;
        $this->planRepository = $planRepository;
    }
    public function getCountries()
    {
        return $this->countryRepository->all();
    }

    public function editDetails(array $data, array $files)
    {
        $user = User::find(auth()->guard('user')->id());

        if ($user->account_type == 'corporate') {

            $record_data = [
                [
                    'Business Name' => $data['business_name'],
                    'meaning' => $data['business_name_meaning']
                ]
            ];
            $data = Arr::only($data, ['business_name', 'country_id', 'phone', 'image_cropping', 'revertImage' , 'video' , 'delete_video' , 'website', 'address', 'biography']);

            $data['name'] = Str::of($data['business_name'])->replace('  ', ' ');
            unset($data['business_name']);

        } else {

            $data['first_name'] = Str::of($data['full_name'])->replace('  ', ' ');
            unset($data['full_name']);
            $record_data = [
                ['First Name' => $data['first_name'], 'meaning' => $data['first_name_meaning']],
                ['Middle Name' => $data['middle_name'], 'meaning' => $data['middle_name_meaning']],
                ['Last Name' => $data['last_name'], 'meaning' => $data['last_name_meaning']],
                ['Nickname' => $data['nick_name'], 'meaning' => $data['nick_name_meaning']]
            ];
            if ($user->account_type == 'personal') {
                array_push(
                    $record_data,
                    ['Company' => $data['company'], 'meaning' => $data['company_meaning']]
                );
            }
            $data['name'] = Str::of($data['first_name'])->replace('  ', ' ');
            unset($data['first_name']);
            $data = Arr::only($data, ['name', 'middle_name', 'last_name', 'country_id', 'phone', 'image_cropping' , 'video' , 'delete_video' ,'revertImage', 'website', 'address', 'biography', 'interests', 'position']);
        }


        if (Arr::has($data, 'revertImage')) {

            $default = ($user->account_type === 'personal' ) ? 'default-user.png' : (($user->account_type === 'employee') ? 'employee.png' : 'default-corporate.png');
            $image = "uploads/profile/" . time() . ".png";
            Storage::disk('public')->copy("defaults/" . $default, $image);
            $data['image'] = $image;
            Storage::disk('public')->delete($user->image);

        } else {

            if (isset($files['image'])) {
                Storage::disk('public')->delete($user->image);
                $image = $data['image_cropping'];  // your base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = Carbon::now()->timestamp . $user->username . '.' . 'png';
                $data['image'] = "uploads/profile/" . $imageName;
                Storage::disk('public')->put("uploads/profile/" . $imageName, base64_decode($image));

                // $data['image'] = $files['image']->store('uploads/profile', ['disk' => 'public']);
            } else {
                $data['image'] = $user->image;
            }
        }

        unset($data['revertImage']);

        if (isset($files['video'])) {
            isset($user->video) ? Storage::disk('public')->delete($user->video)  : '';
            $video = $data['video'];
            $videoName = $user->username . '_' . Carbon::now()->timestamp . '.' . $video->getClientOriginalExtension();
            $data['video'] = "uploads/profile/" . $videoName;
            Storage::disk('public')->put( $data['video'] ,  file_get_contents($video)  );

            // $data['image'] = $files['image']->store('uploads/profile', ['disk' => 'public']);
        }

        if ( isset( $data['delete_video'] )  && $data['delete_video'] == "on")
        {
            isset($user->video) ? Storage::disk('public')->delete($user->video)  : ' ';
            unset($data['delete_video']);
            $user->update(['video' => null ]);
        }

//         if (Arr::has($data, 'image_cropping') && $data['image_cropping']) {
//
//             $cropping_details = json_decode($_POST['image_cropping'], true);
//             $image = Image::make(storage_path('app/public/' . $data['image']));
//             $image->crop((int) $cropping_details['width'], (int) $cropping_details['height'], (int) $cropping_details['x'], (int) $cropping_details['y']);
//             $width = (int) $cropping_details['width'];
//             $height = (int) $cropping_details['height'];
//             $mask = Image::canvas($width, $height);
//
//             // draw a white circle
//             $mask->circle($width, $width / 2, $height / 2, function ($draw) {
//                 $draw->background('#fff');
//             });
//
//             $image->mask($mask, false);
//             $image->save(storage_path('app/public/' . $data['image']));
//         }
        unset($data['image_cropping']);

        $user->update($data);
        $record_types = RecordType::where('account_type', $user->account_type)->orderBy('id', 'asc')->get();
        foreach ($record_data as $index => $one_record) {
            $keys = array_keys($one_record);
            $record_type_id = $record_types->where('name', $keys[0])[$index]->id;
            if (!empty($one_record[$keys[0]])) {
                $user->records()->updateOrCreate(
                    [
                        'record_type_id' => $record_type_id
                    ],
                    [
                        'text_representation' => $one_record[$keys[0]],
                        'meaning' => $one_record[$keys[1]],
                    ]
                );
            } else {
                $user->records()->where('record_type_id', $record_type_id)->delete();
            }
        }
        session()->flash('success', __('admin.success_edit', ['thing' => __('global.user')]));
    }

    public function changePassword(array $data): array
    {
        $user = auth()->guard('user')->user();
        if (!Hash::check($data['current_password'], $user->password)) {
            return ['current_password' => __("global.wrong_pass")];
        }
        $user->update(['password' => Hash::make($data['password'])]);
        session()->flash('password_changed', __("global.password_changed"));
        return [];
    }

    public function toggleStatus()
    {
        $user = auth()->guard('user')->user();
        $user->update(['hidden' => !$user->hidden]);
        if ($user->hidden) {
            session()->flash('success', __('admin.success_hidden', ['thing' => __('global.user')]));
        } else {
            session()->flash('success', __('admin.success_published', ['thing' => __('global.user')]));
        }
    }

    public function fakePayment()
    {
        $user = auth()->guard('user')->user();
        Payment::factory(['user_id' => $user->id, 'confirmed' => true])->create();
        session()->flash('success',  __('admin.success_add', ['thing' => __('global.user')]));
    }
    public function getSubscriptionPrice()
    {
        return $this->settingRepository->getBy('name', 'subscription_price')->value;
    }

    public function getPaymentPreparer()
    {
        return App::make(PaymentPreparer::class);
    }

    public function processPayment($username, array $data, $headers)
    {
        $data = array_merge($data,request()->session()->get('data'));
        $paymentProcessor = App::make(PaymentProcessor::class, ['username' => $username, 'data' => $data, 'headers' => $headers]);

        if ($paymentProcessor->validity()) {
            $user = $this->userRepository->getBy('username', $username);
            if ($user->user_plan && !$paymentProcessor->isUpgrade()) {
                $start_date = (new Carbon($user->user_plan->end_date))->addDay();
                $end_date = (new Carbon($user->user_plan->end_date))->addDay()->addYear();
            } else {
                $start_date = Carbon::now();
                $end_date = Carbon::now()->addYears($paymentProcessor->getPlan()->years);
            }
            $payment = Payment::create([
                'user_id' => $user->id, 'transaction_id' => $paymentProcessor->getTransactionId(), 'payment_type' => 'credit',
                'confirmed' => true, 'value' => $paymentProcessor->userSubscriptionPrice
            ]);
            $user->user_plans()->create(['plan_id' => $paymentProcessor->getPlan()->id, 'payment_id' => $payment->id, 'start_date' => $start_date, 'end_date' => $end_date]);

            $email = $user->email;
            Mail::send('mail.successful_payment', ['name' => $user->name], function ($message) use ($email) {
                $message->to($email)
                    ->subject("Payment Successful");
            });
        }
    }

    public function deleteAccount()
    {
        $user = auth()->guard('user')->user()->id;
        User::find($user)->delete();
    }

    public function getPlans()
    {
        return auth()->guard('user')->user()->getAvailablePlans();
    }
    public function getUpgradePlans()
    {
        return auth()->guard('user')->user()->getAvailablePlans(true);
    }
    public function getPlan(array $data)
    {
        return $this->planRepository->get($data['plan']);
    }
}
