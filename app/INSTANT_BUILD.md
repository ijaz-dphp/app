# ⚡ INSTANT APP BUILD GUIDE

## **Simplest Way (3 Steps)**

### **Step 1: Download Android Studio**
- Go to: https://developer.android.com/studio
- Download and install (choose your OS: Windows/Mac/Linux)
- Launch Android Studio

### **Step 2: Open the App Project**
In Android Studio:
1. **File** → **Open**
2. Navigate to: `/home/nginx/domains/belldial.softarts.co/public/app`
3. Click **Open**
4. Wait 2-5 minutes for gradle to sync

### **Step 3: Click Run**
- Click the green **▶ Run 'app'** button at top
- Select your device/emulator
- Wait for app to build and launch

**Done! App is running!** ✅

---

## **If You Don't Have Android Studio**

### **Option 1: Build from Command Line (Linux/Mac)**

```bash
# Navigate to app folder
cd /home/nginx/domains/belldial.softarts.co/public/app

# Make gradle executable
chmod +x gradlew

# Build APK
./gradlew assembleDebug

# Find your APK
ls -lh build/outputs/apk/debug/
```

APK location: `app/build/outputs/apk/debug/app-debug.apk`

### **Option 2: Build from Command Line (Windows)**

```bash
# Navigate to app folder
cd C:\path\to\app

# Build APK
gradlew.bat assembleDebug

# APK is at:
# app\build\outputs\apk\debug\app-debug.apk
```

---

## **Install APK on Phone**

### **Direct Install (Windows)**
```bash
# Place Android phone in USB debug mode
# Connect via USB cable

adb install -r build/outputs/apk/debug/app-debug.apk
```

### **Manual Install**
1. Build APK (see above)
2. Copy APK to phone via USB/email
3. Open file manager on phone
4. Tap APK file
5. Tap "Install"

---

## **Test the App**

Once app launches:

1. **Login Screen** appears
2. Enter credentials:
   ```
   Username: doctor
   Password: (your password)
   ```
3. Click **Login**
4. You'll see the **Dashboard**
5. Test features:
   - View Reports
   - Search Patient
   - Admin (if admin user)

---

## **What Gets Installed**

From the files in `/app/`:

- ✅ All Java/Kotlin code compiled
- ✅ All XML layouts rendered
- ✅ All resources bundled
- ✅ All dependencies downloaded
- ✅ APK created (Android Package)
- ✅ APK installed on device

**Total Size:** ~50-80 MB

---

## **Common Issues & Fixes**

### **"SDK not found"**
```
Android Studio → Tools → SDK Manager
Click "Install" next to Android 34
```

### **"Gradle sync failed"**
```
File → Sync Now
Or delete: .gradle/ folder
Try again
```

### **"Device not detected"**
```
Windows/Mac: restart adb
adb kill-server
adb start-server
```

### **"gradle: command not found"**
```
# Use gradlew instead:
./gradlew assembleDebug
```

### **App won't connect to server**
1. Check BASE_URL in:
   - `app/src/main/java/com/medicallab/reports/data/api/NetworkClient.kt`
2. Verify it says: `https://belldial.softarts.co/`
3. Rebuild app

---

## **Folder Structure Ready**

```
app/
├── src/main/
│   ├── java/com/medicallab/reports/
│   │   ├── data/api/              ✅ Created
│   │   ├── data/models/           ✅ Created
│   │   ├── data/preferences/      ✅ Created
│   │   ├── data/repository/       ✅ Created
│   │   ├── presentation/ui/       ✅ Created
│   │   └── presentation/viewmodel/✅ Created
│   └── res/
│       ├── layout/                ✅ Created (9 files)
│       └── values/                ✅ Created (3 files)
├── build.gradle                   ✅ Created
├── AndroidManifest.xml            ✅ Created
├── gradle/wrapper/                ✅ Created
└── gradlew                         ✅ Created
```

**All files ready to build!**

---

## **Quick Build Checklist**

- [ ] Install Android Studio OR have JDK 11+
- [ ] Open app folder in Android Studio
- [ ] Wait for gradle sync (see "Resolving dependencies...")
- [ ] Connect device or start emulator
- [ ] Click green Run button OR run `./gradlew runDebug`
- [ ] App launches
- [ ] Login with credentials
- [ ] See dashboard

---

## **For Release Build (Google Play)**

```bash
# Build release APK
./gradlew assembleRelease

# Or in Android Studio:
# Build → Generate Signed Bundle/APK
# Select: APK
# Create keystore (password protected)
# Build type: Release
# Done!
```

Located at: `app/build/outputs/apk/release/app-release.apk`

---

## **Troubleshooting Commands**

```bash
# Clean build
./gradlew clean

# Full rebuild
./gradlew clean assembleDebug

# Check gradle version
./gradlew --version

# View all tasks
./gradlew tasks

# Build with verbose logging
./gradlew assembleDebug --stacktrace --debug

# Update gradle wrapper
./gradlew wrapper --gradle-version=8.1
```

---

## **Real Device Setup**

### **Android Phone:**
1. Settings → Developer Options → USB Debugging (ON)
2. Connect via USB
3. Trust computer when prompted
4. Run app from Android Studio

### **Android Emulator:**
1. Android Studio → Device Manager
2. Create Virtual Device (Pixel 5, Android 14)
3. Click play button to start
4. Run app from Android Studio

---

## **Next Steps After Building**

1. ✅ Test login works
2. ✅ Test patient search
3. ✅ Test report viewing
4. ✅ Test PDF download
5. ✅ Test admin features (if admin)
6. 📦 Build release APK
7. 🚀 Upload to Google Play Store

---

## **Google Play Store Upload**

After testing and release build:

1. Sign up: https://play.google.com/console ($25 fee)
2. Create app listing
3. Upload APK
4. Add screenshots
5. Fill description
6. Submit for review

Takes 24-48 hours to approve.

---

## **Support**

**Still stuck?** 

Check these files:
- [README.md](README.md) - Full documentation
- [SETUP_GUIDE.md](SETUP_GUIDE.md) - Detailed setup
- [QUICK_START.md](QUICK_START.md) - 30-second start

---

**Ready?** 👇

### **Option A: Use Android Studio (Easiest)**
→ Download from https://developer.android.com/studio
→ Open app folder
→ Click Run ✅

### **Option B: Command Line**
```bash
cd /path/to/app
./gradlew assembleDebug
```

---

**Your app will be built and ready to use in minutes!** 🎉
