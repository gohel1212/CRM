@echo off
echo Starting CRM Flutter Application...
echo.

echo Starting PHP Backend Server...
set ROOT=%~dp0
set BACKEND=%ROOT%backend
start "PHP Backend" cmd /k "cd /d %BACKEND% && php -S 127.0.0.1:3000 %BACKEND%\api_sqlite.php"

echo Waiting 3 seconds for server to start...
timeout /t 3 /nobreak > nul

echo Starting Flutter Application...
start "Flutter App" cmd /k "cd /d %ROOT% && flutter run -d chrome"

echo.
echo Both servers are starting...
echo PHP Backend: http://127.0.0.1:3000
echo Flutter App: Will open in Chrome browser
echo.
echo Press any key to exit...
pause > nul
