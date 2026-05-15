<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table): void {
            $table->id('id_pemesanan');
            $table->unsignedBigInteger('id_villa');
            $table->unsignedBigInteger('id_customer');
            $table->string('metode_pembayaran');
            $table->datetime('tanggal_pemesanan');
            $table->enum('status', ['menunggu', 'dibayar', 'checked_in', 'checked_out', 'dibatalkan'])
                ->default('menunggu');
            $table->datetime('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('id_villa')
                ->references('id_villa')->on('villa')
                ->cascadeOnDelete();

            $table->foreign('id_customer')
                ->references('id_customer')->on('customer')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};