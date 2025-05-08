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
        Schema::create('top_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_series');
            $table->unsignedBigInteger('banyak_dipesan');
            $table->timestamps();

            $table->foreign('id_series')->references('id')->on('series')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_series');
    }
};