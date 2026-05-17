<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kritik_saran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->onDelete('cascade');
            $table->foreignId('distribusi_id')->nullable()->constrained('distribusi')->onDelete('set null');
            $table->enum('kategori', ['Kualitas Makanan', 'Ketepatan Waktu', 'Pelayanan Petugas', 'Porsi Makanan', 'Lainnya'])->default('Lainnya');
            $table->tinyInteger('rating')->unsigned()->default(5); // 1-5 stars
            $table->text('komentar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kritik_saran');
    }
};
