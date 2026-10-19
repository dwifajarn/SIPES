<?php

namespace Database\Factories;

use App\Models\Peserta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peserta>
 */
class PesertaFactory extends Factory
{
    protected $model = Peserta::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lengkap' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'nik' => fake()->unique()->numerify('################'),
            'no_telepon' => fake()->numerify('08##########'),
            'tanggal_lahir' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'alamat' => fake()->address(),
            'status' => fake()->randomElement(['aktif', 'aktif', 'aktif', 'nonaktif']),
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(['status' => 'nonaktif']);
    }
}
