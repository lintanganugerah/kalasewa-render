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
        Schema::create('peraturan_sewa_toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->unsignedBigInteger('id_toko');
            $table->boolean('terdapat_denda')->default(false);
            $table->unsignedBigInteger('denda_pasti')->nullable();
            $table->string('denda_kondisional')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_toko')->references('id')->on('tokos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peraturan_sewa_toko');
    }
};