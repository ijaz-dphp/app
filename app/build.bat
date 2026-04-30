@echo off
REM Medical Lab Reports Android App - Build Script for Windows
REM This script automates the Android app build process

setlocal enabledelayedexpansion

echo.
echo ========================================================
echo   Medical Lab Reports - Android App Build Script
echo   https://belldial.softarts.co/
echo ========================================================
echo.

REM Check if build.gradle exists
if not exist "build.gradle" (
    echo ERROR: build.gradle not found!
    echo Please run this script from the app root directory
    pause
    exit /b 1
)

echo [OK] Found build.gradle
echo.

REM Check if gradlew.bat exists
if not exist "gradlew.bat" (
    echo WARNING: gradlew.bat not found
    echo Attempting to use system gradle...
)

echo Build Options:
echo 1) Debug APK (for testing on device^)
echo 2) Release APK (for Google Play Store^)
echo 3) Install debug APK on connected device
echo 4) Clean build cache
echo.

set /p OPTION="Select option (1-4): "

if "%OPTION%"=="1" (
    echo.
    echo Building Debug APK...
    echo This may take 2-5 minutes...
    echo.
    call gradlew.bat assembleDebug
    echo.
    echo [OK] Debug APK built successfully!
    echo Location: app\build\outputs\apk\debug\app-debug.apk
    echo.
    echo To install on device:
    echo   adb install -r build\outputs\apk\debug\app-debug.apk
    goto end
)

if "%OPTION%"=="2" (
    echo.
    echo Building Release APK...
    echo This may take 3-5 minutes...
    echo.
    call gradlew.bat assembleRelease
    echo.
    echo [OK] Release APK built successfully!
    echo Location: app\build\outputs\apk\release\app-release.apk
    echo.
    echo Next steps:
    echo 1. Sign the APK (Android Studio: Build -^> Generate Signed Bundle/APK^)
    echo 2. Upload to Google Play Store
    goto end
)

if "%OPTION%"=="3" (
    echo.
    echo Building and installing on device...
    echo Make sure:
    echo   1. Device is connected via USB
    echo   2. USB Debugging is enabled
    echo   3. Developer mode is on
    echo.
    set /p CONTINUE="Continue? (y/n): "
    if /i "!CONTINUE!"=="y" (
        call gradlew.bat installDebug
        echo.
        echo [OK] App installed on device!
        echo Look for "Medical Lab Reports" app on your device
    )
    goto end
)

if "%OPTION%"=="4" (
    echo.
    echo Cleaning build cache...
    call gradlew.bat clean
    echo [OK] Build cache cleaned!
    goto end
)

echo Invalid option!
pause
exit /b 1

:end
echo.
echo Done!
echo.
echo Need help?
echo   See: INSTANT_BUILD.md
echo   See: README.md
echo   See: QUICK_START.md
echo.
pause
