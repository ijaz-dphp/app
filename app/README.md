# Medical Lab Reports Android App

A comprehensive Android application for the Medical Laboratory Reports Management System, built with Kotlin and modern Android architecture.

## Features

### User Features
- **User Authentication**: Secure login with username/password
- **Patient Search**: Search patients by MRN or name
- **View Reports**: Browse medical reports with detailed results
- **Download Reports**: Export reports as PDF
- **Change Password**: Secure password management

### Staff Features
- All patient features
- Patient search and management
- Report viewing and management

### Admin Features
- All staff features
- User management (create, edit, delete users)
- Test management (manage test categories and parameters)
- System administration

## Project Structure

```
app/
├── src/main/
│   ├── java/com/medicallab/reports/
│   │   ├── MedicalLabApplication.kt
│   │   ├── data/
│   │   │   ├── api/
│   │   │   │   ├── MedicalLabApi.kt        (API endpoints)
│   │   │   │   ├── NetworkClient.kt         (Retrofit setup)
│   │   │   │   └── Models.kt               (API request/response models)
│   │   │   ├── models/
│   │   │   │   └── Models.kt               (Domain models)
│   │   │   ├── preferences/
│   │   │   │   └── PreferencesManager.kt   (Local data storage)
│   │   │   └── repository/
│   │   │       └── Repositories.kt         (Data layer)
│   │   └── presentation/
│   │       ├── ui/
│   │       │   ├── splash/
│   │       │   │   └── SplashActivity.kt
│   │       │   ├── auth/
│   │       │   │   └── LoginActivity.kt
│   │       │   ├── dashboard/
│   │       │   │   └── DashboardActivity.kt
│   │       │   ├── patients/
│   │       │   │   └── PatientSearchActivity.kt
│   │       │   ├── reports/
│   │       │   │   ├── ReportsListActivity.kt
│   │       │   │   └── ReportDetailActivity.kt
│   │       │   └── admin/
│   │       │       ├── AdminDashboardActivity.kt
│   │       │       ├── ManageUsersActivity.kt
│   │       │       └── ManageTestsActivity.kt
│   │       └── viewmodel/
│   │           ├── AuthViewModel.kt
│   │           └── ReportViewModel.kt
│   ├── res/
│   │   ├── layout/
│   │   │   ├── activity_splash.xml
│   │   │   ├── activity_login.xml
│   │   │   ├── activity_dashboard.xml
│   │   │   ├── activity_patient_search.xml
│   │   │   ├── activity_reports_list.xml
│   │   │   ├── activity_report_detail.xml
│   │   │   ├── activity_admin_dashboard.xml
│   │   │   ├── activity_manage_users.xml
│   │   │   └── activity_manage_tests.xml
│   │   ├── values/
│   │   │   ├── strings.xml
│   │   │   ├── colors.xml
│   │   │   └── themes.xml
│   │   └── drawable/
│   │       └── (app icons and drawables)
│   └── AndroidManifest.xml
└── build.gradle
```

## Dependencies

### Core Android Libraries
- **AndroidX**: Core libraries for modern Android development
- **Material Design**: Google's Material Design components
- **Lifecycle**: ViewModel, LiveData, and coroutines support
- **DataStore**: Type-safe data storage

### Networking
- **Retrofit 2**: Type-safe HTTP client
- **OkHttp**: HTTP client with interceptors
- **Gson**: JSON serialization/deserialization
- **Timber**: Logging library

### Architecture Components
- **MVVM Architecture**: Model-View-ViewModel pattern
- **Coroutines**: Asynchronous programming
- **Flow**: Reactive data streams
- **Hilt**: Dependency injection framework

### Additional Libraries
- **PDF Viewer**: Display PDF documents
- **Glide**: Image loading and caching
- **Room**: Local database
- **WorkManager**: Background tasks

## Getting Started

### Prerequisites
- Android Studio 4.1 or higher
- Android SDK 24 (API level 24) or higher
- Java 11 or higher

### Installation Steps

1. **Clone the repository**
   ```bash
   cd /path/to/app
   ```

2. **Configure Server URL**
   - Edit `NetworkClient.kt`
   - Replace `BASE_URL` with your server IP/domain
   ```kotlin
   private const val BASE_URL = "http://your-server-ip/belldial/"
   ```

3. **Build and Run**
   - Open Android Studio
   - File → Open → Select the app folder
   - Build → Make Project
   - Run → Run 'app'

## API Integration

The app connects to your PHP backend using the following endpoints:

### Authentication
- `POST login_process.php` - User login
- `POST logout.php` - User logout
- `POST change_password_process.php` - Change password

### Patients
- `GET search_patient.php` - Search patients
- `GET get_patient_by_mrn.php` - Get patient by MRN

### Reports
- `GET reports_list.php` - Get list of reports
- `GET view_report.php` - Get report details
- `GET get_test_parameters.php` - Get test results
- `GET generate_pdf.php` - Download PDF

### Admin
- `GET manage_users.php` - List users
- `POST save_user.php` - Create/update user
- `POST update_test.php` - Update test

## Architecture Highlights

### MVVM Pattern
- **Model**: Data models and repositories
- **View**: Activities and UI components
- **ViewModel**: Business logic and UI state management

### Repository Pattern
- Single source of truth for data
- Separates data layer from UI
- Facilitates testing

### Coroutines & Flow
- Non-blocking network calls
- Reactive data streams
- Proper lifecycle awareness

## Building for Production

1. **Configure Release Build**
   ```gradle
   buildTypes {
       release {
           minifyEnabled true
           proguardFiles getDefaultProguardFile('proguard-android-optimize.txt'), 'proguard-rules.pro'
       }
   }
   ```

2. **Generate Signed APK**
   - Build → Generate Signed Bundle/APK
   - Select APK format
   - Create or select keystore
   - Complete the signing wizard

3. **Upload to Google Play Store**
   - Sign up for Google Play Developer account
   - Create new app
   - Upload APK
   - Add app details, screenshots, etc.
   - Submit for review

## Testing

### Unit Tests
Create test files in `src/test/java/` directory

### UI/Integration Tests
Create test files in `src/androidTest/java/` directory

Run tests:
```bash
./gradlew test                    # Unit tests
./gradlew connectedAndroidTest    # UI tests
```

## Troubleshooting

### Network Connection Issues
- Verify server IP in `NetworkClient.kt`
- Check firewall settings
- Ensure cleartext traffic is allowed for development

### Login Fails
- Verify username/password
- Check server is running
- Review logs in Logcat

### PDF Download Issues
- Verify write permissions
- Check available storage
- Review network connectivity

## Future Enhancements

- [ ] Biometric authentication
- [ ] Offline report viewing
- [ ] Report sharing via email/messaging
- [ ] Notifications for new reports
- [ ] Dark mode support
- [ ] Multi-language support
- [ ] Advanced filtering and sorting
- [ ] Report annotations
- [ ] Doctor recommendations

## License

This project is licensed under the MIT License - see LICENSE file for details.

## Support

For issues, bug reports, or feature requests, please contact the development team.

## Version History

### Version 1.0 (Current)
- Initial release
- Basic CRUD operations
- User authentication
- Report viewing and download
- Admin management features
