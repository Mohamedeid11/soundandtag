<?php

namespace App\Policies;

use App\Models\Admin;

class NewsletterEmailPolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'newsletter_emails';

    public function create(Admin $admin){
        return $this->doAny($admin, 'send');
    }
}
