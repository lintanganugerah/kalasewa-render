<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeraturanPlatformTable extends Migration
{
    public function up()
    {
        Schema::create('peraturan_platform', function (Blueprint $table) {
            $table->id();
            $table->text('Judul');
            $table->longText('Peraturan');
            $table->enum('Audience', ['umum', 'penyewa', 'pemilik sewa'])->default('umum');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peraturan_platform');
    }
}