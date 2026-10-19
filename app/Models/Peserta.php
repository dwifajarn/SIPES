<?php

namespace App\Models;

use Database\Factories\PesertaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peserta extends Model
{
    /** @use HasFactory<PesertaFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'pesertas';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nik',
        'no_telepon',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Pencarian teks pada nama, email, dan NIK.
     */
    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($keyword): void {
            $query->where('nama_lengkap', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('nik', 'like', "%{$keyword}%");
        });
    }

    /**
     * Filter berdasarkan status keaktifan.
     */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (blank($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Urutan berdasarkan kolom yang diizinkan.
     */
    public function scopeUrutkan(Builder $query, ?string $kolom, ?string $arah): Builder
    {
        $kolomDiizinkan = ['nama_lengkap', 'tanggal_lahir', 'created_at'];

        if (! in_array($kolom, $kolomDiizinkan, true)) {
            return $query->latest();
        }

        return $query->orderBy($kolom, $arah === 'asc' ? 'asc' : 'desc');
    }

    public function labelUsia(): int
    {
        return (int) $this->tanggal_lahir->age;
    }

    public function labelStatus(): string
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    public function labelJenisKelamin(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
