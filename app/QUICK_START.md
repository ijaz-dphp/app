# Android App - Quick Start Guide

## ⚡ 30-Second Setup

1. **Download Android Studio**
   - https://developer.android.com/studio

2. **Configure Server URL**
   - Edit: `app/src/main/java/com/medicallab/reports/data/api/NetworkClient.kt`
   - Change: `BASE_URL = "http://192.168.1.100/belldial/"`
   - To: Your actual server IP/domain

3. **Build & Run**
   - Connect device or start emulator
   - Press `Shift + F10` to run app
   - Or: Build → Run 'app'

## 📱 Test Login Credentials

Use same credentials as your PHP system:

```
Username: doctor
Password: (your password)
```

Or any user from `manage_users.php` in the PHP system.

## 📂 Project Structure Summary

```
app/
├── src/main/
│   ├── AndroidManifest.xml          ← App permissions & activities
│   ├── java/com/medicallab/reports/
│   │   ├── data/                     ← Network & database layer
│   │   └── presentation/             ← UI activities & view models
│   └── res/                          ← Layouts, strings, colors
├── build.gradle                      ← Dependencies & build config
├── AndroidManifest.xml
├── README.md                         ← Full documentation
└── SETUP_GUIDE.md                    ← Detailed setup instructions
```

## 🎯 Main Features

✅ User Login/Logout
✅ Search Patients
✅ View Medical Reports
✅ Download Reports as PDF
✅ Manage Users (Admin)
✅ Manage Tests (Admin)
✅ Change Password

## 🔧 Configuration Files

### Network Configuration
**File**: `app/src/main/java/com/medicallab/reports/data/api/NetworkClient.kt`
- Change `BASE_URL` to your server

### App Dependencies
**File**: `app/build.gradle`
- Add/remove libraries here
- Currently includes: Retrofit, Gson, Room, Coroutines, etc.

### App Resources
**Files**: `app/src/main/res/values/`
- `strings.xml` - App text strings
- `colors.xml` - Color definitions  
- `themes.xml` - App themes

## 🚀 Build Commands

```bash
# Build debug APK
./gradlew assembleDebug

# Build release APK
./gradlew assembleRelease

# Install and run on device
./gradlew installDebug
./gradlew runDebug

# Run tests
./gradlew test
```

## 📋 Development Checklist

- [ ] Android Studio installed
- [ ] Android SDK 24+ installed
- [ ] Project opened in Android Studio
- [ ] Server URL configured
- [ ] App builds without errors
- [ ] Connected device/emulator
- [ ] App runs successfully
- [ ] Login works with test credentials

## ⚠️ Common Issues

| Issue | Solution |
|-------|----------|
| "SDK location not found" | Create `local.properties` with `sdk.dir=/path/to/sdk` |
| App won't connect to server | Check server URL in `NetworkClient.kt` |
| Login fails | Verify credentials match PHP system user |
| Gradle sync error | File → Sync Now, or delete `.gradle` folder |
| Emulator too slow | Use actual device instead |

## 📖 Documentation

- **Full Setup**: See `SETUP_GUIDE.md`
- **Architecture**: See `README.md`
- **API Endpoints**: Check `data/api/MedicalLabApi.kt`
- **Models**: Check `data/models/Models.kt`

## 🎨 UI Components

All screens use Material Design components:
- Material Buttons
- Material TextInputLayout
- Material AppBar
- Material CardView (extendable)

## 🔐 Security Notes

- No sensitive data stored in code
- API token stored in encrypted DataStore
- Uses HTTPS in production (configure in NetworkClient.kt)
- ProGuard enabled for release builds

## 🆘 Getting Help

1. Check logcat for error messages
   - View → Tool Windows → Logcat

2. Review API response
   - Add debug logs in network interceptor

3. Test on real device
   - Android Studio → Select device → Click Run

4. Check PHP backend
   - Verify endpoints respond correctly
   - Test with Postman or curl

## 📞 Next Steps

1. ✅ Configure server URL
2. ✅ Test login
3. ✅ Test patient search
4. ✅ Test report viewing
5. ✅ Generate release APK
6. ✅ Submit to Google Play Store

## 📱 Device Requirements

- **Minimum**: Android 7.0 (API 24)
- **Target**: Android 14 (API 34)
- **RAM**: 200MB minimum
- **Storage**: 50MB minimum

## 🎓 Learn More

- Android Studio Docs: https://developer.android.com/docs
- Kotlin Guide: https://kotlinlang.org/docs/
- Retrofit Guide: https://square.github.io/retrofit/
- Material Design: https://material.io/design

---

**Need more help?** See `SETUP_GUIDE.md` for detailed instructions.
