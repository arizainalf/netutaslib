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
        Schema::create('peminjaman_tahunans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_siswa');
            $table->string('kode')->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['0', '1'])->default('0');
            $table->date('tanggal_kembali')->nullable();
            $table->timestamps();

            $table->foreign('id_kelas')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_mapel')->references('id')->on('mapels')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id')->on('siswas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_tahunans');
    }
};
