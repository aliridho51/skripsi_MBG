<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
Auth::loginUsingId(1); // Login as Admin
echo app()->call('App\Http\Controllers\AuthController@showLogin')->getTargetUrl() . "\n";
Auth::loginUsingId(2); // Login as Petugas
echo app()->call('App\Http\Controllers\AuthController@showLogin')->getTargetUrl() . "\n";
Auth::loginUsingId(3); // Login as Sekolah
echo app()->call('App\Http\Controllers\AuthController@showLogin')->getTargetUrl() . "\n";
