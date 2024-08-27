<?php

namespace App\Policies;

class ContactMessagePolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'contact_messages';
}
