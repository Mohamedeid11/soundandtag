<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Account\EditDetailsRequest;
use App\Http\Requests\Web\Account\PaymentRequest;
use App\Http\Requests\Web\Account\PreparePaymentRequest;
use App\Http\Requests\Web\Profile\ChangePasswordRequest;
use App\Lib\Payments\Credimax\PaymentPreparer as CredimaxPaymentPreparer;
use App\Models\User;
use App\Services\Traits\UserValidityTrait;
use App\Services\Web\AccountService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
// use Barryvdh\DomPDF\Facade\Pdf as PDF;
use PDF;

class AccountController extends BaseWebController
{
    use UserValidityTrait;

    private $accountService;

    /**
     * AccountController constructor.
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        parent::__construct();
        $this->accountService = $accountService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = auth()->guard('user')->user();
        $records = $this->getRecords($user)['available_record_types'];
        $countries = $this->accountService->getCountries();
        return view('web.account.index', compact('user', 'records', 'countries'));
    }

    /**
     * @param EditDetailsRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function editDetails(EditDetailsRequest $request)
    {
        $this->accountService->editDetails($request->all(), $request->allFiles());

        return redirect(route('account.edit'));
    }

    /**
     * @param ChangePasswordRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $errors = $this->accountService->changePassword($request->all());
        if (!empty($errors)) {
            return redirect(route('account.edit') . "#password-change")->withErrors($errors)->withInput();
        }
        return redirect(route('account.edit') . "#password-change");
    }
    public function accountStatus()
    {
        $user = auth()->guard('user')->user();
        $reasons = $this->accountService->getValidityLackReasons($user);
        $trial = $user->isInTrial(True);
        $plans = $this->accountService->getPlans();
        $upgradePlans = $this->accountService->getUpgradePlans();
        return view('web.account.status', compact('user', 'reasons', 'trial', 'plans', 'upgradePlans'));
    }
    public function toggleStatus()
    {
        $this->accountService->toggleStatus();
        return back();
    }
    public function getPaymentData($username, PaymentRequest $request)
    {
        $this->accountService->processPayment($username, $request->all(), $request->headers);
        session()->flash('success', 'Payment completed successfully .');
        return back();
    }
    public function accountDangerZone()
    {
        $user = auth()->guard('user')->user();
        return view('web.account.danger', compact('user'));
    }
    public function checkAccountValidity()
    {
        $id = auth()->guard('user')->id();
        $user = User::find($id);
        return $user->validity ? 1 : 0;
    }
    public function deleteAccount()
    {
        $this->accountService->deleteAccount();
        auth()->guard('user')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('web.get_landing'));
    }
    public function preparePaymentCredi(PreparePaymentRequest $request)
    {
        $plan = $this->accountService->getPlan($request->all());
        $availablePlans = auth()->user()->getAvailablePlans($request->upgrade)->pluck('id')->toArray();
        if (in_array($plan->id, $availablePlans)) {

            $payment_preparer = app()->make(CredimaxPaymentPreparer::class, ['plan' => $plan, 'upgrade' => $request->upgrade]);

            return ['status' => 1, 'preparer' => $payment_preparer];
        }
        return ['status' => 0];
    }
    public function downloadInvoice()
    {
        $user = auth()->user();
        $pdf = PDF::loadView('web.account.invoice-template', compact('user'));
        // return $pdf->download('invoice.pdf');
        return $pdf->stream('invoice.pdf');
    }
}
