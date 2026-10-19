<?php

namespace App\Http\Requests;

use App\Models\Peserta;
use Illuminate\Foundation\Http\FormRequest;

class StorePesertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Peserta::class) ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\p{L}\s.\']+$/u'],
            'email' => ['required', 'string', 'email:rfc', 'max:150', 'unique:pesertas,email'],
            'nik' => ['required', 'digits:16', 'unique:pesertas,nik'],
            'no_telepon' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'tanggal_lahir' => ['required', 'date', 'before:-17 years'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string', 'min:10', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.min' => 'Nama lengkap minimal 3 karakter.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 100 karakter.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 150 karakter.',
            'email.unique' => 'Email sudah terdaftar dalam sistem.',

            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar dalam sistem.',

            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Nomor telepon hanya boleh berisi angka (10–15 digit).',

            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal tidak valid.',
            'tanggal_lahir.before' => 'Usia minimal 17 tahun.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'Nama lengkap',
            'email' => 'Email',
            'nik' => 'NIK',
            'no_telepon' => 'Nomor telepon',
            'tanggal_lahir' => 'Tanggal lahir',
            'jenis_kelamin' => 'Jenis kelamin',
            'alamat' => 'Alamat',
            'status' => 'Status',
        ];
    }
}
