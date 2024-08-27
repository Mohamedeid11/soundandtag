<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditAdminProfile;
use App\Services\Admin\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request){
        $health = $this->dashboardService->getHealth();
        $userStats = $this->dashboardService->getUserStats();
        return view('admin.index', compact('health', 'userStats'));
    }
    public function profile(){
        $admin = auth()->guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }
    public function updateProfile(EditAdminProfile $request){
        $this->dashboardService->updateProfile($request->all(), $request->allFiles());
        return back();
    }
}
