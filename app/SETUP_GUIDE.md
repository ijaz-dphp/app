# Android App Setup Guide

## Overview

This guide provides step-by-step instructions for setting up, configuring, and deploying the Medical Lab Reports Android application.

## Table of Contents

1. [Development Environment Setup](#development-environment-setup)
2. [Project Configuration](#project-configuration)
3. [Building the App](#building-the-app)
4. [Testing](#testing)
5. [Deployment](#deployment)

## Development Environment Setup

### System Requirements

- **OS**: Windows, macOS, or Linux
- **RAM**: Minimum 8GB (16GB recommended)
- **Disk Space**: Min 10GB free space
- **JDK**: Java 11 or higher

### Installing Android Studio

1. Download Android Studio from https://developer.android.com/studio
2. Follow the installation wizard for your OS
3. During setup, ensure you select:
   - Android SDK
   - Android Virtual Device (AVD)
   - Android SDK Platform
   - Android Emulator

### Installing Required SDK

1. Open Android Studio
2. Tools → SDK Manager
3. SDK Platforms tab: Select and install:
   - Android API 34
   - Android API 24 (minimum support)
4. SDK Tools tab: Install/Update:
   - Android SDK Build-Tools 34.0.0
   - Android Emulator
   - Android SDK Platform-Tools
   - NDK (optional for native components)

## Project Configuration

### 1. Server Configuration

Before running the app, configure the backend server URL:

**File**: `app/src/main/java/com/medicallab/reports/data/api/NetworkClient.kt`

```kotlin
object NetworkClient {
    private const val BASE_URL = "http://192.168.1.100/belldial/"  // Change this
    // ...
}
```

**For different environments**:

- **Development**: `http://localhost/belldial/` (local testing)
- **Testing**: `http://test-server.com/belldial/`
- **Production**: `https://your-domain.com/belldial/`

### 2. Package Name Configuration

The app uses package name: `com.medicallab.reports`

To change it:
1. Right-click on package name in Android Studio
2. Refactor → Rename
3. Enter new package name
4. Update manifest accordingly

### 3. App Identity Configuration

**File**: `app/build.gradle`

```gradle
defaultConfig {
    applicationId "com.medicallab.reports"  // Change if needed
    versionCode 1
    versionName "1.0"
    minSdk 24
    targetSdk 34
}
```

### 4. Permissions Configuration

**File**: `app/AndroidManifest.xml`

Already configured with necessary permissions:
- `INTERNET` - Network communication
- `ACCESS_NETWORK_STATE` - Check connectivity
- `READ/WRITE_EXTERNAL_STORAGE` - File access
- `CAMERA` - Future feature support

### 5. ProGuard Configuration

**File**: `app/proguard-rules.pro`

Already configured for:
- Networking libraries (Retrofit, OkHttp)
- JSON parsing (Gson)
- Dependency injection (Hilt)
- Database (Room)

## Building the App

### Debug Build

1. Connect Android device or start emulator
2. Build → Build Bundle(s)/APK(s) → Build APK(s)
3. Wait for build to complete
4. APK location: `app/build/outputs/apk/debug/`

Run on device:
```bash
./gradlew installDebug
./gradlew runDebug
```

### Release Build

1. Generate signed APK:
   - Build → Generate Signed Bundle/APK
   - Select "APK"
   - Create new keystore or select existing:
     - Key store path: `/path/to/keystore.jks`
     - Password: (secure password)
     - Key alias: (unique name)
     - Key password: (same as above)
   - Build type: Release
   - Finish

2. Locate APK:
   - File location: `app/release/` or `app/build/outputs/apk/release/`

### Command Line Build

```bash
# Debug APK
./gradlew assembleDebug

# Release APK
./gradlew assembleRelease

# Build and install on connected device
./gradlew installDebug

# Run tests
./gradlew test
```

## Testing

### Unit Tests

Create tests in `src/test/java/`:

```kotlin
class LoginViewModelTest {
    private lateinit var viewModel: AuthViewModel
    
    @Before
    fun setup() {
        // Setup test fixtures
    }
    
    @Test
    fun testLoginSuccess() {
        // Test login success scenario
    }
}
```

Run unit tests:
```bash
./gradlew test
```

### UI Tests

Create tests in `src/androidTest/java/`:

```kotlin
@RunWith(AndroidJUnit4::class)
class LoginActivityTest {
    @get:Rule
    val activityRule = ActivityScenarioRule(LoginActivity::class.java)
    
    @Test
    fun testLoginButtonClick() {
        // Test UI interactions
    }
}
```

Run UI tests:
```bash
./gradlew connectedAndroidTest
```

### Manual Testing

1. **Login Test**:
   - App launches → Splash → Login screen
   - Enter valid credentials
   - Verify redirect to Dashboard

2. **Patient Search Test**:
   - From Dashboard → Search Patient
   - Search by MRN or name
   - Verify results display

3. **View Reports Test**:
   - From Dashboard → View Reports
   - Select patient/report
   - Verify details load correctly

## Deployment

### Google Play Store Deployment

1. **Sign up for Developer Account**:
   - Visit https://play.google.com/console
   - Create developer account ($25 one-time fee)
   - Set up merchant account

2. **Create Application**:
   - Create new app listing
   - Fill in app name and details

3. **Prepare App for Release**:
   - Ensure version code > 1
   - Build signed APK or App Bundle
   - Test on real device

4. **Upload Release**:
   - Create new release (production/beta/alpha)
   - Upload APK/App Bundle
   - Add release notes
   - Review content rating

5. **Configure Store Listing**:
   - Add screenshots (minimum 2, max 8)
   - Write app description (4000 chars max)
   - Add short description (80 chars max)
   - Set category and content rating
   - Add privacy policy URL

6. **Submit for Review**:
   - Review checklist
   - Submit app
   - Wait for review (typically 24-48 hours)

### Internal Testing Deployment

1. Create Alpha/Beta tracks in Google Play Console
2. Upload APK
3. Add test accounts
4. Share test link with testers
5. Monitor feedback and crash reports

### Firebase Integration (Optional)

1. Create Firebase project:
   ```
   https://console.firebase.google.com
   ```

2. Add app to Firebase:
   - Register app (package: `com.medicallab.reports`)
   - Download `google-services.json`
   - Place in `app/` directory

3. Add dependencies in `build.gradle`:
   ```gradle
   implementation platform('com.google.firebase:firebase-bom:32.0.0')
   implementation 'com.google.firebase:firebase-analytics-ktx'
   implementation 'com.google.firebase:firebase-crashlytics-ktx'
   ```

4. Monitor crashes:
   - Firebase Console → Crashlytics
   - View crash reports and stack traces

## Troubleshooting

### Build Issues

**Error: "SDK location not found"**
- Create `local.properties` file in project root
- Add: `sdk.dir=/path/to/android/sdk`

**Error: "Gradle sync failed"**
- File → Sync Now
- Check Java version (must be 11+)
- Delete `.gradle` folder and retry

### Runtime Issues

**App crashes on login**
- Check server URL is correct
- Verify backend is running
- Check network connectivity
- Review logcat for stack traces

**Network timeout**
- Increase timeout in `NetworkClient.kt`
- Check server response time
- Verify firewall settings

### Testing Issues

**Tests fail to run**
- Ensure emulator or device is connected
- Check USB debugging is enabled
- Update Android SDK Platform-Tools

## Security Checklist

- [ ] Server URL uses HTTPS in production
- [ ] Sensitive data is encrypted
- [ ] API keys are not hardcoded
- [ ] ProGuard/R8 minification enabled
- [ ] Permissions are minimal necessary
- [ ] No debug logs in production
- [ ] Keystore is securely stored
- [ ] Regular security updates applied

## Performance Tips

1. **Network**:
   - Implement request caching
   - Compress API responses
   - Use pagination for lists

2. **Memory**:
   - Use weak references for listeners
   - Avoid memory leaks in coroutines
   - Load images efficiently with Glide

3. **Storage**:
   - Clean old cache files
   - Compress downloaded PDFs
   - Limit local database size

4. **UI**:
   - Optimize layout hierarchy
   - Use ViewHolder pattern
   - Enable battery saver mode support

## Next Steps

1. Configure server URL for your environment
2. Test app thoroughly on real device
3. Request beta testers
4. Prepare Google Play Store listing
5. Submit for review
6. Monitor user feedback and crashes
7. Plan future updates and features

## Support

For issues or questions:
- Check logcat for error messages
- Review API response codes
- Test on multiple devices/API levels
- Consult Android documentation
