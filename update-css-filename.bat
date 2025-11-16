@echo off
echo Updating CSS filename in css-check.js...

REM Get the current CSS filename from manifest.json
for /f "tokens=3 delims=:" %%a in ('findstr /C:"file" "public\build\manifest.json"') do (
    set cssfile=%%a
    goto :found
)

:found
REM Remove quotes and comma from the filename
set cssfile=%cssfile:"=%
set cssfile=%cssfile:,=%
set cssfile=%cssfile: =%

echo Current CSS filename: %cssfile%

REM Update the css-check.js file
powershell -Command "(Get-Content public\css-check.js) -replace '/build/assets/app-.*\.css', '%cssfile%' | Set-Content public\css-check.js"

echo CSS filename updated successfully!
pause