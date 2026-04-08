# ResQ Redis Setup Script for Windows
# This script installs Redis server and configures PHP extension

$ErrorActionPreference = "Stop"
Write-Host "=== ResQ Redis Setup ===" -ForegroundColor Green

# Step 1: Check PHP version and extension directory
Write-Host "`n[1/4] Checking PHP configuration..." -ForegroundColor Cyan
$phpVersion = php -r "echo PHP_VERSION;"
$phpExtDir = php -r "echo ini_get('extension_dir');"
$phpThreadSafe = php -r "echo ZEND_THREAD_SAFE ? 'ts' : 'nts';"
$phpArch = php -r "echo PHP_INT_SIZE == 8 ? 'x64' : 'x86';"

Write-Host "   PHP Version: $phpVersion"
Write-Host "   Extension Dir: $phpExtDir"
Write-Host "   Thread Safe: $phpThreadSafe"
Write-Host "   Architecture: $phpArch"

# Step 2: Download and install PHP Redis extension
Write-Host "`n[2/4] Installing PHP Redis extension..." -ForegroundColor Cyan

$redisDllUrl = "https://windows.php.net/downloads/pecl/releases/redis/6.2.0/php_redis-6.2.0-8.4-ts-vs17-x64.zip"
$tempZip = "$env:TEMP\php_redis.zip"

if (-not (php -m | Select-String -Pattern "redis" -Quiet)) {
    Write-Host "   Downloading php_redis.dll..."

    try {
        Invoke-WebRequest -Uri $redisDllUrl -OutFile $tempZip -UseBasicParsing
        Expand-Archive -Path $tempZip -DestinationPath "$env:TEMP\php_redis" -Force

        # Find the dll file
        $dllFile = Get-ChildItem -Path "$env:TEMP\php_redis" -Filter "php_redis.dll" -Recurse | Select-Object -First 1

        if ($dllFile) {
            Copy-Item -Path $dllFile.FullName -Destination $phpExtDir -Force
            Write-Host "   php_redis.dll copied to: $phpExtDir" -ForegroundColor Green
        }

        # Enable extension in php.ini
        $phpIni = php -r "echo php_ini_loaded_file();"
        $iniContent = Get-Content $phpIni -Raw

        if ($iniContent -notmatch "^extension=redis") {
            Add-Content -Path $phpIni -Value "`nextension=redis"
            Write-Host "   Added 'extension=redis' to php.ini" -ForegroundColor Green
        }

        Remove-Item $tempZip -Force -ErrorAction SilentlyContinue
        Remove-Item "$env:TEMP\php_redis" -Recurse -Force -ErrorAction SilentlyContinue

    } catch {
        Write-Warning "Failed to download Redis extension automatically."
        Write-Host "   Please manually download from: https://pecl.php.net/package/redis" -ForegroundColor Yellow
    }
} else {
    Write-Host "   PHP Redis extension already installed!" -ForegroundColor Green
}

# Step 3: Download and setup Redis server (using Memurai or tporadowski)
Write-Host "`n[3/4] Setting up Redis server..." -ForegroundColor Cyan

$redisPath = "C:\Redis"
$redisExe = "$redisPath\redis-server.exe"

if (-not (Test-Path $redisExe)) {
    Write-Host "   Creating Redis directory: $redisPath"
    New-Item -ItemType Directory -Path $redisPath -Force | Out-Null

    $redisServerUrl = "https://github.com/tporadowski/redis/releases/download/v5.0.14.1/Redis-x64-5.0.14.1.zip"
    $redisZip = "$env:TEMP\redis-server.zip"

    try {
        Write-Host "   Downloading Redis server..."
        Invoke-WebRequest -Uri $redisServerUrl -OutFile $redisZip -UseBasicParsing
        Expand-Archive -Path $redisZip -DestinationPath $redisPath -Force
        Remove-Item $redisZip -Force -ErrorAction SilentlyContinue
        Write-Host "   Redis server installed to: $redisPath" -ForegroundColor Green

        # Add to PATH
        $currentPath = [Environment]::GetEnvironmentVariable("Path", "User")
        if ($currentPath -notlike "*$redisPath*") {
            [Environment]::SetEnvironmentVariable("Path", "$currentPath;$redisPath", "User")
            Write-Host "   Added Redis to user PATH" -ForegroundColor Green
        }

    } catch {
        Write-Warning "Failed to download Redis server automatically."
        Write-Host "   Please download from: https://github.com/tporadowski/redis/releases" -ForegroundColor Yellow
    }
} else {
    Write-Host "   Redis server already installed at: $redisPath" -ForegroundColor Green
}

# Step 4: Create startup script
Write-Host "`n[4/4] Creating helper scripts..." -ForegroundColor Cyan

$startScript = @"
@echo off
echo Starting Redis Server...
"$redisPath\redis-server.exe" "$redisPath\redis.windows.conf"
"@

$startScript | Out-File -FilePath "$redisPath\start-redis.bat" -Encoding ASCII
Write-Host "   Created: $redisPath\start-redis.bat" -ForegroundColor Green

# Summary
Write-Host "`n=== Setup Complete ===" -ForegroundColor Green
Write-Host "`nNext steps:"
Write-Host "   1. Restart your terminal to apply PATH changes"
Write-Host "   2. Restart your PHP server (Ctrl+C, then 'php artisan serve')"
Write-Host "   3. Run: $redisPath\start-redis.bat"
Write-Host "`nYour .env is already configured for Redis:"
Write-Host "   SESSION_DRIVER=redis"
Write-Host "   CACHE_STORE=redis"
Write-Host "   QUEUE_CONNECTION=redis"

# Check if we should start Redis now
$response = Read-Host "`nWould you like to start Redis server now? (y/n)"
if ($response -eq 'y' -or $response -eq 'Y') {
    if (Test-Path $redisExe) {
        Write-Host "`nStarting Redis Server..." -ForegroundColor Cyan
        Start-Process -FilePath $redisExe -ArgumentList "$redisPath\redis.windows.conf" -NoNewWindow
        Write-Host "   Redis server started!" -ForegroundColor Green
    } else {
        Write-Warning "Redis executable not found. Please check the installation."
    }
}

Write-Host "`nPress any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
