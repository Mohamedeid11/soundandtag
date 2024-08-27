<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\General\ContactMessageRequest;
use App\Mail\InvitationByEmail;
use App\Mail\ThanksEmailAfterContactUsCall;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use App\Services\Web\LandingService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LandingController extends BaseWebController
{
    private $landingService;

    /**
     * LandingController constructor.
     * @param LandingService $landingService
     */
    public function __construct(LandingService $landingService)
    {
        parent::__construct();
        $this->landingService = $landingService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $personalProfiles = $this->landingService->getTopPersonalProfiles();
        $corporateProfiles = $this->landingService->getTopCorporateProfiles();
        $personalPlans = $this->landingService->getPersonalPlans();
        $corporatePlans = $this->landingService->getCorporatePlans();
        $trialPeriod = Setting::where(['name' => 'trial_days'])->first()->value;
        $is_public = auth()->guard('user')->check() ? $this->landingService->getPublicStatus(auth()->guard('user')->user()) : false;

        return view('web.index', compact('corporateProfiles', 'personalProfiles', 'personalPlans', 'corporatePlans', 'trialPeriod', 'is_public'));
    }
    public function contact()
    {
        return view('web.contact');
    }
    public function addContact(ContactMessageRequest $request)
    {
        $this->landingService->addContactMessage($request->all());
        session()->flash('success', __('global.message_sent_and_thanks'));
        return back();
    }
    public function page($page)
    {
        $page = $this->landingService->getPage($page);
        if (!$page) {
            abort(404);
        }
        return view('web.page', compact('page'));
    }
    public function about()
    {
        $page = $this->landingService->getPage('about');
        return view('web.page', compact('page'));
    }

    public function aboutUs()
    {
        $page = $this->landingService->getPage('about_us');
        return view('web.page', compact('page'));
    }

    public function pricing()
    {
        $personalPlans = $this->landingService->getPersonalPlans();
        $corporatePlans = $this->landingService->getCorporatePlans();
        $trialPeriod = Setting::where(['name' => 'trial_days'])->first()->value;
        return view('web.pricing', compact('trialPeriod', 'corporatePlans', 'personalPlans'));
    }
    public function terms()
    {
        $page = $this->landingService->getPage('terms');
        return view('web.page', compact('page'));
    }
    public function profiles(Request $request)
    {
        $search = strip_tags($request->input('search'));
        $account_type = strip_tags($request->input('account_type'));
        $profiles = $this->landingService->getAllProfiles($search, $account_type);
        return view('web.profiles', compact('profiles', 'search'));
    }

    public function corporateEmployees(Request $request, User $company)
    {
        $user = auth()->guard('user')->user();
        $search = $request->input('search') ? strip_tags($request->input('search')) : null;
        $employees = $this->landingService->getPublicEmployeesOfCorporate($company, $search);
        $categories = $this->landingService->getAllCategories();
        return view('web.account.corporate.employees-public', compact('employees', 'company', 'categories', 'user'));
    }

    public function faqs()
    {
        $faqs = $this->landingService->getAllFaqs();
        return view('web.faqs', compact('faqs'));
    }
    public function guides()
    {
        $guides = $this->landingService->getAllGuides();
        return view('web.guides', compact('guides'));
    }

    public function inviteByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'g-recaptcha-response' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        Mail::to($request->email)->send(new InvitationByEmail($request->name));
        return back()->with('success', __('global.invitation_sent'));
    }

    public function deleteTestingUsers(Request $request)
    {
        $testing_emails = ["abdallaelsayed3@gmailcom", "islam@test.com"];
        $user = User::findOrFail(auth()->id());
        if (!in_array($user->email, $testing_emails)) {
            abort(404);
        }
        auth()->guard('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($user->delete()) {
            session()->flash('success', __('global.user_deleted_successfully'));
            return redirect(route('web.get_landing'));
        }
    }
}
