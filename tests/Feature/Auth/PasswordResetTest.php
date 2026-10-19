<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Lupa Password?');
        $response->assertSee('Kirim Tautan Reset Password');
    }

    #[Test]
    public function test_forgot_password_validation_in_indonesian(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => '',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email wajib diisi.',
        ]);

        $responseInvalid = $this->post('/forgot-password', [
            'email' => 'bukan-email',
        ]);

        $responseInvalid->assertSessionHasErrors([
            'email' => 'Format email tidak valid.',
        ]);
    }

    #[Test]
    public function test_forgot_password_user_not_found_message_in_indonesian(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'tidakada@example.com',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Pengguna dengan alamat email tersebut tidak ditemukan.',
        ]);
    }

    #[Test]
    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
        $response->assertSessionHas('status', 'Kami telah mengirimkan tautan reset password ke email Anda.');
    }

    #[Test]
    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertStatus(200);
            $response->assertSee('Atur Ulang Password');
            $response->assertSee('Simpan Password Baru');

            return true;
        });
    }

    #[Test]
    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            $response->assertSessionHas('status', 'Password Anda telah berhasil diperbarui.');

            return true;
        });
    }

    #[Test]
    public function test_reset_password_invalid_token_error_in_indonesian(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/reset-password', [
            'token' => 'token-palsu-123',
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Token pengaturan ulang password ini tidak valid atau sudah kedaluwarsa.',
        ]);
    }
}
