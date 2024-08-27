<?php
namespace App\Services\Admin;

use App\Models\Admin;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminsCrudService
{
    private $adminRepository;
    private $roleRepository;
    public function __construct(AdminRepositoryInterface $adminRepository, RoleRepositoryInterface  $roleRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedAdmins(): LengthAwarePaginator
    {
        return $this->adminRepository->paginate(100);
    }

    public function createAdmin(array $data, $files): array
    {
        $role = $this->roleRepository->get($data['role_id']);
        if (!$role){
            return ['role_id'=> __('admin.bad_role')];
        }
        $data = Arr::only($data, ['name', 'username', 'email', 'role_id', 'password', 'image']);
        $data['password'] = Hash::make($data['password']);
        if (Arr::has($files,'image')){
            $data['image'] = $files['image']->store('uploads/admins', ['disk'=>'public']);
        }
        $this->adminRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.admin')]) );
        return [];
    }

    public function updateAdmin(Admin $admin, array $data, $files): array
    {
        $role = $this->roleRepository->get($data['role_id']);
        if (!$role){
            return ['role_id'=> __('admin.bad_role')];
        }
        $data = Arr::only($data, ['name', 'username', 'email', 'role_id', 'password']);
        if(Arr::has($data, 'password')){
            $data['password'] = Hash::make($data['password']);
        }
        if (Arr::has($files,'image')){
            Storage::disk('public')->delete($admin->image);
            $data['image'] = $files['image']->store('uploads/admins', ['disk'=>'public']);
        }
        $admin->update($data);
        session()->flash('success',  __('admin.success_edit', ['thing'=>__('global.admin')]) );
        return [];
    }

    public function deleteAdmin(Admin $admin): void
    {
        $admin->delete();
        session()->flash('success', __('admin.success_delete', ['thing'=>__('global.admin')]) );
    }

    public function batchDeleteAdmins(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $user = auth()->guard('admin')->user();
        $admins = $this->adminRepository->getMany($ids);
        $self_included = $admins->where('id', '=', $user->id)->first();
        if($self_included){
            session()->flash('error', __('admin.cannot_delete_yourself'));
            return;
        }
        $system_included = $admins->where('is_system', '=', true)->first();
        if($system_included){
            session()->flash('error', __('admin.no_delete_sys_admins') );
            return;
        }
        $this->adminRepository->deleteMany($ids);
        session()->flash('success', __('admin.success_delete', ['thing'=>__('global.admins')]) );
    }

}
