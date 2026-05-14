<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ===== TABEL VILLA =====
        Schema::create('villa', function (Blueprint $table): void {
            $table->id('id_villa');
            $table->unsignedBigInteger('id_owner')->nullable();
            $table->string('nama_villa');
            $table->text('deskripsi')->nullable();
            $table->string('kota');
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('provinsi')->nullable();
            $table->decimal('harga', 15, 2);
            $table->integer('kapasitas');
            $table->integer('jumlah_kamar')->default(1);
            $table->integer('jumlah_kamar_mandi')->default(1);
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'nonaktif'])->default('pending');
            $table->decimal('ulasan', 3, 1)->nullable();
            $table->text('alamat');
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->timestamps();
        });

        // ===== TABEL ULASAN =====
        Schema::create('ulasan', function (Blueprint $table): void {
            $table->id('id_ulasan');
            $table->unsignedBigInteger('id_villa');
            $table->unsignedBigInteger('id_customer');
            $table->tinyInteger('rating'); // 1-5
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->unique(['id_villa', 'id_customer']);

            $table->foreign('id_villa')
                ->references('id_villa')->on('villa')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
        Schema::dropIfExists('villa');
    }
};