<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePesertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('peserta')) ?? false;
    }

    /**
     * Aturan `unique` mengecualikan record yang sedang diedit agar
     * menyimpan ulang tanpa perubahan tidak memicu error duplikat.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $peserta = $this->route('peserta');

        return [
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\p{L}\s.\']+$/u'],
            'email' => ['required', 'string', 'email:rfc', 'max:150', Rule::unique('pesertas', 'email')->ignore($peserta)],
            'nik' => ['required', 'digits:16', Rule::unique('pesertas', 'nik')->ignore($peserta)],
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
        return (new StorePesertaRequest)->messages();
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return (new StorePesertaRequest)->attributes();
    }
}
