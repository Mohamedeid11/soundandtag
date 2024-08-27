<?php

namespace Tests\Feature\Admin;

use App\Http\Middleware\OtpIsValid;
use App\Http\Middleware\OtpNotValid;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Session;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;

    protected $seed = true;
    private $admin;
    public function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::where(['name' => 'admin'])->first();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_login_page_accessible()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get(route('admin.get_login'));
        $response->assertStatus(200);
    }

    public function test_admin_login()
    {
        $this->refreshApplicationWithLocale('en');
        $this->session(['admin_otp_valid' => encrypt($this->admin->google2fa_secret)]);

        // Right Example
        $response = $this->post(route('admin.post_login'), [
            'username' => $this->admin->username,
            'password' => 'sownd-admn-2022'
        ]);

        $response->assertStatus(302);
        $response->assertLocation(route('admin.2fa.twoFactorAuthQr'));

        $this->post(route('admin.post_logout'));
    }
    public function test_admin_cannot_login()
    {
        // Wrong Example
        $this->get(route('admin.get_login'));
        $response = $this->post(route('admin.post_login'), [
            'username' => 'wrong',
            'password' => 'wrong'
        ]);
        $response->assertStatus(302);
        $response->assertLocation(route('admin.get_login'));
    }

    public function test_admin_logout()
    {
        $this->refreshApplicationWithLocale('en');

        // Wrong -> Non logged in users  cannot logout -> Should redirect to admin login page
        $response = $this->post(route('admin.post_logout'));
        $response->assertStatus(302);
        $response->assertLocation(route('admin.fake_get_login'));
        // Right
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.post_logout'));
        $response->assertStatus(302);
        $response->assertLocation(route('admin.2fa.twoFactorAuthOTP'));
    }

    public function test_admin_dashboard_accessible()
    {
        $this->refreshApplicationWithLocale('en');
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.get_dashboard'));
        $response->assertStatus(302);
    }
}
