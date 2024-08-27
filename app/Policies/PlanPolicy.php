<?php

namespace App\Policies;

use App\Models\Admin;

class PlanPolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'plans';
    protected $hasSystemProtection = true;
    public function update(Admin $admin, $model=null){
        $this->hasSystemProtection = false;
        return parent::update($admin, $model);
    }
}
