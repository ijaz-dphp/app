#!/bin/bash

# Medical Lab Reports Android App - Build Script
# This script automates the Android app build process

set -e

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║  Medical Lab Reports - Android App Build Script               ║"
echo "║  https://belldial.softarts.co/                               ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the app directory
if [ ! -f "build.gradle" ]; then
    echo -e "${RED}Error: build.gradle not found!${NC}"
    echo "Please run this script from the app root directory:"
    echo "cd /home/nginx/domains/belldial.softarts.co/public/app"
    exit 1
fi

echo -e "${GREEN}✓ Found build.gradle${NC}"
echo ""

# Check if gradlew exists
if [ ! -f "gradlew" ]; then
    echo -e "${YELLOW}⚠ gradlew not found. Creating...${NC}"
    mkdir -p gradle/wrapper
    chmod +x gradlew 2>/dev/null || true
fi

# Make gradlew executable
chmod +x gradlew

echo -e "${GREEN}✓ Gradle wrapper ready${NC}"
echo ""

# Display options
echo "Build Options:"
echo "1) Debug APK (for testing on device)"
echo "2) Release APK (for Google Play Store)"
echo "3) Install debug APK on connected device"
echo "4) Clean build cache"
echo ""
read -p "Select option (1-4): " OPTION

case $OPTION in
    1)
        echo ""
        echo -e "${YELLOW}Building Debug APK...${NC}"
        echo "This may take 2-5 minutes..."
        echo ""
        ./gradlew assembleDebug
        echo ""
        echo -e "${GREEN}✓ Debug APK built successfully!${NC}"
        echo "Location: app/build/outputs/apk/debug/app-debug.apk"
        echo ""
        echo "To install on device:"
        echo "  adb install -r build/outputs/apk/debug/app-debug.apk"
        ;;
    
    2)
        echo ""
        echo -e "${YELLOW}Building Release APK...${NC}"
        echo "This may take 3-5 minutes..."
        echo ""
        ./gradlew assembleRelease
        echo ""
        echo -e "${GREEN}✓ Release APK built successfully!${NC}"
        echo "Location: app/build/outputs/apk/release/app-release.apk"
        echo ""
        echo "Next steps:"
        echo "1. Sign the APK (Android Studio: Build → Generate Signed Bundle/APK)"
        echo "2. Upload to Google Play Store"
        ;;
    
    3)
        echo ""
        echo -e "${YELLOW}Building and installing on device...${NC}"
        echo "Make sure:"
        echo "  1. Device is connected via USB"
        echo "  2. USB Debugging is enabled"
        echo "  3. Developer mode is on"
        echo ""
        read -p "Continue? (y/n) " -n 1 -r
        echo ""
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            ./gradlew installDebug
            echo ""
            echo -e "${GREEN}✓ App installed on device!${NC}"
            echo "Look for 'Medical Lab Reports' app on your device"
        fi
        ;;
    
    4)
        echo ""
        echo -e "${YELLOW}Cleaning build cache...${NC}"
        ./gradlew clean
        echo -e "${GREEN}✓ Build cache cleaned!${NC}"
        ;;
    
    *)
        echo -e "${RED}Invalid option!${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${GREEN}Done!${NC}"
echo ""
echo "Need help?"
echo "  See: INSTANT_BUILD.md"
echo "  See: README.md"
echo "  See: QUICK_START.md"
echo ""
