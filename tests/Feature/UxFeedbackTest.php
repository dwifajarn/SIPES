<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UxFeedbackTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function form_peserta_merender_indikator_validasi_realtime_dan_counter(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->get(route('peserta.create'));

        $response->assertStatus(200);
        $response->assertSee('Validasi Real-time Aktif');
        $response->assertSee('formPesertaRealtime()', false);
        $response->assertSee('16 digit');
        $response->assertSee('Pengaturan Aksesibilitas Tampilan');
    }

    #[Test]
    public function toast_notifikasi_muncul_saat_ada_pesan_flash(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        MessageService::flash(
            Message::TYPE_SUCCESS,
            'Operasi Berhasil',
            'Data uji coba berhasil diproses.'
        );

        $response = $this->actingAs($admin)->get(route('peserta.index'));

        $response->assertStatus(200);
        $response->assertSee('role="alert"', false);
        $response->assertSee('Operasi Berhasil');
        $response->assertSee('Data uji coba berhasil diproses.');
    }
}
