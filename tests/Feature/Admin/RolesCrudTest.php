<?php

namespace Tests\Feature\Admin;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class RolesCrudTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    protected $seed = true;
    private $adminRepository;
    private $roleRepository;
    private $sampleData;
    private $admin;
    protected function setUp(): void
    {
        parent::setUp();
        $this->adminRepository = $this->app->make(AdminRepositoryInterface::class);
        $this->roleRepository = $this->app->make(RoleRepositoryInterface::class);
        $this->sampleData = [
            'name' => "holla",
            'display_name' => "holla"
        ];
        $this->admin = $this->adminRepository->getBy('name', 'admin');
        $this->refreshApplicationWithLocale('en');
        $this->session(['admin_otp_valid' => encrypt($this->admin->google2fa_secret)]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_roles_crud_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.roles.index'));
        $response->assertStatus(200);
    }
    public function test_roles_create_role_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.roles.create'));
        $response->assertStatus(200);
    }
    public function test_roles_create_role()
    {
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.roles.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_add', ['thing' => __('global.role')]));
        $role = $this->roleRepository->all()->reverse()->first();
        $this->assertEquals($role->name, $this->sampleData['name']);
        $this->assertEquals($role->display_name, $this->sampleData['display_name']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.roles.store'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'display_name']);
        // Unique name  for roles
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.roles.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
    public function test_roles_edit_role_accessible()
    {
        $role = $this->roleRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.roles.edit', ['role' => $role->id]));
        $response->assertStatus(200);
    }
    public function test_roles_update_role()
    {
        $role = $this->roleRepository->create($this->sampleData);
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.roles.update', ['role' => $role->id]), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_edit', ['thing' => __('global.role')]));
        $this->assertEquals($role->name, $this->sampleData['name']);
        $this->assertEquals($role->display_name, $this->sampleData['display_name']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.roles.update', ['role' => $role->id]), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'display_name']);
        // unique names for roles
        $this->roleRepository->create([
            'name' => "holla1",
            'display_name' => "holla"
        ]);
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.roles.update', ['role' => $role->id]), ['name' => 'holla1', 'display_name' => "holla"]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
    public function test_roles_delete_role()
    {
        // Working example
        $role = $this->roleRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.roles.destroy', ['role' => $role->id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.role')]));
        $this->assertNull($role->fresh());
    }
    public function test_roles_batch_delete_roles()
    {
        $role = $this->roleRepository->create($this->sampleData);
        $this->sampleData['name'] = "Hollaaaaa";
        $role1 = $this->roleRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.roles.batch_destroy'), [
            'bulk_delete' => json_encode([$role->id, $role1->id])
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.role')]));
        $this->assertNull($role->fresh());
        $this->assertNull($role1->fresh());
    }
}
