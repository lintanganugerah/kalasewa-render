<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tiket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_tiket_id')->constrained('kategori_tiket')->cascadeOnDelete();
            $table->text('deskripsi');
            $table->enum('status', ["Menunggu Konfirmasi", "Sedang Diproses", "Selesai", "Ditolak"]);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('bukti_tiket'); // bentuk array
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });

        Schema::create('bukti_tiket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tiket_id')->constrained('tiket')->cascadeOnDelete();
            $table->text('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiket');
        Schema::dropIfExists('bukti_tiket');
    }
};