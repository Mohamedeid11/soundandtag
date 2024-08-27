<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\BaseService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BaseWebController extends Controller
{
    public function __construct()
    {
        $this->baseService = App::make(BaseService::class);
        $contact_emails = $this->baseService->getContactEmails();
        $contact_numbers = $this->baseService->getContactNumbers();
        $location = $this->baseService->getLocation();
        $social_links = $this->baseService->getSocialLinks();
        View::share('contact_emails', $contact_emails);
        View::share('contact_numbers', $contact_numbers);
        View::share('location', $location);
        View::share('social_links', $social_links);
        $this->middleware(function ($request, $next) {
            if (Auth::guard('user')->check()) {
                $validity_lack_reasons = $this->baseService->getValidityLackFirstReason(auth()->guard('user')->user());
                View::share('validity_lack_reasons', $validity_lack_reasons);
            }
            return $next($request);
        });
    }
}
