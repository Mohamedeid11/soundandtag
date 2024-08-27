<?php
namespace App\Services\Admin;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Arr;

class RolesCrudService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles()
    {
        return $this->roleRepository->paginate(100);
    }

    public function createRole(array $data)
    {
        $permissions_ids = $data['permissions'] ?? [];
        $data =  Arr::only($data, ['name', 'display_name']);
        $role = $this->roleRepository->create($data);
        $role->permissions()->attach($permissions_ids);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.role')]));
    }

    public function updateRole($role, array $data)
    {
        $permissions_ids = $data['permissions'] ?? [];
        $data =  Arr::only($data, ['name', 'display_name']);
        $role->update($data);
        $role->permissions()->detach($role->permissions()->pluck('permissions.id'));
        $role->permissions()->attach($permissions_ids);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.role')]) );
    }

    public function deleteRole($role)
    {
        $role->delete();
        session()->flash('success', __('admin.success_delete', ['thing'=>__('global.role')]) );
    }

    public function batchDeleteRoles(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $wrong_role = $this->roleRepository->all(true)->whereIn('id', $ids)->where(['is_system'=>true])->first();
        if($wrong_role){
            session()->flash('error', __('admin.no_delete_sys_roles') );
        }
        else {
            $this->roleRepository->deleteMany($ids);
            session()->flash('success', __('admin.success_delete', ['thing' => __('global.role')]));
        }
    }
}
