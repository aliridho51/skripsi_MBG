<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distribusi', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel sekolah dan petugas
            $table->foreignId('sekolah_id')->constrained('sekolah')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade');

            $table->date('tanggal');
            $table->text('menu_hari_ini')->nullable();
            $table->integer('target_porsi');
            $table->integer('porsi_diterima')->nullable();
            $table->time('waktu_tiba')->nullable();
            $table->enum('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan', 'Selesai', 'Ada Kendala'])->default('Belum Dikirim');
            $table->string('foto_bukti')->nullable();

            $table->timestamps();
        });
    }
};
