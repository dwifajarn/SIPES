<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Daftar Sekarang');
    }

    #[Test]
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame(User::ROLE_USER, $user->role);
    }

    #[Test]
    public function test_registration_validation_errors_in_indonesian(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'email-salah',
            'password' => 'pass',
            'password_confirmation' => 'beda',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'Nama wajib diisi.',
            'email' => 'Format email tidak valid.',
            'password' => 'Konfirmasi password tidak cocok.',
        ]);
    }

    #[Test]
    public function test_duplicate_email_registration_rejected(): void
    {
        User::factory()->create(['email' => 'kembar@example.com']);

        $response = $this->post('/register', [
            'name' => 'Orang Lain',
            'email' => 'kembar@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email sudah terdaftar dalam sistem.',
        ]);
    }
}
