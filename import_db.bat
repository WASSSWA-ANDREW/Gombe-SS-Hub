@echo off
cd /d "c:\wamp64\www\Gombe SS Hub Pro"
mysql -h 127.0.0.1 -u root gombe_ss_hub < database\database_mysql.sql
if %ERRORLEVEL% EQU 0 (
    echo Import completed successfully!
) else (
    echo Import failed!
)
pause
