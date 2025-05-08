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
        Schema::create('pengajuan_denda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penyewa');
            $table->unsignedBigInteger('id_toko');
            $table->string('nomor_order');
            $table->unsignedBigInteger('id_peraturan');
            $table->text('penjelasan');
            $table->unsignedBigInteger('nominal_denda');
            $table->unsignedBigInteger('sisa_denda');
            $table->json('foto_bukti'); //Bentuk ['path.jpg', 'path2.jpg']
            $table->enum('status', ['pending', 'diproses', 'lunas', 'penyewa_kabur', 'dibatalkan', 'ditolak']);
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->foreign('id_penyewa')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_toko')->references('id')->on('tokos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_peraturan')->references('id')->on('peraturan_sewa_toko')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nomor_order')->references('nomor_order')->on('order_penyewaan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_denda');
    }
};