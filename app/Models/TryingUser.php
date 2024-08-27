<?php

namespace App\Models;

use App\Events\DeletingTryingUser;
use App\Events\NewTryingUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TryingUser extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['name', 'expire_date'];

    public function tryingRecords()
    {
        return $this->hasMany(TryingRecord::class, 'user_id');
    }

    public function getNameAttribute()
    {
        return TryingRecord::where('user_id', $this->id)->first()->first_name;
    }

    public function getExpireDateAttribute()
    {
        if($this)
        {
            return $this->created_at->addDays(14);
        }
    }

    protected $dispatchesEvents = [
//        'saved' => NewTryingUser::class,
        'deleting' => DeletingTryingUser::class,
    ];
}
