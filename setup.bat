@echo off
REM ============================================================
REM  Nivi Homes - one-command setup for a fresh clone
REM
REM  Creates the SQLite database, the default admin, default
REM  settings, and seeds the demo projects + gallery images.
REM
REM  The database and uploads are intentionally NOT in git, so
REM  this MUST be run once after cloning the repository.
REM
REM  Usage:  setup.bat
REM ============================================================

setlocal

REM --- Locate PHP -------------------------------------------------
set "PHP="
where php >nul 2>&1 && set "PHP=php"
if not defined PHP if exist "C:\xampp\php\php.exe" set "PHP=C:\xampp\php\php.exe"

if not defined PHP (
    echo [ERROR] PHP was not found.
    echo         Install XAMPP ^(https://www.apachefriends.org/^) or add PHP to your PATH,
    echo         then run this script again.
    exit /b 1
)

echo Using PHP: %PHP%
%PHP% -v
echo.

REM --- Verify the pdo_sqlite extension ----------------------------
%PHP% -m | findstr /I /C:"pdo_sqlite" >nul
if errorlevel 1 (
    echo [ERROR] The pdo_sqlite PHP extension is not enabled.
    echo         Enable "extension=pdo_sqlite" in php.ini and run this script again.
    exit /b 1
)

echo [1/2] Creating database schema, admin and settings...
%PHP% database\install.php
if errorlevel 1 (
    echo [ERROR] install.php failed. Make sure the data\ folder is writable.
    exit /b 1
)
echo.

echo [2/2] Seeding demo projects and gallery images...
%PHP% database\seed_projects.php
if errorlevel 1 (
    echo [ERROR] seed_projects.php failed. Make sure assets\uploads\projects\ is writable.
    exit /b 1
)
echo.

echo ============================================================
echo  Setup complete.
echo.
echo  Start the site:   %PHP% -S localhost:8000
echo  Public site:      http://localhost:8000/
echo  Admin panel:      http://localhost:8000/admin
echo  Admin login:      admin / Admin@123
echo ============================================================

endlocal
