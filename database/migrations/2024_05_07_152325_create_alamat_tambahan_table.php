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
        Schema::create('alamat_tambahan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->index(); //id nya nanti unixTime + idToko
            $table->unsignedBigInteger('id_toko');
            $table->string('nama');
            $table->text('alamat');
            $table->string('kode_pos');
            $table->string('kota');
            $table->string('provinsi');
            $table->timestamps();

            $table->foreign('id_toko')->references('id')->on('tokos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat_tambahan');
    }
};