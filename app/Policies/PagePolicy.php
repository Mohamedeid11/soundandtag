<?php

namespace App\Policies;

class PagePolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'pages';
    protected $systemAdminRequired = true;
    protected $systemProtectionActions = ['delete'];
}
