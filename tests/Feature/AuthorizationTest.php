<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function dataValid(): array
    {
        return [
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@contoh.com',
            'nik' => '3201234567890123',
            'no_telepon' => '081234567890',
            'tanggal_lahir' => now()->subYears(25)->format('Y-m-d'),
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'status' => 'aktif',
        ];
    }

    #[Test]
    public function tamu_dialihkan_ke_login(): void
    {
        $this->get(route('peserta.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function tamu_tidak_bisa_menyimpan_peserta(): void
    {
        $this->post(route('peserta.store'), $this->dataValid())
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('pesertas', 0);
    }

    #[Test]
    public function user_biasa_tidak_bisa_membuka_form_tambah_peserta(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)
            ->get(route('peserta.create'))
            ->assertForbidden();
    }

    #[Test]
    public function user_biasa_tidak_bisa_menyimpan_peserta(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)
            ->post(route('peserta.store'), $this->dataValid())
            ->assertForbidden();

        $this->assertDatabaseCount('pesertas', 0);
    }

    #[Test]
    public function user_biasa_tidak_bisa_mengubah_peserta(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $peserta = Peserta::factory()->create();

        $this->actingAs($user)
            ->put(route('peserta.update', $peserta), $this->dataValid())
            ->assertForbidden();
    }

    #[Test]
    public function user_biasa_tidak_bisa_menghapus_peserta(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $peserta = Peserta::factory()->create();

        $this->actingAs($user)
            ->delete(route('peserta.destroy', $peserta))
            ->assertForbidden();

        $this->assertDatabaseHas('pesertas', ['id' => $peserta->id, 'deleted_at' => null]);
    }

    #[Test]
    public function user_biasa_tetap_bisa_melihat_daftar_peserta(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)->get(route('peserta.index'))->assertOk();
    }

    #[Test]
    public function user_biasa_tidak_bisa_membuka_pusat_pesan(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)->get(route('pesan.index'))->assertForbidden();
    }

    #[Test]
    public function admin_bisa_membuka_pusat_pesan(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get(route('pesan.index'))->assertOk();
    }

    #[Test]
    public function admin_bisa_menandai_pesan_sudah_dibaca(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $pesan = Message::factory()->belumDibaca()->create(['user_id' => $admin->id]);

        $this->actingAs($admin)
            ->patch(route('pesan.dibaca', $pesan))
            ->assertRedirect();

        $this->assertTrue($pesan->fresh()->is_read);
    }

    #[Test]
    public function halaman_demo_menampilkan_empat_tipe_pesan(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->get(route('demo.index'));

        $response->assertOk();
        $response->assertSee('Data berhasil disimpan');
        $response->assertSee('Pembaruan sistem');
        $response->assertSee('Kuota hampir penuh');
        $response->assertSee('Gagal memproses data');
    }

    #[Test]
    public function halaman_404_kustom_ditampilkan(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->get('/halaman-yang-tidak-ada');

        $response->assertNotFound();
        $response->assertSee('Halaman Tidak Ditemukan');
    }

    #[Test]
    public function login_gagal_menampilkan_pesan_generik(): void
    {
        User::factory()->create([
            'email' => 'admin@contoh.com',
            'password' => 'password',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'admin@contoh.com',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }
}
