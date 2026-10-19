<?php

namespace Database\Seeders;

use App\Models\Peserta;
use Illuminate\Database\Seeder;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        if (Peserta::exists()) {
            return;
        }

        Peserta::factory()
            ->count(28)
            ->create();

        Peserta::factory()->count(4)->nonaktif()->create();
    }
}
