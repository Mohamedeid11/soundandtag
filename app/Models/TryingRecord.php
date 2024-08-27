<?php

namespace App\Models;

use App\Events\NewTryingUser;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TryingRecord extends Model
{
    use HasFactory, Translatable;

    protected $guarded = [];
    protected $appends = ['first_name_full_url', 'last_name_full_url', 'full_name'];
    public $trans_fields = ['first_name', 'last_name', 'first_name_meaning', 'last_name_meaning'];

    public function user(){
        return $this->belongsTo(TryingUser::class);
    }
    public function getFirstNameFullUrlAttribute(){
        return Str::of($this->first_name_file)->startsWith('storage') ? asset($this->first_name_file) : asset('storage/'.$this->first_name_file);
    }
    public function getLastNameFullUrlAttribute(){
        return Str::of($this->last_name_file)->startsWith('storage') ? asset($this->last_name_file) : asset('storage/'.$this->last_name_file);
    }
    public function getFullNameAttribute(){
        return $this->first_name . " " . $this->last_name;
    }

    protected $dispatchesEvents = [
        'saved' => NewTryingUser::class
    ];
}
