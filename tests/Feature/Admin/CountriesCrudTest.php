<?php

namespace Tests\Feature\Admin;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CountriesCrudTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    protected $seed = true;
    private $admin;
    private $sampleData;
    private $adminRepository;
    private $countryRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->adminRepository =  $this->app->make(AdminRepositoryInterface::class);
        $this->countryRepository = $this->app->make(CountryRepositoryInterface::class);
        $this->admin = $this->adminRepository->getBy('name', 'admin');
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $this->sampleData = [
            'name' => 'Mit Mohsen',
            'nationality' => 'Mit Mohsenian',
            'key' => '050',
            'code' => 'MM',
            'image' => $file
        ];
        $this->refreshApplicationWithLocale('en');
        $this->session(['admin_otp_valid' => encrypt($this->admin->google2fa_secret)]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_countries_crud_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.countries.index'));
        $response->assertStatus(200);
    }
    public function test_countries_create_country_accessible()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.countries.create'));
        $response->assertStatus(200);
    }
    public function test_countries_create_country()
    {
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.countries.store'), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_add', ['thing' => __('global.country')]));
        $country = $this->countryRepository->all()->reverse()->first();
        $this->assertEquals($country->name, $this->sampleData['name']);
        $this->assertEquals($country->nationality, $this->sampleData['nationality']);
        $this->assertEquals($country->key, $this->sampleData['key']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.countries.store'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'nationality', 'key', 'image']);
    }
    public function test_countries_edit_page_accessible()
    {
        $country = $this->countryRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.countries.edit', ['country' => $country->id]));
        $response->assertStatus(200);
    }
    public function test_countries_update_country()
    {
        $country = $this->countryRepository->create($this->sampleData);
        // Working example
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.countries.update', ['country' => $country->id]), $this->sampleData);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_edit', ['thing' => __('global.country')]));
        $this->assertEquals($country->name, $this->sampleData['name']);
        $this->assertEquals($country->nationality, $this->sampleData['nationality']);
        $this->assertEquals($country->key, $this->sampleData['key']);
        // Wrong Input -> validation errors
        $response = $this->actingAs($this->admin, 'admin')->patch(route('admin.countries.update', ['country' => $country->id]), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'nationality', 'key']);
    }
    public function test_countries_delete_country()
    {
        // Working example
        $country = $this->countryRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.countries.destroy', ['country' => $country->id]));
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.country')]));
        $this->assertNull($country->fresh());
    }
    public function test_countries_batch_delete_countries()
    {
        $country = $this->countryRepository->create($this->sampleData);
        $country1 = $this->countryRepository->create($this->sampleData);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.countries.batch_destroy'), [
            'bulk_delete' => json_encode([$country->id, $country1->id])
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response = $this->followRedirects($response);
        $response->assertSee(__('admin.success_delete', ['thing' => __('global.country')]));
        $this->assertNull($country->fresh());
        $this->assertNull($country1->fresh());
    }
    public function test_countries_toggle_country_active()
    {
        $country = $this->countryRepository->create($this->sampleData);
        $country->refresh();
        $before_status = $country->active;
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->admin->createToken('token')->plainTextToken)->post(route('admin.api.countries.toggle_active', [
            'country_id' => $country->id
        ]));
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 1,
            'message' => __("admin.status_success")
        ]);
        $country->refresh();
        $after_status = $country->status;
        $this->assertNotEquals($before_status, $after_status);
    }
}
