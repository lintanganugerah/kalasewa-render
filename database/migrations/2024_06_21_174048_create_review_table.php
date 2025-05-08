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
        Schema::create('review', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penyewa');
            $table->unsignedBigInteger('id_toko')->nullable();
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->text('komentar');
            $table->unsignedInteger('nilai');
            $table->json('foto')->nullable();
            $table->enum('tipe', ['review_produk', 'review_penyewa']);
            $table->timestamps();

            $table->foreign('id_penyewa')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('produks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_toko')->references('id')->on('tokos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_penyewa');
    }
};