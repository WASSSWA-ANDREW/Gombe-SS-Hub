<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('email', 'superadmin@gombess.edu.ng')->first();
if ($user) {
    $user->update(['role' => 'super_admin']);
    echo "User role updated to 'super_admin'\n";
} else {
    echo "User not found\n";
}
