<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        if (Message::exists()) {
            return;
        }

        $admin = User::where('email', 'admin@sipes.test')->first();

        $contoh = [
            [Message::TYPE_SUCCESS, 'Data peserta berhasil disimpan', 'Peserta "Siti Nurhaliza" telah ditambahkan ke sistem.', 201, true],
            [Message::TYPE_WARNING, 'Validasi gagal (3 kesalahan)', 'NIK harus terdiri dari 16 digit angka. Email sudah terdaftar dalam sistem.', 422, true],
            [Message::TYPE_ERROR, 'Email sudah terdaftar dalam sistem', 'Percobaan menyimpan data dengan email duplikat ditolak.', 422, false],
            [Message::TYPE_INFO, 'Selamat datang di SIPES', 'Aplikasi pendataan peserta siap digunakan.', null, true],
            [Message::TYPE_SUCCESS, 'Data peserta berhasil diperbarui', 'Perubahan pada "Ahmad Fauzi" telah disimpan.', null, false],
        ];

        foreach ($contoh as $index => [$type, $title, $body, $code, $isRead]) {
            Message::create([
                'user_id' => $admin?->id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'code' => $code,
                'url' => null,
                'is_read' => $isRead,
                'created_at' => now()->subHours($index + 1),
                'updated_at' => now()->subHours($index + 1),
            ]);
        }

        Message::factory()->count(8)->belumDibaca()->create(['user_id' => $admin?->id]);
    }
}
