<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\EmployeeAddRequest;
use App\Models\Category;
use App\Models\CorporateEmployee;
use App\Models\User;
use App\Services\Web\EmployeesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmployeesController extends BaseWebController
{
    private $employeesService;
    public function __construct(EmployeesService $employeesService)
    {
        parent::__construct();
        $this->employeesService = $employeesService;
    }

    public function employees()
    {
        /** @var User $user */
        $user = auth()->guard('user')->user();
        $user->account_type === 'corporate' || abort(404);
        $invitations = $user->employees->where('employee_id', 0)->sortByDesc('created_at');
        $employees = $user->employees->where('employee_id', '!=', 0)->sortBy([
            ['category_id', 'asc'],
            ['arrange', 'asc'],
        ]);

        $categories = Category::where('active',1)->get();

        return view('web.account.corporate.employees', compact('user', 'employees', 'invitations', 'categories'));
    }

    public function PublicEmployeesArrange()
    {
        /** @var User $user */
        $user = auth()->guard('user')->user();
        $user->account_type === 'corporate' || abort(404);
        $employees = $user->employees->where('employee_id', '!=', 0)->where('public', 1)->sortBy('arrange');
        $categories = Category::where('active',1)->get();

        return view('web.account.corporate.employees-public-arrange', compact('user', 'employees', 'categories'));
    }
    public function addEmployee(EmployeeAddRequest $request)
    {
        return $this->employeesService->addEmployee($request->all());
    }

    public function addEmployeeExcel(Request $request)
    {

        return $this->employeesService->addEmployeeExcel($request->all());
    }

    public function sign(Request $request, $company, $email)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        return $company . " " . $email;
    }
    public function deleteEmployee(CorporateEmployee $employee)
    {
        $this->employeesService->deleteEmployee($employee);
        return redirect(route('account.employees'));
    }
    public function resendEmployeeInvitation(CorporateEmployee $employee)
    {
        $this->employeesService->resendEmployeeInvitation($employee);
        return redirect(route('account.employees'));
    }

    public function remindEmployeeToGoPublic(CorporateEmployee $employee)
    {
        $this->employeesService->remindEmployeeToGoPublic($employee);
        return redirect(route('account.employees'));
    }

    public function arrangeEmployees(Request $request)
    {
        $employees = $request->input("ids");
        $user = auth()->guard('user')->user();
        if ($user->account_type != 'corporate') {
            abort(404);
        }
        $arrange = $this->employeesService->arrangeEmployees($employees, $user);
        if ($arrange) {
            return response()->json([
                'status' => 'success',
                'message' => __('global.arranged_successfully')
            ]);
        }
    }
}
