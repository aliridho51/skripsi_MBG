<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Auth::loginUsingId(1); // Admin
$request = Request::create('/admin/dashboard', 'GET');
$response = $kernel->handle($request);
echo "Admin accessing /admin/dashboard: Status " . $response->getStatusCode();
if ($response->isRedirection()) {
    echo " Redirects to: " . $response->headers->get('Location');
}
echo "\n";

Auth::loginUsingId(3); // Sekolah
$request = Request::create('/sekolah/dashboard', 'GET');
$response = $kernel->handle($request);
echo "Sekolah accessing /sekolah/dashboard: Status " . $response->getStatusCode();
if ($response->isRedirection()) {
    echo " Redirects to: " . $response->headers->get('Location');
}
echo "\n";

Auth::loginUsingId(2); // Petugas
$request = Request::create('/petugas/dashboard', 'GET');
$response = $kernel->handle($request);
echo "Petugas accessing /petugas/dashboard: Status " . $response->getStatusCode();
if ($response->isRedirection()) {
    echo " Redirects to: " . $response->headers->get('Location');
}
echo "\n";
