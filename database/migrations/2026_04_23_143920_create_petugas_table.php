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
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Kolom kode_petugas yang wajib ada untuk seeder
            $table->string('kode_petugas', 20)->unique();
            $table->string('kendaraan', 50);
            $table->string('area_tugas', 100);
            $table->enum('status', ['Aktif', 'Sedang Mengirim', 'Non-Aktif'])->default('Aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};