@echo off
:: ResQ Redis Setup - Batch Wrapper
echo ========================================
echo     ResQ Redis Setup for Windows
echo ========================================
echo.

:: Check if running as administrator (needed for some operations)
net session >nul 2>&1
if %errorLevel% == 0 (
    echo Running with administrator privileges.
) else (
    echo Note: Some operations may require administrator privileges.
    echo If installation fails, please run as Administrator.
    echo.
)

:: Run the PowerShell script
powershell -ExecutionPolicy Bypass -File "%~dp0setup-redis.ps1"

if %errorLevel% neq 0 (
    echo.
    echo ========================================
    echo Setup failed with error code: %errorLevel%
    echo ========================================
    pause
    exit /b %errorLevel%
)
