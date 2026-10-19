<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HalamanErrorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function error_500_menampilkan_halaman_ramah_saat_debug_mati(): void
    {
        config(['app.debug' => false]);

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->get(route('demo.500'));

        $response->assertStatus(500);
        $response->assertSee('Terjadi Kesalahan Sistem');
        $response->assertDontSee('RuntimeException');
        $response->assertDontSee('vendor');
    }

    #[Test]
    public function error_404_menampilkan_halaman_kustom(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->get('/url-yang-tidak-ada');

        $response->assertNotFound();
        $response->assertSee('Halaman Tidak Ditemukan');
        $response->assertSee('Ke Dashboard');
    }

    #[Test]
    public function error_403_menampilkan_halaman_kustom(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $response = $this->actingAs($user)->get(route('pesan.index'));

        $response->assertForbidden();
        $response->assertSee('Akses Ditolak');
    }
}
