<?php
/**
 * Script untuk migrasi foto lama ke base64 di database.
 * Jalankan dari root project: php migrate_foto_base64.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Distribusi;
use Illuminate\Support\Facades\DB;

echo "=== Migrasi Foto ke Base64 ===\n\n";

// Migrasi foto_menu
$distribusiDenganFoto = Distribusi::whereNotNull('foto_menu')
    ->whereNull('foto_menu_data')
    ->get();

echo "Ditemukan " . $distribusiDenganFoto->count() . " distribusi dengan foto_menu tanpa base64.\n";

foreach ($distribusiDenganFoto as $d) {
    $path = public_path($d->foto_menu);
    if (file_exists($path)) {
        $mime = mime_content_type($path);
        $base64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        DB::table('distribusi')->where('id', $d->id)->update(['foto_menu_data' => $base64]);
        echo "  [OK] ID {$d->id}: Foto menu berhasil dikonversi ke base64.\n";
    } else {
        echo "  [SKIP] ID {$d->id}: File tidak ditemukan di {$path}\n";
    }
}

// Migrasi foto_bukti
$distribusiDenganBukti = Distribusi::whereNotNull('foto_bukti')
    ->whereNull('foto_bukti_data')
    ->get();

echo "\nDitemukan " . $distribusiDenganBukti->count() . " distribusi dengan foto_bukti tanpa base64.\n";

foreach ($distribusiDenganBukti as $d) {
    $path = public_path($d->foto_bukti);
    if (file_exists($path)) {
        $mime = mime_content_type($path);
        $base64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        DB::table('distribusi')->where('id', $d->id)->update(['foto_bukti_data' => $base64]);
        echo "  [OK] ID {$d->id}: Foto bukti berhasil dikonversi ke base64.\n";
    } else {
        echo "  [SKIP] ID {$d->id}: File tidak ditemukan di {$path}\n";
    }
}

echo "\nSelesai!\n";
