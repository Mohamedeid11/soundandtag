<?php

namespace App\Policies;

class RolePolicy extends AdminResourceBasePolicy
{
    protected $systemAdminRequired = true;
    protected $hasSystemProtection = true;
    protected $hasSystemProtectionErrorMessage = 'admin.sysroles_cannot_edited';
}
