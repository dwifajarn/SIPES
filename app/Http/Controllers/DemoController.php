<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function index(Request $request): View
    {
        return view('demo.index');
    }

    public function picuError500(): never
    {
        throw new \RuntimeException('Kegagalan sistem yang sengaja dipicu dari halaman demo.');
    }

    public function picuError404(): never
    {
        abort(404, 'Data yang Anda cari tidak ditemukan.');
    }

    public function picuError403(): never
    {
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }

    /**
     * Tampilkan ulang halaman demo dengan pesan contoh tanpa mencatat ke database.
     */
    public function contoh(Request $request, string $tipe): RedirectResponse
    {
        $contoh = [
            Message::TYPE_SUCCESS => ['Data berhasil diproses', 'Perubahan Anda telah tersimpan dengan aman.'],
            Message::TYPE_INFO => ['Pembaruan sistem', 'Aplikasi akan menjalani pemeliharaan pada 20.00 WIB.'],
            Message::TYPE_WARNING => ['Kuota hampir penuh', 'Anda telah memakai 90% dari kapasitas penyimpanan.'],
            Message::TYPE_ERROR => ['Gagal memproses data', 'Terjadi kesalahan saat menyimpan. Silakan coba lagi.'],
        ];

        abort_unless(isset($contoh[$tipe]), 404);

        session()->flash('pesan', [
            'type' => $tipe,
            'title' => $contoh[$tipe][0],
            'body' => $contoh[$tipe][1],
        ]);

        return redirect()->route('demo.index');
    }
}
