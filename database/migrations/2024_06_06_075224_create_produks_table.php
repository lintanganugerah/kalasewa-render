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
        Schema::create('produks', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('id_toko')->unsigned();
            $table->string('nama_produk');
            $table->text('deskripsi_produk');
            $table->string('brand');
            $table->unsignedBigInteger('biaya_cuci')->nullable();
            $table->string('brand_wig')->nullable();
            $table->string('keterangan_wig')->nullable();
            $table->enum('grade', ['Grade 1', 'Grade 2', 'Grade 3']);
            $table->unsignedBigInteger('harga');
            $table->enum('gender', ['Pria', 'Wanita', 'Semua Gender']);
            $table->unsignedBigInteger('berat_produk');
            $table->string('ukuran_produk');
            $table->unsignedBigInteger('id_alamat')->nullable();
            $table->json('additional')->nullable();
            $table->bigInteger('id_series')->unsigned();
            $table->json('metode_kirim');
            $table->enum('status_produk', ['aktif', 'arsip', 'tidak ready'])->default('aktif');
            $table->timestamps();
            $table->softDeletes(); // Produk memakai softDeletes sehingga tidak benar benar terhapus

            $table->foreign('id_series')->references('id')->on('series')->onUpdate('cascade');
            $table->foreign('id_toko')->references('id')->on('tokos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_alamat')->references('id')->on('alamat_tambahan')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};