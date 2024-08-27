<?php

namespace App\Models;

use App\Events\DeletingUser;
use App\Events\UserStateChanged;
use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\PasswordReset;

/**
 * Class User
 * @package App\Models
 * @property string $account_type
 * @property HasMany $employees
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Translatable, HasAdminForm, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $appends = ['plan', 'validity', 'full_name', 'full_name_updated', 'is_public', 'items', 'can_renew', 'can_upgrade'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $full_name;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'featured' => 'boolean',
        'active' => 'boolean',
        'hidden' => 'boolean'
    ];
    public $trans_fields = ['name'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getCanRenewAttribute()
    {
        return $this->plan ? ($this->plan->plan->account_type == $this->account_type) && $this->plan->remaining < 30 : false;
    }
    public function getCanUpgradeAttribute()
    {
        return $this->plan ? ($this->plan->plan->account_type == $this->account_type) && (bool) Plan::where(['account_type' => $this->account_type])->where('price', '>', $this->plan->plan->price)->get() : false;
    }
    public function getAvailablePlans($upgrade = false)
    {
        $operand = $upgrade ? '>' : '>=';
        $plan = $this->user_plans()->orderBy('created_at', 'desc')->first();
        if ($plan) {
            return Plan::where(['account_type' => $this->account_type])->where('price', $operand, $plan->plan->price)->get();
            // if($upgrade){
            //     return Plan::where(['account_type'=>$this->account_type])->where('price', '>=', $plan->plan->price)->get();
            // }
            // return Plan::where(['account_type'=>$this->account_type])->get();
        } else {
            return Plan::where(['account_type' => $this->account_type])->get();
        }
    }
    public function getItemsAttribute()
    {
        if ($this->account_type == 'corporate') {
            if ($this->isInTrial()) {
                return 15;
            }
            return $this->plan && $this->plan->plan->items > 0 ? $this->plan->plan->items : 0;
        }
        return 0;
    }
    public function getIsPublicAttribute()
    {
        return (bool) self::public()->where(['id' => $this->id])->first();
    }
    public function getFullNameAttribute()
    {
        return $this->name;
    }
    public function getFullNameUpdatedAttribute()
    {
        $full_name_updated =  $this->name;
        $full_name_updated .= $this->middle_name ? ' ' . $this->middle_name : '';
        $full_name_updated .= $this->last_name ? ' ' . $this->last_name : '';
        return $full_name_updated;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function hits()
    {
        return $this->hasMany(UserHit::class);
    }
    protected function form_fields()
    {
        $countries = Country::where(['active' => true])->get();
        $countries_choices = [];
        foreach ($countries as $country) {
            $countries_choices[$country->id] = $country->trans('name');
        }
        return collect([
            'country_id' => collect(['type' => 'select', 'required' => 3, 'choices' => $countries_choices]),
            'account_type' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => ['personal' => __('global.personal'), 'corporate' => __('global.corporate')]
            ]),
            'username' => collect(['type' => 'text', 'required' => 3]),
            'image' => collect(['type' => 'file', 'required' => 0]),
            'featured' => collect(['type' => 'checkbox']),
            'active' => collect(['type' => 'checkbox']),
            'hidden' => collect(['type' => 'checkbox']),
            'email' => collect(['type' => 'email', 'required' => 3]),
            'name' => collect(['type' => 'text', 'required' => 3]),
            'password' => collect(['type' => 'password', 'required' => 2]),
            'phone' => collect(['type' => 'text', 'required' => 0]),
            'website' => collect(['type' => 'text', 'required' => 0]),
        ]);
    }
    public function records()
    {
        return $this->hasMany(Record::class);
    }
    public function record_types()
    {
        return $this->hasMany(RecordType::class);
    }
    //    public function plans(){
    //        return $this->hasManyThrough(Plan::class, UserPlan::class);
    //    }
    public function user_plans()
    {
        return $this->company ? $this->company->user_plans() : $this->hasMany(UserPlan::class);
    }
    public function getPlanAttribute()
    {
        return $this->user_plans()->where('end_date', '>=', Carbon::now())->orderByDesc('created_at')->first();
    }
    public function company()
    {
        return $this->belongsTo(__CLASS__, 'company_id');
    }
    public function employees(): HasMany
    {
        return $this->hasMany(CorporateEmployee::class, "user_id");
    }
    public function isInTrial($returnValue = false)
    {
        if ($this->company) {
            return $this->company->isInTrial();
        }
        if (count($this->user_plans) > 0) {
            return false;
        }
        $now = Carbon::now();
        $formatted_date=Carbon::parse($this->created_at);
        $diff = $this->created_at ? $formatted_date->diffInDays($now) : 0;
        $trial_period = Setting::where(['name' => 'trial_days'])->first()->value;
        if ($returnValue) {
            return $diff <= $trial_period ? $trial_period - $diff : false;
        } else {
            return $diff <= $trial_period;
        }
    }
    public function getValidityAttribute()
    {
        if ($this->isInTrial()) {
            return true;
        } else if ($this->plan) {
            if ($this->plan->remaining > -1) {
                return true;
            }
        }
        return false;
    }
    public function scopeFeatured($query)
    {
        $query->where(['featured' => true]);
    }
    public function scopePersonal($query)
    {
        $query->where(['account_type' => 'personal']);
    }
    public function scopeCorporate($query)
    {
        $query->where(['account_type' => 'corporate']);
    }
    public function scopeEmployee($query)
    {
        $query->where(['account_type' => 'employee']);
    }
    public function scopePublic($query)
    {
        $query->whereNotNull('email_verified_at')->whereNotNull('name')->whereNotNull('country_id')
            ->where(function ($q) {
                $trial_period = Setting::where(['name' => 'trial_days'])->first()->value;
                $q->whereRaw("exists (select * from user_plans where users.id = user_plans.user_id and end_date >= NOW()) or exists (select * from user_plans where users.company_id = user_plans.user_id and end_date >= NOW())")
                    ->orWhere('created_at', '>=', Carbon::now()->subdays($trial_period));
            })
            ->where(['hidden' => false])->where(['active' => true])
            ->where(function ($Qu) {
                $Qu->where(function ($personal) {
                    $record_types = RecordType::general()->personal()->where(['required' => true])->pluck('id');
                    $personal->where(['account_type' => 'personal']);
                    foreach ($record_types as $record_type) {
                        $personal->whereHas('records', function ($q) use ($record_type) {
                            $q->where(['record_type_id' => $record_type])->whereNotNull('file_path');
                        });
                    }
                });
                $Qu->orWhere(function ($corporate) {
                    $record_types = RecordType::general()->corporate()->where(['required' => true])->pluck('id');
                    $corporate->where(['account_type' => 'corporate']);
                    foreach ($record_types as $record_type) {
                        $corporate->whereHas('records', function ($q) use ($record_type) {
                            $q->where(['record_type_id' => $record_type])->whereNotNull('file_path');
                        });
                    }
                });
                $Qu->orWhere(function ($employee) {
                    $record_types = RecordType::general()->employee()->where(['required' => true])->pluck('id');
                    $employee->where(['account_type' => 'employee']);
                    foreach ($record_types as $record_type) {
                        $employee->whereHas('records', function ($q) use ($record_type) {
                            $q->where(['record_type_id' => $record_type])->whereNotNull('file_path');
                        });
                    }
                });
            });
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }
    protected $dispatchesEvents = [
        'saved' => UserStateChanged::class,
        'restored' => UserStateChanged::class,
        'deleting' => DeletingUser::class,
    ];
}
