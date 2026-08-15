<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roles = [
    ['name' => 'admin', 'password' => 'admin'],
    ['name' => 'petugas', 'password' => 'petugas'],
    ['name' => 'warga', 'password' => 'warga'],
];

foreach ($roles as $r) {
    if (Auth::attempt($r)) {
        /** @var User $u */
        $u = Auth::user();
        echo "OK: Username={$u->name} | Role={$u->role} | CanManagePayments=" . ($u->canManagePayments() ? 'YES' : 'NO') . " | CanManageSettings=" . ($u->canManageSettings() ? 'YES' : 'NO') . "\n";
    } else {
        echo "FAIL for " . $r['name'] . "\n";
    }
}
