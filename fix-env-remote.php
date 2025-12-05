<?php
/**
 * Remote .env Fix Script
 * This script connects to Hostinger via SSH and fixes the .env file
 */

$hostinger_host = '72.60.224.71';
$hostinger_user = 'u933773389';
$hostinger_port = 22;
$target_path = '/home/u933773389/domains/gombesshub.com/public_html';

// Path to private key
$ssh_key_path = getenv('HOME') . '/.ssh/gombe_hostinger';

// Build SSH command
$ssh_commands = [
    "cd {$target_path}",
    "cp .env .env.backup.\$(date +%s)",
    "sed -i 's|APP_URL=.*|APP_URL=https://gombesshub.com|' .env",
    "sed -i 's|APP_ENV=.*|APP_ENV=production|' .env",
    "sed -i 's|APP_DEBUG=.*|APP_DEBUG=false|' .env",
    "php artisan config:clear",
    "php artisan config:cache",
    "php artisan route:clear",
    "php artisan route:cache",
    "echo '=== FIX APPLIED ==='",
    "grep -E 'APP_URL|APP_ENV|APP_DEBUG' .env",
    "tail -5 storage/logs/laravel.log"
];

$command = implode(' && ', $ssh_commands);

// Build SSH connection command
$ssh_cmd = "ssh -i {$ssh_key_path} -p {$hostinger_port} {$hostinger_user}@{$hostinger_host} \"{$command}\"";

echo "Connecting to Hostinger and applying fixes...\n";
echo "Command: {$ssh_cmd}\n\n";

$output = shell_exec($ssh_cmd . ' 2>&1');
echo $output;
