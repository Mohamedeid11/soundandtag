<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactMessagesCrudTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    protected $seed = true;
    private $admin;
    private $sampleData;
    private $contactMessageRepository;
    private $adminRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->contactMessageRepository = $this->app->make("App\Repositories\Interfaces\ContactMessageRepositoryInterface");
        $this->adminRepository = $this->app->make('App\Repositories\Interfaces\AdminRepositoryInterface');
        $this->admin = $this->adminRepository->getBy('name', 'admin');
        $this->sampleData = [
            'name' => 'Muhammed Abu Treka',
            'email' => 'abutreka@alahly.com',
            'message' => 'This is a very long text, maybe more than 20 chars.'
        ];
        $this->refreshApplicationWithLocale('en');
        $this->session(['admin_otp_valid' => encrypt($this->admin->google2fa_secret)]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_contact_messages_crud_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.contact_messages.index'));
        $response->assertStatus(200);
    }
    public function test_contact_messages_view_one()
    {
        $contactMessage = $this->contactMessageRepository->create($this->sampleData);
        $contactMessage->refresh();
        $this->assertFalse($contactMessage->read);
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.contact_messages.show', ['contact_message' => $contactMessage->id]));
        $response->assertStatus(200);
        $contactMessage->refresh();
        $response->assertSee($contactMessage->name);
        $response->assertSee($contactMessage->email);
        $response->assertSee($contactMessage->message);
        $this->assertTrue($contactMessage->read);
    }
    public function test_contact_messages_delete_one()
    {
        // Working example
        $contactMessage = $this->contactMessageRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.contact_messages.destroy', ['contact_message' => $contactMessage->id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.contact_message')]));
        $this->assertNull($contactMessage->fresh());
    }
    public function test_contact_messages_delete_many()
    {
        $contactMessage = $this->contactMessageRepository->create($this->sampleData);
        $contactMessage2 = $this->contactMessageRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.contact_messages.batch_destroy'), [
            'bulk_delete' => json_encode([$contactMessage->id, $contactMessage2->id])
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.contact_messages')]));
        $this->assertNull($contactMessage2->fresh());
        $this->assertNull($contactMessage2->fresh());
    }
}
