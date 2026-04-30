# Build APK Using GitHub Actions (No Local Gradle Needed)

Since you can't connect to Gradle servers locally, this guide uses **GitHub Actions** to build your APK in the cloud.

## Step 1: Create a GitHub Repository

1. Go to [github.com/new](https://github.com/new)
2. Create a new **public** or **private** repository
3. Name it something like `medical-lab-app` or `belldial-android-app`
4. Do NOT initialize with README (we already have files)

## Step 2: Push Your Code to GitHub

Navigate to your project and run these commands (replace `YOUR_USERNAME` and `YOUR_REPO`):

```bash
cd /home/nginx/domains/belldial.softarts.co/public

# Initialize git repo (if not already done)
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial Android app configuration"

# Add remote repository
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git

# Push to GitHub (use main or master based on your repo)
git branch -M main
git push -u origin main
```

**OR** if you already have git configured:

```bash
cd /home/nginx/domains/belldial.softarts.co/public
git add .
git commit -m "Android app with GitHub Actions build"
git push
```

## Step 3: GitHub Actions Builds Your APK Automatically

Once pushed, GitHub will:

1. ✅ Trigger the build workflow automatically (watch in **Actions** tab)
2. ✅ Download Java JDK 17
3. ✅ Download Gradle and all dependencies from Maven Central
4. ✅ Compile your Kotlin code
5. ✅ Package into APK
6. ✅ Store APK as an artifact for 90 days

## Step 4: Download Your APK

### Option A: From Build Artifacts (Recommended)

1. Go to your GitHub repo
2. Click **Actions** tab at the top
3. Click the latest workflow run (green checkmark)
4. Scroll down to **Artifacts** section
5. Click **app-debug.apk** to download

**File location after download:** `~/Downloads/app-debug.apk`

### Option B: From Release (if you tag commits)

```bash
# Create a version tag (this triggers a release)
git tag v1.0.0
git push --tags

# Then go to Releases tab to download
```

## Step 5: Install APK on Your Phone

### Via Android Studio
1. Connect phone with USB debugging enabled
2. Open Android Studio
3. Build → Generate Signed Bundle/APK
4. Install on device

### Via ADB (Command Line)
```bash
adb install ~/Downloads/app-debug.apk
```

### Via Email/Cloud
1. Email yourself the APK file
2. Download on phone
3. Open file manager → Tap APK → Install
4. Allow "Unknown Sources" in Security settings if needed

### Via QR Code (Easy for users)
Use any QR code generator to create a link to your APK hosted on your server.

## Build Status Checking

**Check build progress anytime:**
- GitHub Repo → Actions tab → View logs in real-time
- Each build takes 3-5 minutes
- Full Android build system runs in cloud

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "Workflow failed" | Check build logs in Actions tab for error messages |
| APK not created | Ensure `app/` directory path is correct in gradle build |
| Can't access GitHub | Ask your IT team about firewall rules for github.com |
| Large file size | Debug APK is ~60MB; Release build is smaller (~45MB) |

## For Production Release

When ready to release, switch from Debug to Release builds:

1. Generate signing key
2. Update workflow to `assembleRelease` 
3. Sign APK with your key
4. Distribute through Play Store or direct download

## Workflow Details

The `.github/workflows/android-build.yml` file contains:

```yaml
- Java JDK 17 installation
- Gradle wrapper execution
- APK compilation & packaging
- Artifact storage
- Build notifications
```

**No configuration needed** - it just works once you push to GitHub!

---

## Quick Command Summary

```bash
# One-time setup
cd /home/nginx/domains/belldial.softarts.co/public
git init
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git

# Every time you make changes
git add .
git commit -m "Your message"
git push

# Watch build
open https://github.com/YOUR_USERNAME/YOUR_REPO/actions
```

✅ **Your APK will be ready in 3-5 minutes after each push!**

---

## Still Stuck?

If you need help:
1. Share your GitHub repository URL
2. Show the error from the Actions tab logs
3. We can adjust the workflow for your specific setup
