@echo off
title Cloudflare Tunnel - Gudang TKJ
echo ========================================================
echo   Membuka Akses Publik (Tunneling) untuk Gudang TKJ
echo   Local Address: http://localhost:8000
echo ========================================================
echo.

set "CLOUDFLARED=C:\Users\nafuser\Desktop\9router\9router\bin\cloudflared.exe"

if not exist "%CLOUDFLARED%" (
    echo [ERROR] cloudflared.exe tidak ditemukan di:
    echo %CLOUDFLARED%
    pause
    exit /b 1
)

echo Menghubungkan ke Cloudflare Edge...
echo Tunggu tautan https://*.trycloudflare.com muncul di bawah:
echo.

"%CLOUDFLARED%" tunnel --url http://localhost:8000
pause
