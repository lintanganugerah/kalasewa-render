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
        Schema::create('alasan_penolakan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->enum('penolakan', ['refund', 'withdraw', 'verify', 'ban_account', 'delete_account']);
            $table->text('alasan_penolakan');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('alasan_penolakan');
    }
};
