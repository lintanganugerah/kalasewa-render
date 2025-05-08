<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foto_produks', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->bigInteger('id_produk')->unsigned();
            $table->timestamps();

            $table->foreign('id_produk')->references('id')->on('produks')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_produks');
    }
};
