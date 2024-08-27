<?php

namespace App\Models;

use App\Traits\AdminAuthenticatable;
use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends AdminAuthenticatable
{
    use HasFactory, Notifiable, HasApiTokens, Translatable, HasAdminForm;
    protected $fillable = ['name', 'username', 'email', 'password', 'role_id', 'image'];
    protected $casts = [
        'is_system' => 'boolean'
    ];
    protected $attributes = [
        'image' => 'defaults/default-admin.png'
    ];
    protected $hidden = ['password'];
    public $trans_fields = ['name'];
    public function role(){
        return $this->belongsTo(Role::class);
    }
    protected function form_fields(){
        $roles = Role::all();
        $choices = [];
        foreach($roles as $role){
            $choices[$role['id']] = $role->trans('display_name');
        }

        return collect([
            'role_id' => collect(['type'=>'select', 'required' => 3, 'choices' => $choices]),
            'username' => collect(['type'=>'text', 'required'=>3]),
            'email' => collect(['type'=>'email', 'required'=>3]),
            'name' => collect(['type'=>'text','required'=>3]),
            'password' => collect(['type'=>'password', 'required'=>2]),
            'image' => collect(['type'=>'file', 'required'=>0]),
        ]);
    }
}
