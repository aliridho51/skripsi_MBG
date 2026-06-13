<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('distribusi', function (Blueprint $table) {
            // Simpan gambar sebagai base64 agar tidak hilang saat redeploy di Railway
            $table->longText('foto_menu_data')->nullable()->after('foto_menu');
            $table->longText('foto_bukti_data')->nullable()->after('foto_bukti');
        });
    }

    public function down(): void
    {
        Schema::table('distribusi', function (Blueprint $table) {
            $table->dropColumn(['foto_menu_data', 'foto_bukti_data']);
        });
    }
};
