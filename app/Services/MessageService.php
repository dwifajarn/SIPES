<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Satu-satunya pintu keluar untuk pesan sistem.
 *
 * Setiap pemanggilan mencatat pesan ke tabel `messages` sekaligus
 * menaruhnya di flash session agar langsung tampil di layar pengguna.
 */
class MessageService
{
    /**
     * Catat pesan sistem dan tampilkan sebagai flash message.
     */
    public static function notify(
        string $type,
        string $title,
        string $body,
        ?int $code = null,
        ?string $url = null,
    ): void {
        self::flash($type, $title, $body);
        self::catat($type, $title, $body, $code, $url);
    }

    /**
     * Tampilkan pesan tanpa mencatat ke database.
     */
    public static function flash(string $type, string $title, string $body): void
    {
        session()->flash('pesan', [
            'type' => $type,
            'title' => $title,
            'body' => $body,
        ]);
    }

    /**
     * Simpan pesan ke database. Kegagalan pencatatan tidak boleh
     * menggagalkan operasi utama pengguna.
     */
    public static function catat(
        string $type,
        string $title,
        string $body,
        ?int $code = null,
        ?string $url = null,
    ): ?Message {
        try {
            return Message::create([
                'user_id' => Auth::id(),
                'type' => $type,
                'title' => mb_substr($title, 0, 150),
                'body' => $body,
                'code' => $code,
                'url' => $url,
                'is_read' => false,
            ]);
        } catch (Throwable $exception) {
            Log::warning('Gagal mencatat pesan sistem.', [
                'type' => $type,
                'title' => $title,
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Catat kegagalan validasi lengkap dengan daftar pesannya.
     *
     * @param  array<string, array<int, string>>  $errors
     */
    public static function catatValidasiGagal(array $errors, ?string $url = null, ?string $asal = null): void
    {
        $jumlah = count($errors);
        $ringkasan = collect($errors)->flatten()->take(5)->implode(' ');

        self::catat(
            Message::TYPE_ERROR,
            "Validasi gagal ({$jumlah} kesalahan)",
            $ringkasan,
            422,
            $asal ?? $url,
        );
    }

    public static function jumlahBelumDibaca(): int
    {
        try {
            return Message::belumDibaca()->count();
        } catch (Throwable) {
            return 0;
        }
    }
}
