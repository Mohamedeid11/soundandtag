<?php

namespace Tests\Feature\Admin;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecordTypesCrudTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    protected $seed = true;
    private $adminRepository;
    private $recordTypeRepository;
    private $userRepository;
    private $sampleData;
    private $admin;
    private $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->adminRepository = $this->app->make(AdminRepositoryInterface::class);
        $this->recordTypeRepository = $this->app->make(RecordTypeRepositoryInterface::class);
        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
        $this->admin = $this->adminRepository->getBy('name', 'admin');
        $this->user = $this->userRepository->all(true)->first();
        $this->sampleData = [
            'name' => "betengan",
            'type_order' => 1,
            'user_id' => $this->user->id,
            'account_type' => $this->user->account_type
        ];
        $this->refreshApplicationWithLocale('en');
        $this->session(['admin_otp_valid' => encrypt($this->admin->google2fa_secret)]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_record_types_crud_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.record_types.index'));
        $response->assertStatus(200);
    }
    public function test_record_types_create_record_type_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.record_types.create'));
        $response->assertStatus(200);
    }
    public function test_record_types_create_record_type()
    {
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.record_types.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_add', ['thing' => __('global.record_type')]));
        $recordType = $this->recordTypeRepository->all()->reverse()->first();
        $this->assertEquals($recordType->name, $this->sampleData['name']);
        $this->assertEquals($recordType->type_order, $this->sampleData['type_order']);
        $this->assertEquals($recordType->user_id, $this->sampleData['user_id']);
        $this->assertEquals($recordType->account_type, $this->sampleData['account_type']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.record_types.store'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'type_order', 'account_type']);
        // Unique name,type_order  for record_types
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.record_types.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'type_order']);
        // matching record_type and user account_type i.e a personal account's user cannot add a corporate record type
        $recordType->delete();
        $this->sampleData['account_type'] = $this->sampleData['account_type'] == 'personal' ? 'corporate' : 'personal';
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.record_types.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['account_type']);
    }
    public function test_record_types_edit_record_type_accessible()
    {
        $recordType = $this->recordTypeRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.record_types.edit', ['record_type' => $recordType->id]));
        $response->assertStatus(200);
    }
    public function test_record_types_update_record_type()
    {
        $recordType = $this->recordTypeRepository->create($this->sampleData);
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.record_types.update', ['record_type' => $recordType->id]), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_edit', ['thing' => __('global.record_type')]));
        $this->assertEquals($recordType->name, $this->sampleData['name']);
        $this->assertEquals($recordType->type_order, $this->sampleData['type_order']);
        $this->assertEquals($recordType->user_id, $this->sampleData['user_id']);
        $this->assertEquals($recordType->account_type, $this->sampleData['account_type']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.record_types.update', ['record_type' => $recordType->id]), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'type_order', 'account_type']);
        // matching record_type and user account_type i.e a personal account's user cannot add a corporate record type
        $this->sampleData['account_type'] = $this->sampleData['account_type'] == 'personal' ? 'corporate' : 'personal';
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.record_types.update', ['record_type' => $recordType->id]), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['account_type']);
        // test unique
        $otherSampleData = [
            'name' => "betengan1",
            'type_order' => 2,
            'user_id' => $this->user->id,
            'account_type' => $this->user->account_type
        ];
        $this->recordTypeRepository->create($otherSampleData);
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.record_types.update', ['record_type' => $recordType->id]), $otherSampleData);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['type_order', 'name']);
    }
    public function test_record_types_delete_record_type()
    {
        // Working example
        $recordType = $this->recordTypeRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.record_types.destroy', ['record_type' => $recordType->id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.record_type')]));
        $this->assertNull($recordType->fresh());
    }
    public function test_record_types_batch_delete_record_types()
    {
        $recordType = $this->recordTypeRepository->create($this->sampleData);
        $this->sampleData['name'] = "Hollaaaaa";
        $this->sampleData['type_order'] = 5;
        $recordType1 = $this->recordTypeRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.record_types.batch_destroy'), [
            'bulk_delete' => json_encode([$recordType->id, $recordType1->id])
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.record_type')]));
        $this->assertNull($recordType->fresh());
        $this->assertNull($recordType1->fresh());
    }
}
