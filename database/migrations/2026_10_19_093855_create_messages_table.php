<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['success', 'info', 'warning', 'error'])->index();
            $table->string('title', 150);
            $table->text('body');
            $table->unsignedSmallInteger('code')->nullable();
            $table->string('url', 255)->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamps();

            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
