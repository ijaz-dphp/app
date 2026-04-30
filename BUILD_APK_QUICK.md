# 🚀 Quick Start: Build APK in 5 Minutes

**TL;DR** - Use GitHub to build since local Gradle can't connect to servers.

## The 4 Commands You Need

### 1️⃣ Go to your project
```bash
cd /home/nginx/domains/belldial.softarts.co/public
```

### 2️⃣ Set up git (one time only)
```bash
git init
git add .
git commit -m "Medical lab app"
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git branch -M main
git push -u origin main
```

### 3️⃣ Watch GitHub build it
- Open: https://github.com/YOUR_USERNAME/YOUR_REPO/actions
- Wait for **green checkmark** (3-5 minutes)

### 4️⃣ Download your APK
- Click the build
- Scroll to **Artifacts**
- Click **app-debug.apk** ✅

## What to Replace

- `YOUR_USERNAME` = Your GitHub username
- `YOUR_REPO` = Your repository name (e.g., `medical-lab-app`)

## Example

```bash
cd /home/nginx/domains/belldial.softarts.co/public
git init
git add .
git commit -m "Medical lab app"
git remote add origin https://github.com/john123/medical-lab-app.git
git branch -M main
git push -u origin main
```

Then visit: `https://github.com/john123/medical-lab-app/actions`

## Done!

Your APK is ready. Install it on Android phone with:
```bash
adb install ~/Downloads/app-debug.apk
```

---

**For detailed instructions:** See `GITHUB_BUILD_GUIDE.md`
