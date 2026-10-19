<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PesertaValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    /**
     * Data peserta yang seluruhnya valid.
     *
     * @return array<string, string>
     */
    private function dataValid(array $override = []): array
    {
        return array_merge([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@contoh.com',
            'nik' => '3201234567890123',
            'no_telepon' => '081234567890',
            'tanggal_lahir' => now()->subYears(25)->format('Y-m-d'),
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'status' => 'aktif',
        ], $override);
    }

    #[Test]
    public function form_kosong_menghasilkan_pesan_wajib_untuk_semua_field(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), []);

        $response->assertSessionHasErrors([
            'nama_lengkap', 'email', 'nik', 'no_telepon',
            'tanggal_lahir', 'jenis_kelamin', 'alamat', 'status',
        ]);

        $this->assertCount(8, session('errors')->getBag('default')->all());
        $this->assertSame('Nama lengkap wajib diisi.', session('errors')->first('nama_lengkap'));
        $this->assertSame('Email wajib diisi.', session('errors')->first('email'));
        $this->assertSame('NIK wajib diisi.', session('errors')->first('nik'));
    }

    #[Test]
    public function nama_kurang_dari_tiga_karakter_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'nama_lengkap' => 'Ab',
        ]));

        $response->assertSessionHasErrors(['nama_lengkap' => 'Nama lengkap minimal 3 karakter.']);
    }

    #[Test]
    public function nama_mengandung_angka_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'nama_lengkap' => 'Budi123',
        ]));

        $response->assertSessionHasErrors(['nama_lengkap' => 'Nama lengkap hanya boleh berisi huruf dan spasi.']);
    }

    #[Test]
    public function format_email_tidak_valid_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'email' => 'budi@@mail',
        ]));

        $response->assertSessionHasErrors(['email' => 'Format email tidak valid.']);
    }

    #[Test]
    public function email_duplikat_ditolak(): void
    {
        Peserta::factory()->create(['email' => 'sudah@ada.com']);

        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'email' => 'sudah@ada.com',
        ]));

        $response->assertSessionHasErrors(['email' => 'Email sudah terdaftar dalam sistem.']);
    }

    #[Test]
    public function nik_kurang_dari_16_digit_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'nik' => '320123456789012',
        ]));

        $response->assertSessionHasErrors(['nik' => 'NIK harus terdiri dari 16 digit angka.']);
    }

    #[Test]
    public function nik_duplikat_ditolak(): void
    {
        Peserta::factory()->create(['nik' => '3201234567890199']);

        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'nik' => '3201234567890199',
        ]));

        $response->assertSessionHasErrors(['nik' => 'NIK sudah terdaftar dalam sistem.']);
    }

    #[Test]
    public function nomor_telepon_mengandung_huruf_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'no_telepon' => '0812-abcd',
        ]));

        $response->assertSessionHasErrors(['no_telepon' => 'Nomor telepon hanya boleh berisi angka (10–15 digit).']);
    }

    #[Test]
    public function usia_di_bawah_17_tahun_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'tanggal_lahir' => now()->subYears(15)->format('Y-m-d'),
        ]));

        $response->assertSessionHasErrors(['tanggal_lahir' => 'Usia minimal 17 tahun.']);
    }

    #[Test]
    public function alamat_kurang_dari_10_karakter_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid([
            'alamat' => 'Jl. A',
        ]));

        $response->assertSessionHasErrors(['alamat' => 'Alamat minimal 10 karakter.']);
    }

    #[Test]
    public function old_input_dipertahankan_setelah_validasi_gagal(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('peserta.create'))
            ->post(route('peserta.store'), $this->dataValid([
                'nama_lengkap' => 'Budi Santoso',
                'email' => 'bukan-email',
            ]));

        $response->assertRedirect(route('peserta.create'));
        $response->assertSessionHasInput('nama_lengkap', 'Budi Santoso');
    }

    #[Test]
    public function kegagalan_validasi_tercatat_di_pusat_pesan(): void
    {
        $this->actingAs($this->admin)->post(route('peserta.store'), []);

        $this->assertDatabaseHas('messages', [
            'type' => Message::TYPE_ERROR,
            'code' => 422,
        ]);
    }

    #[Test]
    public function data_valid_tersimpan(): void
    {
        $response = $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid());

        $response->assertRedirect(route('peserta.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pesertas', [
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@contoh.com',
            'nik' => '3201234567890123',
        ]);
    }

    #[Test]
    public function pesan_sukses_tercatat_setelah_penyimpanan_berhasil(): void
    {
        $this->actingAs($this->admin)->post(route('peserta.store'), $this->dataValid());

        $this->assertDatabaseHas('messages', [
            'type' => Message::TYPE_SUCCESS,
            'title' => 'Data peserta berhasil disimpan',
        ]);
    }

    #[Test]
    public function edit_tanpa_mengubah_email_dan_nik_tidak_menghasilkan_error_duplikat(): void
    {
        $peserta = Peserta::factory()->create([
            'email' => 'tetap@contoh.com',
            'nik' => '3201234567890123',
        ]);

        $response = $this->actingAs($this->admin)->put(route('peserta.update', $peserta), $this->dataValid([
            'email' => 'tetap@contoh.com',
            'nik' => '3201234567890123',
            'nama_lengkap' => 'Nama Baru Setelah Edit',
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('peserta.index'));

        $this->assertDatabaseHas('pesertas', [
            'id' => $peserta->id,
            'nama_lengkap' => 'Nama Baru Setelah Edit',
        ]);
    }
}
