@echo off
echo Clearing Laravel Cache...
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
echo Cache cleared successfully!
pause