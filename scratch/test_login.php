<?php
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$credentials = ['name' => 'admin', 'password' => 'admin'];
if (Auth::attempt($credentials)) {
    echo "LOGIN_SUCCESS: Authenticated as " . Auth::user()->name . "\n";
} else {
    echo "LOGIN_FAILED\n";
}
