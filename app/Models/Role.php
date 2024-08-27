<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    protected $fillable = ['name', 'display_name'];
    protected $casts = [
        'is_system' => 'boolean'
    ];
    public $trans_fields = ['display_name'];

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id', 'id', 'id');
    }
    protected function form_fields(){
        return collect([
            'name' => collect(['type'=>'text', 'required'=>3]),
            'display_name' => collect(['type'=>'text', 'required'=>3]),
            'permissions' => collect([
                'type' => 'permissions',
            ])
        ]);
    }
}
