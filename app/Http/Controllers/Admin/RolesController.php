<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\CreateRole;
use App\Http\Requests\Admin\Roles\DeleteRole;
use App\Http\Requests\Admin\Roles\EditRole;
use App\Models\Role;
use App\Services\Admin\RolesCrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class RolesController extends BaseAdminController
{
private $rolesCrudService;
    /**
     * RolesController constructor.
     * Authorize requests using App\Policies\Admin\RolePolicy
     */
    public function __construct(RolesCrudService $rolesCrudService)
    {
        parent::__construct(Role::class);
        $this->authorizeResource(Role::class);
        $this->rolesCrudService = $rolesCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $roles = $this->rolesCrudService->getAllRoles();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $role = new Role;
        return view('admin.roles.create-edit', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRole $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateRole $request)
    {
        $this->rolesCrudService->createRole($request->all());
        return redirect(route('admin.roles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return Application|Factory|View|Response
     */
    public function edit(Role $role)
    {
        return view('admin.roles.create-edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditRole $request
     * @param Role $role
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditRole $request, Role $role)
    {
        $this->rolesCrudService->updateRole($role, $request->all());
        return redirect(route('admin.roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRole $request
     * @param Role $role
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(DeleteRole $request, Role $role)
    {
        $this->rolesCrudService->deleteRole($role);
        return redirect(route('admin.roles.index'));
    }
    /**
     *  Batch remove specified resources from storage
     *
     * @param DeleteRole $request
     * @return RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteRole $request){
        $this->rolesCrudService->batchDeleteRoles($request->all());
        return redirect(route('admin.roles.index'));
    }
}
