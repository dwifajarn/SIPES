# SIPES — Sistem Pendataan Peserta

Aplikasi web pendataan peserta dengan fokus pada **kualitas pesan sistem**: validasi input yang jelas, pesan error berbahasa Indonesia, dan riwayat pesan yang dapat diaudit.

Tugas mata kuliah **Interaksi Manusia & Komputer (IMK)** — Semester 7.

---

## Daftar Isi

- [Fitur](#fitur)
- [Persyaratan](#persyaratan)
- [Instalasi](#instalasi)
- [Akun Demo](#akun-demo)
- [Pengujian Validasi (untuk Laporan)](#pengujian-validasi-untuk-laporan)
- [Arsitektur](#arsitektur)
- [Penanganan Pesan & Error](#penanganan-pesan--error)
- [Menjalankan Test](#menjalankan-test)
- [Catatan Produksi](#catatan-produksi)

---

## Fitur

| Modul | Kemampuan |
|---|---|
| **Autentikasi** | Login, register, logout, rate limit 5 percobaan/menit |
| **Data Peserta** | CRUD lengkap, pencarian, filter status, sorting, pagination |
| **Soft Delete** | Hapus tanpa kehilangan data (dapat dipulihkan) |
| **Validasi** | 8 field dengan pesan Bahasa Indonesia spesifik |
| **Pesan Sistem** | Log semua aktivitas, filter tipe, tandai dibaca |
| **Halaman Error** | 403, 404, 500, 503 kustom dan ramah pengguna |
| **Hak Akses** | Admin (penuh) dan User (read-only) |
| **Halaman Demo** | Galeri 4 tipe pesan untuk dokumentasi laporan |

---

## Persyaratan

| Komponen | Versi |
|---|---|
| PHP | 8.3+ (diuji pada 8.5) |
| Composer | 2.x |
| Node.js | 18+ (diuji pada 24) |
| MySQL | 8.0 |
| Laravel | 13.x |

Ekstensi PHP yang dibutuhkan: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `zip`, `gd`, `intl`

---

## Instalasi

### 1. Clone dan install dependency

```bash
git clone <repo-url> tugasweb
cd tugasweb

composer install
npm install
```

### 2. Siapkan database

Pastikan **MySQL** sudah berjalan (di Laragon: klik **Start All**), lalu:

```bash
mysql -u root -e "CREATE DATABASE tugasweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan bagian database di `.env`:

```env
APP_NAME="SIPES"
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tugasweb
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi dan data contoh

```bash
php artisan migrate --seed
```

### 5. Build aset frontend

```bash
npm run build
```

### 6. Jalankan aplikasi

```bash
php artisan serve
```

Buka **http://127.0.0.1:8000**

> Untuk pengembangan dengan hot reload: `composer run dev` (menjalankan server + Vite + queue sekaligus).

---

## Akun Demo

| Email | Password | Peran | Hak Akses |
|---|---|---|---|
| `admin@sipes.test` | `password` | Admin | Lihat, tambah, ubah, hapus, pulihkan, pusat pesan |
| `user@sipes.test` | `password` | User | Hanya lihat data peserta |

> Kredensial ini hanya untuk lingkungan lokal akademik dan **wajib diganti** sebelum deployment nyata.

---

## Pengujian Validasi (untuk Laporan)

Login sebagai **admin**, buka **Data Peserta → Tambah Peserta**, lalu lakukan skenario berikut.

| # | Aksi | Pesan yang Muncul |
|---|---|---|
| 1 | Klik Simpan tanpa mengisi apa pun | Ringkasan *"Periksa kembali isian Anda (8 kesalahan)"* + 8 field menyala merah |
| 2 | Nama = `Ab` | "Nama lengkap minimal 3 karakter." |
| 3 | Nama = `Budi123` | "Nama lengkap hanya boleh berisi huruf dan spasi." |
| 4 | Email = `budi@@mail` | "Format email tidak valid." |
| 5 | Email sudah dipakai peserta lain | "Email sudah terdaftar dalam sistem." |
| 6 | NIK = 15 digit | "NIK harus terdiri dari 16 digit angka." |
| 7 | Telepon = `0812-abcd` | "Nomor telepon hanya boleh berisi angka (10–15 digit)." |
| 8 | Tanggal lahir = 2 tahun lalu | "Usia minimal 17 tahun." |
| 9 | Alamat = `Jl. A` | "Alamat minimal 10 karakter." |
| 10 | Edit peserta, ubah nama, email & NIK tidak diubah, simpan | **Berhasil** tanpa error duplikat |
| 11 | Isi semua dengan benar, simpan | Kotak hijau "Data peserta berhasil disimpan." |
| 12 | Hapus peserta, konfirmasi modal | "Data peserta dihapus" + masuk daftar terhapus |
| 13 | Buka `/peserta/99999` | Halaman 404 kustom |
| 14 | Login sebagai `user@sipes.test`, akses `/peserta/create` | Halaman 403 kustom |
| 15 | Logout, akses `/peserta` | Dialihkan ke halaman login |

### Halaman Demo Pesan

Buka **Demo Pesan** di sidebar (khusus admin) untuk melihat:

1. Empat tipe pesan (sukses, info, peringatan, error) sekaligus
2. Perbandingan field valid vs field error
3. Pemicu halaman 403, 404, dan 500
4. Tabel spesifikasi warna untuk dokumentasi

---

## Arsitektur

### Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/     DashboardController, PesertaController,
│   │                    PesanController, DemoController
│   ├── Middleware/      EnsureUserIsAdmin
│   └── Requests/        StorePesertaRequest, UpdatePesertaRequest
├── Models/              User, Peserta, Message
├── Policies/            PesertaPolicy, MessagePolicy
└── Services/            MessageService

resources/views/
├── components/          alert, input-error, form.input,
│                        form.select, form.textarea, nav-sidebar,
│                        badge-status, badge-tipe, dot-tipe,
│                        modal-hapus, empty-state
├── peserta/             index, form, create, edit, show, terhapus
├── pesan/               index
├── demo/                index
├── errors/              minimal, 403, 404, 419, 429, 500, 503
└── layouts/             app

database/
├── migrations/          users (+role), pesertas, messages
├── factories/           PesertaFactory, MessageFactory
└── seeders/             DatabaseSeeder, UserSeeder,
                         PesertaSeeder, MessageSeeder

lang/id/                 auth.php, validation.php
tests/Feature/           PesertaValidationTest, PesertaCrudTest,
                         AuthorizationTest, HalamanErrorTest
```

### Skema Database

**`pesertas`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| nama_lengkap | varchar(100) | Index |
| email | varchar(150) | Unique |
| nik | char(16) | Unique |
| no_telepon | varchar(20) | |
| tanggal_lahir | date | |
| jenis_kelamin | enum(L,P) | |
| alamat | text | |
| status | enum(aktif,nonaktif) | Index |
| created_at, updated_at | timestamp | |
| deleted_at | timestamp | Soft delete, index |

**`messages`**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary key |
| user_id | bigint | FK → users, nullOnDelete |
| type | enum(success,info,warning,error) | Index |
| title | varchar(150) | |
| body | text | |
| code | smallint | HTTP status (422, 201, dst) |
| url | varchar(255) | Halaman asal pesan |
| is_read | boolean | Index |

---

## Penanganan Pesan & Error

### Lapisan Validasi

```
Input pengguna
    ↓
FormRequest (StorePesertaRequest / UpdatePesertaRequest)
    ├── rules()      → aturan validasi
    ├── messages()   → pesan Bahasa Indonesia spesifik
    └── attributes() → label field yang rapi
    ↓
ValidationException
    ├── Flash errors + old input ke session
    ├── Tercatat ke tabel messages (kode 422)
    └── Redirect kembali ke form
    ↓
Tampilan
    ├── Ringkasan error di atas form
    ├── Border merah + aria-invalid pada field
    ├── Pesan spesifik di bawah field
    └── Isian sebelumnya dipertahankan
```

### Alur Pesan Sistem

```php
MessageService::notify(
    type: Message::TYPE_SUCCESS,
    title: 'Data peserta berhasil disimpan',
    body: "Peserta \"{$peserta->nama_lengkap}\" telah ditambahkan.",
    code: 201,
);
```

Metode ini:
1. Menaruh pesan di flash session (langsung tampil di layar)
2. Mencatat pesan ke tabel `messages` (muncul di Pusat Pesan)
3. **Fail-safe** — jika pencatatan gagal, operasi utama tetap berhasil

### Handler Exception Global

Dikonfigurasi di `bootstrap/app.php`:

| Exception | Perilaku |
|---|---|
| `ValidationException` | Catat ke riwayat + redirect back dengan old input |
| `AuthenticationException` | Redirect ke login + pesan "Silakan masuk terlebih dahulu" |
| `AuthorizationException` | Halaman 403 kustom |
| `NotFoundHttpException` | Halaman 404 kustom |
| `Throwable` (debug off) | Halaman 500 ramah, tanpa stack trace |

### Komponen Blade

```blade
{{-- Pesan global --}}
<x-alert type="success" title="Berhasil">Data telah disimpan.</x-alert>
<x-alert type="error" :dismissible="false">Kesalahan kritis.</x-alert>

{{-- Input dengan validasi otomatis --}}
<x-form.input name="email" label="Email" type="email" required />
<x-form.select name="status" label="Status" :options="['aktif' => 'Aktif']" required />
<x-form.textarea name="alamat" label="Alamat" :rows="3" required />

{{-- Pesan error manual --}}
<x-input-error :messages="$errors->get('email')" />
```

---

## Menjalankan Test

```bash
# Seluruh suite
php artisan test

# Hanya test validasi
php artisan test --filter=PesertaValidationTest

# Format kode
vendor/bin/pint
```

**Cakupan test (71 test, 193 assertion):**

| File | Menguji |
|---|---|
| `PesertaValidationTest` | 15 test — setiap aturan validasi, old input, log pesan, regresi edit |
| `PesertaCrudTest` | 8 test — tampil, cari, filter, detail, soft delete, restore, dashboard |
| `AuthorizationTest` | 13 test — tamu, user read-only, admin, 404, login gagal |
| `HalamanErrorTest` | 3 test — halaman 500 tanpa stack trace, 404, 403 |
| `UxFeedbackTest` | 2 test — validasi real-time, counter karakter, toast floating notification |
| `RegistrationTest` | 4 test — pendaftaran sukses, pesan validasi Indonesia, email duplikat |
| `PasswordResetTest` | 7 test — form minta link, validasi email, pesan tidak ditemukan, token salah, ganti password |

---

## Catatan Produksi

Sebelum deploy ke server produksi:

```env
APP_ENV=production
APP_DEBUG=false          # WAJIB — jika true, stack trace bocor ke pengguna
APP_URL=https://domain-anda.com
```

Langkah optimasi:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
npm run build
```

Checklist keamanan:

- [ ] `APP_DEBUG=false` sudah diset
- [ ] Password akun demo sudah diganti
- [ ] `DB_PASSWORD` tidak kosong
- [ ] HTTPS aktif
- [ ] Folder `storage/` dan `bootstrap/cache/` writable

---

## Lisensi

Proyek akademik — dibuat untuk tugas mata kuliah Interaksi Manusia & Komputer.
