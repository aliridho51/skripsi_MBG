<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah kolom foto_menu pada tabel distribusi
        Schema::table('distribusi', function (Blueprint $table) {
            $table->string('foto_menu')->nullable()->after('menu_hari_ini');
        });

        // Tabel pengembalian ompreng (wadah makanan)
        Schema::create('pengembalian_ompreng', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_id')->constrained('distribusi')->onDelete('cascade');
            $table->foreignId('sekolah_id')->constrained('sekolah')->onDelete('cascade');
            $table->integer('jumlah_dikirim')->default(0);
            $table->integer('jumlah_kembali')->default(0);
            $table->integer('jumlah_rusak')->default(0);
            $table->enum('kondisi', ['Baik Semua', 'Sebagian Rusak', 'Banyak Rusak'])->default('Baik Semua');
            $table->enum('status', ['Belum Dikembalikan', 'Sudah Dikembalikan'])->default('Belum Dikembalikan');
            $table->text('catatan')->nullable();
            $table->string('foto_kondisi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('distribusi', function (Blueprint $table) {
            $table->dropColumn('foto_menu');
        });
        Schema::dropIfExists('pengembalian_ompreng');
    }
};
