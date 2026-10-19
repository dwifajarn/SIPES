# Laporan Implementasi — Sistem Pesan & Validasi
## SIPES (Sistem Pendataan Peserta)

| Atribut | Nilai |
|---|---|
| **Mata Kuliah** | Interaksi Manusia & Komputer (IMK) |
| **Semester** | 7 |
| **Framework** | Laravel 13.37 |
| **Database** | MySQL 8.0 |
| **CSS** | Tailwind CSS v4 (Vite) |
| **Tanggal** | 19 Oktober 2026 |

---

## 1. Latar Belakang

Formulir pendataan peserta memiliki banyak aturan bisnis yang harus dipenuhi. Tanpa umpan balik yang baik, pengguna tidak tahu apa yang salah dan mengulang kesalahan yang sama. Aplikasi ini menerapkan prinsip IMK berupa **umpan balik yang jelas, spesifik, dan berbahasa manusia** pada setiap kesalahan input.

---

## 2. Prinsip IMK yang Diterapkan

| Prinsip (Nielsen) | Penerapan di Aplikasi |
|---|---|
| **Visibility of system status** | Setiap aksi memberi umpan balik segera: flash message, badge pesan belum dibaca, indikator status peserta |
| **Match between system and real world** | Pesan berbahasa Indonesia sehari-hari, bukan "ValidationException" atau kode teknis |
| **User control and freedom** | Tombol Batal di setiap form, modal konfirmasi hapus, data terhapus dapat dipulihkan |
| **Consistency and standards** | 4 tipe pesan dengan warna dan ikon konsisten di seluruh aplikasi |
| **Error prevention** | `maxlength`, `inputmode="numeric"` untuk NIK/telepon, `type="date"` dengan batas usia |
| **Recognition rather than recall** | Isian lama dipertahankan saat validasi gagal; helper text menjelaskan aturan |
| **Help users recognize, diagnose, recover from errors** | Pesan menyebut **apa** yang salah dan **bagaimana** memperbaikinya |
| **Aesthetic and minimalist design** | Satu aksi primer per layar; warna dikhususkan untuk status |
| **Accessibility** | `aria-invalid`, `aria-describedby` menghubungkan error ke field, kontras ≥ 4.5:1 |

---

## 3. Arsitektur Pesan

### 3.1 Empat Tipe Pesan

| Tipe | Warna | Ikon | Penggunaan |
|---|---|---|---|
| Success | `green-600` | check-circle | Data tersimpan, dihapus, dipulihkan |
| Info | `blue-600` | information-circle | Pemberitahuan netral, pesan belum dibaca |
| Warning | `amber-500` | exclamation-triangle | Data dihapus (masih bisa dipulihkan) |
| Error | `red-600` | x-circle | Validasi gagal, kegagalan sistem |

### 3.2 Tiga Lapisan Penyampaian Pesan

```
Lapisan 1 — Ringkasan (top-level)
    Kotak merah di atas form: "Periksa kembali isian Anda (N kesalahan)"
    + daftar semua error

Lapisan 2 — Per-field (kontekstual)
    Border merah + background merah muda
    Pesan spesifik langsung di bawah field

Lapisan 3 — Riwayat (audit)
    Tercatat di tabel messages → halaman Pusat Pesan
```

---

## 4. Daftar Pesan Validasi

### 4.1 Field Peserta

| Field | Aturan | Pesan Error |
|---|---|---|
| Nama Lengkap | Wajib, min 3, max 100, hanya huruf | "Nama lengkap wajib diisi." / "Nama lengkap minimal 3 karakter." / "Nama lengkap hanya boleh berisi huruf dan spasi." |
| Email | Wajib, format RFC, unik | "Email wajib diisi." / "Format email tidak valid." / "Email sudah terdaftar dalam sistem." |
| NIK | Wajib, tepat 16 digit, unik | "NIK wajib diisi." / "NIK harus terdiri dari 16 digit angka." / "NIK sudah terdaftar dalam sistem." |
| Nomor Telepon | Wajib, 10–15 digit angka | "Nomor telepon wajib diisi." / "Nomor telepon hanya boleh berisi angka (10–15 digit)." |
| Tanggal Lahir | Wajib, minimal 17 tahun | "Tanggal lahir wajib diisi." / "Usia minimal 17 tahun." |
| Jenis Kelamin | Wajib, L/P | "Jenis kelamin wajib dipilih." |
| Alamat | Wajib, min 10, max 255 | "Alamat wajib diisi." / "Alamat minimal 10 karakter." |
| Status | Wajib, aktif/nonaktif | "Status wajib dipilih." |

### 4.2 Autentikasi & Reset Password

| Modul | Kondisi | Pesan yang Dikeluarkan |
|---|---|---|
| **Login** | Email/password kosong | "Email wajib diisi." / "Password wajib diisi." |
| **Login** | Format email salah | "Format email tidak valid." |
| **Login** | Kredensial salah | "Email atau password yang Anda masukkan salah." (generik — tidak membocorkan mana yang salah) |
| **Login** | Percobaan > 5/menit | "Terlalu banyak percobaan masuk. Silakan coba lagi dalam N detik." |
| **Register** | Nama kosong | "Nama wajib diisi." |
| **Register** | Email duplikat | "Email sudah terdaftar dalam sistem." |
| **Register** | Konfirmasi password beda | "Konfirmasi password tidak cocok." |
| **Register** | Pendaftaran berhasil | "Selamat datang, [Nama]! Akun Anda telah berhasil dibuat." |
| **Forgot Password** | Email tidak terdaftar | "Pengguna dengan alamat email tersebut tidak ditemukan." |
| **Forgot Password** | Link berhasil dikirim | "Kami telah mengirimkan tautan reset password ke email Anda." |
| **Reset Password** | Token palsu/kadaluwarsa | "Token pengaturan ulang password ini tidak valid atau sudah kedaluwarsa." |
| **Reset Password** | Password berhasil diubah | "Password Anda telah berhasil diperbarui." |

