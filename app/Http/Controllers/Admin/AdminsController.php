<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Admins\CreateAdmin;
use App\Http\Requests\Admin\Admins\DeleteAdmin;
use App\Http\Requests\Admin\Admins\EditAdmin;
use App\Models\Admin;
use App\Services\Admin\AdminsCrudService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AdminsController extends BaseAdminController
{
    private $adminsCrudService;

    /**
     * AdminsController constructor.
     * Authorize requests using App\Policies\Admin\AdminPolicy.
     * @param AdminsCrudService $adminCrudService
     */
    public function __construct(AdminsCrudService $adminCrudService)
    {
        parent::__construct(Admin::class);
        $this->authorizeResource(Admin::class);
        $this->adminsCrudService = $adminCrudService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $admins = $this->adminsCrudService->getPaginatedAdmins();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $admin = new Admin;
        return view('admin.admins.create-edit', compact('admin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAdmin $request
     * @return RedirectResponse|Redirector
     */
    public function store(CreateAdmin $request)
    {
        $errors = $this->adminsCrudService->createAdmin($request->all(), $request->allFiles());
        if(empty($errors)){
            return redirect(route('admin.admins.index'));
        }
        else {
            return back()->withInput()->withErrors($errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Admin $admin
     * @return Factory|View
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.create-edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditAdmin $request
     * @param Admin $admin
     * @return RedirectResponse
     */
    public function update(EditAdmin $request, Admin $admin)
    {
        $errors = $this->adminsCrudService->updateAdmin($admin, $request->all(), $request->allFiles());
        if (empty($errors)) {
            return redirect(route('admin.admins.index'));
        }
        else {
            return back()->withInput()->withErrors($errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteAdmin $request
     * @param Admin $admin
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function destroy(DeleteAdmin $request, Admin $admin)
    {
        $this->adminsCrudService->deleteAdmin($admin);
        return redirect(route('admin.admins.index'));
    }

    /**
     * Batch remove specified resources from storage
     *
     * @param DeleteAdmin $request
     * @return RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteAdmin $request){
        $this->adminsCrudService->batchDeleteAdmins($request->all());
            return redirect(route('admin.admins.index'));
    }
}
