<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
//use App\Repositories\Interfaces\AdminRepositoryInterface;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminsCrudTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    protected $seed = true;
    private $admin;
    private $sampleData;
    private $adminRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->adminRepository = $this->app->make('App\Repositories\Interfaces\AdminRepositoryInterface');
        $this->admin = $this->adminRepository->getBy('name', 'admin'); // Admin::where(['name'=>'admin'])->first();
        $this->sampleData = [
            'name' => 'New Admin',
            'username' => 'newadmin',
            'role_id' => $this->admin->role_id,
            'email' => 'newadmin@gmail.com',
            'password' => 'sownd-admn-2022'
        ];
        $this->refreshApplicationWithLocale('en');
        $this->session(['admin_otp_valid' => encrypt($this->admin->google2fa_secret)]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admins_crud_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.admins.index'));
        $response->assertStatus(200);
    }
    public function test_admins_create_admin_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.admins.create'));
        $response->assertStatus(200);
    }
    public function test_admins_create_admin()
    {
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.admins.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee('Admin Added Successfully');
        $admin = $this->adminRepository->all()->reverse()->first();
        $this->assertEquals($admin->name, $this->sampleData['name']);
        $this->assertEquals($admin->username, $this->sampleData['username']);
        $this->assertEquals($admin->role_id, $this->sampleData['role_id']);
        $this->assertEquals($admin->email, $this->sampleData['email']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.admins.store'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'username', 'role_id', 'email', 'password']);
        // Unique email and username for admins
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.admins.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username', 'email']);
    }
    public function test_admins_edit_page_accessible()
    {
        $admin = $this->adminRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.admins.edit', ['admin' => $admin->id]));
        $response->assertStatus(200);
        // System Admins shouldn't be editable
        // $response = $this->actingAs($this->admin, 'admin')->get(route('admin.admins.edit', ['admin' => $this->admin->id]));
        // $response->assertStatus(403);
    }
    public function test_admins_update_admin()
    {
        $admin = $this->adminRepository->create($this->sampleData);
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.admins.update', ['admin' => $admin->id]), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee('Admin Edited Successfully');
        $admin->refresh();
        $this->assertEquals($admin->name, $this->sampleData['name']);
        $this->assertEquals($admin->username, $this->sampleData['username']);
        $this->assertEquals($admin->role_id, $this->sampleData['role_id']);
        $this->assertEquals($admin->email, $this->sampleData['email']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.admins.update', ['admin' => $admin->id]), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'username', 'role_id', 'email']);
        // Cannot update system admins
        // $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.admins.update', ['admin'=>$this->admin->id]), ['name'=>'Not Admin']);
        // $response->assertStatus(403);
        // $this->assertNotEquals($this->admin->name, 'Not Admin');
    }
    public function test_admins_delete_admin()
    {
        // Working example
        $admin = $this->adminRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.admins.destroy', ['admin' => $admin->id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee('Admin Deleted Successfully');
        $this->assertNull($admin->fresh());
        // Cannot delete system admins
        // $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.admins.destroy', ['admin' => $this->admin->id]));
        // $response->assertStatus(403);
        // Admins cannot delete themselves
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.admins.destroy', ['admin' => $this->admin->id]));
        $response->assertStatus(403);
    }
    public function test_admins_batch_delete_admins()
    {
        $admin = $this->adminRepository->create($this->sampleData);
        $anotherSample = $this->sampleData;
        $anotherSample['username'] = 'anothersample';
        $anotherSample['email'] = 'anothersample@gmail.com';
        $anotherAdmin = $this->adminRepository->create($anotherSample);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.admins.batch_destroy'), [
            'bulk_delete' => json_encode([$admin->id, $anotherAdmin->id])
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee('Admins Deleted Successfully');
        $this->assertNull($admin->fresh());
        $this->assertNull($anotherAdmin->fresh());

        $admin = $this->adminRepository->create($this->sampleData);
        // Cannot delete system admins
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.admins.batch_destroy'), ['bulk_delete' => json_encode([$this->admin->id])]);
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertNotNull($this->admin->fresh());
        // Admins Cannot Delete themselves
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.admins.batch_destroy'), ['bulk_delete' => json_encode([$this->admin->id])]);
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertNotNull($admin->fresh());
    }
}
