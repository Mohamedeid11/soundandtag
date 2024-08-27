<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordType extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    protected $fillable = ['name', 'user_id', 'type_order', 'account_type', 'required'];
    public $trans_fields = ["name"];
    protected $casts = [
      'required' => 'boolean'
    ];
    public function form_fields(){
        $choices = [];
        $choices[0] = __('global.admin');
        foreach(User::all() as $user){
            $choices[$user->id] = $user->full_name;
        }

        return collect([
            'user_id' => collect(['type'=>'select', 'required' => 3, 'choices' => $choices]),
            'account_type' => collect(['type'=>'select', 'required' => 3,
                'choices' => ['personal'=>__('global.personal'), 'corporate'=>__('global.corporate'), 'employee'=>__('global.employee')]]),
            'name' => collect(['type'=>'text','required'=>3]),
            'required' => collect(['type'=>'checkbox', 'required'=>0]),
            'type_order' => collect(['type'=>'number', 'required'=>3]),

            ]);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function scopeGeneral($query){
        $query->where(['user_id'=>null]);
    }
    public function scopePersonal($query){
        $query->where(['account_type'=>'personal']);
    }
    public function scopeCorporate($query){
        $query->where(['account_type'=>'corporate']);
    }
    public function scopeEmployee($query){
        $query->where(['account_type'=>'employee']);
    }
}
