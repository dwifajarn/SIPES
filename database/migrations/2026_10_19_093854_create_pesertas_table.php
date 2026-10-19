<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesertas', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('email', 150)->unique();
            $table->char('nik', 16)->unique();
            $table->string('no_telepon', 20);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('deleted_at');
            $table->index('nama_lengkap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
