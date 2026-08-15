<?php
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('name', 'admin')->first();
Auth::login($user);

echo "LOGGED_IN_USER: " . Auth::user()->name . " | ROLE: " . Auth::user()->role . "\n";
echo "APP_TITLE: " . AppSetting::get('app_title') . "\n";
echo "APP_SUBTITLE: " . AppSetting::get('app_subtitle') . "\n";
echo "APP_ADDRESS: " . AppSetting::get('app_address') . "\n";
echo "APP_PERIODE: " . AppSetting::get('app_periode') . "\n";
