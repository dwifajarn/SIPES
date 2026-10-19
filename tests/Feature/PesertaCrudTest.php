<?php

namespace Tests\Feature;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PesertaCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    #[Test]
    public function daftar_peserta_dapat_ditampilkan(): void
    {
        Peserta::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('peserta.index'));

        $response->assertOk();
        $response->assertViewHas('daftarPeserta');
    }

    #[Test]
    public function pencarian_memfilter_berdasarkan_nama(): void
    {
        Peserta::factory()->create(['nama_lengkap' => 'Siti Aminah']);
        Peserta::factory()->create(['nama_lengkap' => 'Joko Widodo']);

        $response = $this->actingAs($this->admin)->get(route('peserta.index', ['cari' => 'Siti']));

        $response->assertOk();
        $response->assertSee('Siti Aminah');
        $response->assertDontSee('Joko Widodo');
    }

    #[Test]
    public function filter_status_hanya_menampilkan_peserta_aktif(): void
    {
        Peserta::factory()->create(['nama_lengkap' => 'Peserta Aktif', 'status' => 'aktif']);
        Peserta::factory()->create(['nama_lengkap' => 'Peserta Nonaktif', 'status' => 'nonaktif']);

        $response = $this->actingAs($this->admin)->get(route('peserta.index', ['status' => 'aktif']));

        $response->assertSee('Peserta Aktif');
        $response->assertDontSee('Peserta Nonaktif');
    }

    #[Test]
    public function detail_peserta_dapat_dilihat(): void
    {
        $peserta = Peserta::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('peserta.show', $peserta));

        $response->assertOk();
        $response->assertSee($peserta->nama_lengkap);
    }

    #[Test]
    public function peserta_yang_tidak_ada_menghasilkan_404(): void
    {
        $this->actingAs($this->admin)
            ->get(route('peserta.show', 99999))
            ->assertNotFound();
    }

    #[Test]
    public function hapus_peserta_menggunakan_soft_delete(): void
    {
        $peserta = Peserta::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('peserta.destroy', $peserta));

        $response->assertRedirect(route('peserta.index'));
        $this->assertSoftDeleted('pesertas', ['id' => $peserta->id]);
    }

    #[Test]
    public function peserta_terhapus_dapat_dipulihkan(): void
    {
        $peserta = Peserta::factory()->create();
        $peserta->delete();

        $response = $this->actingAs($this->admin)->post(route('peserta.restore', $peserta->id));

        $response->assertRedirect(route('peserta.index'));
        $this->assertDatabaseHas('pesertas', [
            'id' => $peserta->id,
            'deleted_at' => null,
        ]);
    }

    #[Test]
    public function dashboard_menampilkan_statistik(): void
    {
        Peserta::factory()->count(4)->create();

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('statistik');
    }
}
