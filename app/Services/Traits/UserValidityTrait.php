<?php
namespace App\Services\Traits;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait UserValidityTrait {
    public function getRecords(?Authenticatable $user)
    {
        $recordTypeRepository = App::make(RecordTypeRepositoryInterface::class);
        $available_record_types = $recordTypeRepository->getGeneralRecords($user->account_type)->all();
        foreach ($available_record_types as $available_record_type){
            $available_record_type->record = $user->records()->where(['record_type_id'=>$available_record_type->id])->first() ?:
                ['text_representation'=>null, 'original_text'=> null, 'meaning'=>null, 'original_meaning'=>null] ;
        }
        $available_user_record_types = $user->record_types;
        foreach ($available_user_record_types as $available_user_record_type){
            $available_user_record_type->record = $user->records()->where(['record_type_id'=>$available_user_record_types->id])->first() ?:
                ['text_representation'=>null, 'original_text' => null, 'meaning'=>null, 'original_meaning'=>null] ;
        }
        return [
            'available_record_types' => $available_record_types,
            'available_user_record_types'=>$available_user_record_types
        ];
    }

    public function getGeneralValidity($user){
        if (! $user->email_verified_at){
            return false;
        }
        if ($user->full_name == null || $user->country_id == null){
            return false;
        }
        if (! $user->validity){
            return false;
        }
        $record_types = $this->getRecords($user);
        foreach ($record_types['available_record_types'] as $record_type){
            if ($record_type->required && ! isset($record_type->record['file_path'])){
                return false;
            }
        }
        return true;
    }
    public function getValidityLackReasons($user){
        $reasons = array();
        if (! $user->email_verified_at){
            array_push($reasons, collect([
                'title'=> __('global.please_verify_your_email'),
                // 'text' => 'Your profile cannot go public until you verify your email',
                'url_text' => __('global.go_ahead'),
                'url' => route('verification.notice'),
                'type' => 'verify-email',
                'class' => 'btn btn-success'
            ]));
        }
        if ($user->full_name == null || $user->country == null){
            if($user->email_verified_at){
                array_push($reasons, collect([
                    'title'=>  __('global.complete_subscription_form_required') ,
                    'text' => '',
                    'url_text' => __('global.go_ahead'),
                    'url' => route('account.edit'),
                    'class' => 'btn btn-success',
                    'type' => 'name-missing'
                ]));
            } else {
                array_push($reasons, collect([
                    'title'=> __('global.complete_subscription_form_required') ,
                    'text' => '',
                    'url_text' => '',
                    'url' => '',
                    'type' => 'name-missing'
                ]));
            }
        }
        if (! $user->validity){
            if($user->email_verified_at && $user->full_name != null && $user->country != null){

                if(count($user->user_plans) == 0){

                    $url_text = 'ok';
                    $class = 'btn btn-success';
                    $url = route('account.status');
                    $title = __('global.pay_subscription_fee');
                    if($user->account_type == 'employee'){
                        $url_text = '';
                        $class = '';
                        $url = '';
                        $title = __('global.contact_corporate_pay_subscription_fee');
                    }

                    array_push($reasons, collect([
                        'title'=> $title,
                        'text' => '',
                        'url_text' => $url_text,
                        'class' => $class,
                        'url' => $url,
                        'type' => 'payment'
                    ]));
                } else {

                    $url_text = 'ok';
                    $class = 'btn btn-success';
                    $url = route('account.status');
                    $title = __('global.renew_your_plan_long_desc');
                    if($user->account_type == 'employee'){
                        $url_text = '';
                        $class = '';
                        $url = '';
                        $title = __('global.contact_corporate_renew_your_plan_long_desc');
                    }

                    array_push($reasons, collect([
                        'title'=> $title ,
                        'text' => '',
                        'url_text' => $url_text,
                        'class' => $class,
                        'url' => $url,
                        'type' => 'payment'
                    ]));
                }
            } else {
                array_push($reasons, collect([
                    'title'=>  __('global.pay_subscription_fee'),
                    'text' => '',
                    'url_text' => '',
                    'class' => '',
                    'url' => '',
                    'type' => 'payment'
                ]));
            }
        }
        if($user->email_verified_at && $user->validity && $user->full_name != null && $user->country != null){
            $record_types = $this->getRecords($user);
            foreach ($record_types['available_record_types'] as $record_type){
                if ($record_type->required && ! isset($record_type->record['file_path'])){
                    array_push($reasons, collect([
                        'title'=> __('global.complete_record_name_pronounciation') ,
                        'text' => '',
                        'url_text' => __('global.go_ahead'),
                        'class' => 'btn btn-success',
                        'url' => route('profile.edit'),
                        'type' => 'record-missing'
                    ]));
                    break;
                }
            }
        } else {
            $record_types = $this->getRecords($user);
            foreach ($record_types['available_record_types'] as $record_type){
                if ($record_type->required && ! isset($record_type->record['file_path'])){
                    array_push($reasons, collect([
                        'title'=> __('global.complete_record_name_pronounciation') ,
                        'text' => '',
                        'url_text' => '',
                        'class' => '',
                        'url' => '',
                        'type' => 'record-missing'
                    ]));
                    break;
                }
            }
        }
        return $reasons;
    }
    public function getValidityLackFirstReason($user){
        if (! $user->email_verified_at && ! Str::contains(request()->route()->getName(), 'verification.notice') && ! Str::contains(request()->route()->getName(), 'account.status')){
            return collect([
                'title'=> __('global.email_not_verified'),
                // 'text' => 'Your profile cannot go public until you verify your email',
                'url_text' => __('global.go_ahead'),
                'url' => route('verification.notice')
            ]);
        }
        if($user->email_verified_at && ($user->full_name == null  || $user->country == null) && ! Str::contains(request()->route()->getName(), 'account.status') && ! Str::contains(request()->route()->getName(), 'account.edit'))
        {
            return collect([
                'title'=> __('global.complete_your_subscription'),
                // 'text' => 'Your profile cannot go public until you add alt least the required records',
                'url_text' => __('global.go_ahead'),
                'url' => route('account.status')
            ]);
        }
        //        die("$user->isInTrial(false)");
        if ($user->email_verified_at && $user->full_name != null && $user->country != null && ! $user->validity && ! Str::contains(request()->route()->getName(), 'account.status')){
            if(count($user->user_plans) > 0){
                return collect([
                    'title'=> __('global.renew_your_plan'),
                    // 'text' => 'Your profile cannot go public until you subscribe/renew your plan',
                    'url_text' => __('global.go_ahead'),
                    'url' => route('account.status')
                ]);
            }
            return collect([
                'title'=> __('global.complete_your_subscription') ,
                // 'text' => 'Your profile cannot go public until you subscribe/renew your plan',
                'url_text' => __('global.go_ahead'),
                'url' => route('account.status')
            ]);
        }

        if($user->email_verified_at && $user->full_name != null && $user->country != null && $user->validity && ! Str::contains(request()->route()->getName(), 'profile.edit') && ! Str::contains(request()->route()->getName(), 'account.status'))
        {
            $record_types = $this->getRecords($user);
            foreach ($record_types['available_record_types'] as $record_type){
                if ($record_type->required && ! isset($record_type->record['file_path'])){
                    return collect([
                        'title'=> __('global.complete_your_subscription'),
                        // 'text' => 'Your profile cannot go public until you add alt least the required records',
                        'url_text' => __('global.go_ahead'),
                        'url' => route('account.status')
                    ]);
                }
            }
        }
        return null;
    }
}
