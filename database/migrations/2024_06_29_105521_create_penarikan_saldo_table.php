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
        Schema::create('penarikan_saldo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('nominal');
            $table->string('bank');
            $table->string('nomor_rekening');
            $table->string('nama_rekening');
            $table->enum('status', ["Menunggu Konfirmasi", "Sedang Diproses", "Selesai", "Ditolak"]);
            $table->text('alasan_penolakan')->nullable();
            $table->json('bukti_transfer')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan_saldo');
    }
};