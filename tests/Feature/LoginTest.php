<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()->assertSee('Masuk ke toko');
    }

    public function test_valid_username_and_password_authenticate_the_cashier(): void
    {
        $this->seed();
        $user = User::where('username', 'kasir1')->firstOrFail();

        $response = $this->post(route('login.store'), ['username' => 'kasir1', 'password' => '1234']);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_return_to_login_with_an_error(): void
    {
        User::factory()->create(['username' => 'kasir1', 'password' => '1234']);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'username' => 'kasir1',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'))->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_from_protected_pages(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_cashier_can_log_out(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
