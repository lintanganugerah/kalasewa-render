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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->index();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_telp')->unique()->nullable();
            $table->string('no_darurat')->nullable();
            $table->enum('ket_no_darurat', ['Teman', 'Kerabat', 'Orang Tua'])->nullable();
            $table->enum('badge', ['Banned', 'Aktif'])->default('Aktif');
            $table->string('kode_pos')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('link_sosial_media')->nullable();
            $table->string('foto_identitas')->nullable();
            $table->string('foto_diri')->nullable();
            $table->string('NIK')->nullable()->unique();
            $table->string('foto_profil')->default('storage/profiles/profil_default.jpg');
            $table->enum('role', ['penyewa', 'pemilik_sewa', 'admin', 'super_admin']);
            $table->enum('verifyIdentitas', ['Sudah', 'Tidak', 'Ditolak'])->default('Tidak');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};