---

## 5. Checklist Screenshot untuk Laporan

### 5.1 Halaman Utama

- [ ] **Login** — form dengan validasi
- [ ] **Dashboard** — 4 kartu statistik + pesan terbaru
- [ ] **Data Peserta** — tabel dengan pencarian dan filter

### 5.2 Pesan Validasi (Inti Laporan)

| No | Skenario | Screenshot yang Diambil |
|---|---|---|
| 1 | Form kosong → Simpan | Ringkasan "8 kesalahan" + 8 field merah |
| 2 | Nama `Ab` | "Nama lengkap minimal 3 karakter." |
| 3 | Nama `Budi123` | "Nama lengkap hanya boleh berisi huruf dan spasi." |
| 4 | Email `budi@@mail` | "Format email tidak valid." |
| 5 | Email duplikat | "Email sudah terdaftar dalam sistem." |
| 6 | NIK 15 digit | "NIK harus terdiri dari 16 digit angka." |
| 7 | Telepon `0812-abcd` | "Nomor telepon hanya boleh berisi angka (10–15 digit)." |
| 8 | Lahir 2 tahun lalu | "Usia minimal 17 tahun." |
| 9 | Alamat `Jl. A` | "Alamat minimal 10 karakter." |
| 10 | Simpan data valid | Kotak hijau "Data peserta berhasil disimpan." |

### 5.3 Pesan Sistem

- [ ] **Halaman Demo Pesan** — 4 tipe pesan sekaligus
- [ ] **Pusat Pesan** — daftar riwayat dengan badge "Baru"
- [ ] **Filter tipe Error** — hanya pesan error
- [ ] **Tandai dibaca** — perubahan status visual
- [ ] **Modal konfirmasi hapus** — peringatan sebelum aksi destruktif

### 5.4 Halaman Error

- [ ] **404** — "Halaman Tidak Ditemukan"
- [ ] **403** — "Akses Ditolak"
- [ ] **500** — "Terjadi Kesalahan Sistem" (tanpa stack trace)

### 5.5 Bukti Otorisasi

- [ ] Login sebagai `user@sipes.test` → tombol Tambah/Edit/Hapus tidak muncul
- [ ] Akses `/peserta/create` langsung via URL → halaman 403
- [ ] Akses `/pesan` → halaman 403

---

## 6. Hasil Pengujian Otomatis

```
PASS  Tests\Feature\AuthorizationTest         13 test
PASS  Tests\Feature\HalamanErrorTest           3 test
PASS  Tests\Feature\UxFeedbackTest               2 test
PASS  Tests\Feature\PesertaCrudTest            8 test
PASS  Tests\Feature\PesertaValidationTest     15 test
PASS  Tests\Feature\Auth\AuthenticationTest    6 test
PASS  Tests\Feature\Auth\EmailVerificationTest 3 test
PASS  Tests\Feature\Auth\PasswordConfirmationTest 2 test
PASS  Tests\Feature\Auth\PasswordResetTest     7 test
PASS  Tests\Feature\Auth\PasswordUpdateTest    4 test
PASS  Tests\Feature\Auth\RegistrationTest      4 test
PASS  Tests\Feature\ExampleTest                1 test
PASS  Tests\Unit\ExampleTest                   1 test

Tests:  71 passed (193 assertions)
Pint:   passed (PSR-12)
```

### Test Kunci

| Test | Membuktikan |
|---|---|
| `form_kosong_menghasilkan_pesan_wajib_untuk_semua_field` | 8 pesan wajib muncul sekaligus |
| `edit_tanpa_mengubah_email_dan_nik_tidak_menghasilkan_error_duplikat` | Regresi: edit tanpa ubah email tidak menghasilkan error palsu |
| `kegagalan_validasi_tercatat_di_pusat_pesan` | Setiap validasi gagal tercatat dengan kode 422 |
| `user_biasa_tidak_bisa_menyimpan_peserta` | Otorisasi server-side, bukan hanya menyembunyikan tombol |
| `error_500_menampilkan_halaman_ramah_saat_debug_mati` | Tidak ada stack trace bocor ke pengguna |

---

## 7. Struktur Data Pesan

Tabel `messages` mencatat setiap pesan yang dikeluarkan sistem:

| Kolom | Fungsi |
|---|---|
| `type` | Klasifikasi: success / info / warning / error |
| `title` | Judul singkat yang dapat dipindai |
| `body` | Isi lengkap pesan |
| `code` | Kode HTTP terkait (422 validasi, 201 dibuat) |
| `url` | Halaman asal pesan untuk penelusuran |
| `is_read` | Status baca untuk badge notifikasi |
| `user_id` | Siapa yang memicu pesan |

---

## 8. Kesimpulan

Aplikasi menerapkan prinsip IMK secara terukur: setiap kesalahan input dijelaskan dalam Bahasa Indonesia, ditampilkan pada tiga lapisan (ringkasan, per-field, riwayat), dan dilengkapi penanda aksesibilitas untuk pembaca layar. Validasi tidak hanya mencegah data kotor masuk, tetapi juga memandu pengguna memperbaiki kesalahannya.

Pengujian otomatis memastikan perilaku tersebut tidak berubah seiring pengembangan — termasuk regresi paling umum pada aplikasi CRUD: error duplikat palsu saat menyimpan ulang data yang tidak diubah.
