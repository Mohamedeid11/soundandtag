<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHit extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function scopeToday($query){
        $query->whereDate('day', date('Y-m-d'));
    }
    public function scopeMonth($query){
        $query->whereMonth('day', date('m'));
    }
}
