<?php

namespace Tests\Feature\Admin;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesCrudTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    protected $seed = true;
    private $adminRepository;
    private $pageRepository;
    private $sampleData;
    private $admin;
    protected function setUp(): void
    {
        parent::setUp();
        $this->adminRepository = $this->app->make(AdminRepositoryInterface::class);
        $this->pageRepository = $this->app->make(PageRepositoryInterface::class);
        $this->sampleData = [
            'name' => "holla",
            'content' => "holla"
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
    public function test_pages_crud_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.pages.index'));
        $response->assertStatus(200);
    }
    public function test_pages_create_page_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.pages.create'));
        $response->assertStatus(200);
    }
    public function test_pages_create_page()
    {
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.pages.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_add', ['thing' => __('global.page')]));
        $page = $this->pageRepository->all()->reverse()->first();
        $this->assertEquals($page->name, $this->sampleData['name']);
        $this->assertEquals($page->content, $this->sampleData['content']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.pages.store'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'content']);
        // Unique name  for pages
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.pages.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
    public function test_pages_edit_page_accessible()
    {
        $page = $this->pageRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.pages.edit', ['page' => $page->id]));
        $response->assertStatus(200);
    }
    public function test_pages_update_page()
    {
        $page = $this->pageRepository->create($this->sampleData);
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.pages.update', ['page' => $page->id]), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_edit', ['thing' => __('global.page')]));
        $this->assertEquals($page->name, $this->sampleData['name']);
        $this->assertEquals($page->content, $this->sampleData['content']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.pages.update', ['page' => $page->id]), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'content']);
    }
    public function test_pages_delete_page()
    {
        // Working example
        $page = $this->pageRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.pages.destroy', ['page' => $page->id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.page')]));
        $this->assertNull($page->fresh());
    }
    public function test_pages_batch_delete_pages()
    {
        $page = $this->pageRepository->create($this->sampleData);
        $this->sampleData['name'] = "Hollaaaaa";
        $page1 = $this->pageRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.pages.batch_destroy'), [
            'bulk_delete' => json_encode([$page->id, $page1->id])
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.page')]));
        $this->assertNull($page->fresh());
        $this->assertNull($page1->fresh());
    }
}
