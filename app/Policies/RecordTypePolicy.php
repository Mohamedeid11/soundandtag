<?php

namespace App\Policies;

class RecordTypePolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'record_types';
    protected $hasSystemProtection = true;
    protected $hasSystemProtectionErrorMessage = 'admin.no_edit_sys_record_types';
}
