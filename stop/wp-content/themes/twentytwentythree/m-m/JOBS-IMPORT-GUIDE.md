# 📥 Jobs Import Utility - Complete Guide

## 🎯 Purpose

The `import-sample-jobs.php` file is a one-time utility to **import 10 professional M&E jobs** into your WordPress database.

---

## ✨ What Gets Imported

### 10 Sample Jobs:

1. **Senior Electrical Engineer** (Permanent, Featured) 
   - London, £55K-£65K

2. **Mechanical Maintenance Engineer** (Permanent)
   - Central London, £40K-£48K

3. **Electrical Project Manager** (Permanent, Featured)
   - South East England, £65K-£75K + Car

4. **HVAC Technician** (Contract)
   - London, £22-£26/hour

5. **Electrical Installations Supervisor** (Permanent)
   - West London, £45K-£52K

6. **BMS Controls Engineer** (Permanent)
   - London & Home Counties, £38K-£45K + Van

7. **Plumbing Engineer - Data Centre** (Permanent, Featured)
   - Greater London, £48K-£55K

8. **Electrical Testing & Inspection Engineer** (Permanent)
   - London & South East, £38K-£44K + Van

9. **Junior Mechanical Fitter** (Permanent)
   - East London, £28K-£32K

10. **Fire Alarm Engineer** (Permanent)
    - London & M25, £36K-£42K + Van

---

## 📋 Each Job Includes:

### Complete Data:
- ✅ **Title** - Job position name
- ✅ **Description** - Full job overview
- ✅ **Location** - City/area
- ✅ **Salary** - Pay range
- ✅ **Contract Type** - Permanent/Contract
- ✅ **Experience** - Years required
- ✅ **Application Deadline** - Auto-set (15-30 days from import)
- ✅ **Responsibilities** - Line-by-line list
- ✅ **Requirements** - Line-by-line list
- ✅ **Qualifications** - Line-by-line list
- ✅ **Contact Email** - recruitment@mesite.com
- ✅ **Contact Phone** - 020 8298 9977
- ✅ **Category** - Electrical or Mechanical
- ✅ **Type** - Permanent or Contract tag
- ✅ **Featured Status** - 3 jobs marked as featured

---

## 🚀 How to Use

### Step 1: Upload File
```bash
# Upload import-sample-jobs.php to theme folder:
/wp-content/themes/m-m/import-sample-jobs.php
```

### Step 2: Visit Import Page
Open browser and go to:
```
https://yoursite.com/wp-content/themes/m-m/import-sample-jobs.php
```

### Step 3: Preview Jobs
You'll see:
- List of 10 jobs to be imported
- Job details (title, location, salary, type)
- Featured badges
- Warning messages

### Step 4: Click "Start Import Now"
- Button will ask for confirmation
- Click OK to proceed
- Import process starts

### Step 5: Watch Progress
You'll see:
- ✅ Green success messages for each imported job
- Job ID assigned
- Total count: 10/10
- Summary at the end

### Step 6: View Jobs
Click one of these buttons:
- **"View Jobs in Admin"** - WordPress admin panel
- **"View Jobs on Site"** - Frontend jobs page

### Step 7: DELETE THE FILE ⚠️
**IMPORTANT:** For security, delete `import-sample-jobs.php` after import!

```bash
# Delete the file from server
rm /wp-content/themes/m-m/import-sample-jobs.php
```

---

## 🔒 Security Features

### Admin-Only Access:
```php
if (!current_user_can('manage_options')) {
    die('Access denied. Admin only.');
}
```

Only WordPress administrators can run this import.

### WordPress Loading:
```php
require_once('../../../wp-load.php');
```

Loads full WordPress environment for security and functions.

### Must Delete After Use:
File shows warning to delete itself after import for security.

---

## 📊 Import Process

### What Happens:

1. **Loops through 10 jobs**
2. **Creates job post** with `wp_insert_post()`
3. **Adds meta fields** (11 custom fields per job)
4. **Assigns categories** (Electrical/Mechanical)
5. **Assigns types** (Permanent/Contract tags)
6. **Sets featured status** (3 jobs)
7. **Shows success/error** for each job
8. **Displays summary** at end

### Technical Flow:
```php
foreach ($sample_jobs as $job_data) {
    // 1. Create post
    $post_id = wp_insert_post($post_data);
    
    // 2. Add meta fields
    update_post_meta($post_id, '_job_location', $location);
    update_post_meta($post_id, '_job_salary', $salary);
    // ... 11 total meta fields
    
    // 3. Assign taxonomy
    wp_set_object_terms($post_id, $category, 'job_category');
    wp_set_object_terms($post_id, $type, 'job_type');
    
    // 4. Set featured
    if (featured) {
        update_post_meta($post_id, '_job_featured', 'yes');
    }
}
```

---

## 🎨 Visual Interface

### Before Import:
```
┌────────────────────────────────────┐
│  Import Sample M&E Jobs            │
├────────────────────────────────────┤
│  📋 Ready to Import:               │
│  This will import 10 sample jobs   │
│                                    │
│  Jobs to be Imported:              │
│  ┌──────────────────────────────┐ │
│  │ 1. Senior Electrical Engineer│ │
│  │    📍 London | 💰 £55K-£65K  │ │
│  ├──────────────────────────────┤ │
│  │ 2. Mechanical Maintenance... │ │
│  │    📍 Central London         │ │
│  └──────────────────────────────┘ │
│                                    │
│  ⚠️ Before You Import:            │
│  - Theme activated                 │
│  - Logged in as admin              │
│  - Database backup recommended     │
│                                    │
│  [🚀 Start Import Now]            │
└────────────────────────────────────┘
```

### During Import:
```
┌────────────────────────────────────┐
│  🔄 Starting Import Process...     │
│                                    │
│  ✅ Imported: Senior Electrical... │
│  ✅ Imported: Mechanical Maint...  │
│  ✅ Imported: Electrical Project...│
│  ✅ Imported: HVAC Technician...   │
│  ...                               │
└────────────────────────────────────┘
```

### After Import:
```
┌────────────────────────────────────┐
│  📊 Import Summary:                │
│  ✅ Successfully imported: 10 jobs │
│  ❌ Failed: 0 jobs                 │
│  📝 Total processed: 10 jobs       │
│                                    │
│  🎉 Import Complete!               │
│  You can now view your jobs...     │
│                                    │
│  [View Jobs in Admin]              │
│  [View Jobs on Site]               │
│                                    │
│  ⚠️ IMPORTANT:                     │
│  Please DELETE this file after...  │
└────────────────────────────────────┘
```

---

## 📁 File Details

### File Name:
```
import-sample-jobs.php
```

### Location:
```
/wp-content/themes/m-m/import-sample-jobs.php
```

### File Size:
~25 KB (with all job data)

### Lines of Code:
~400 lines (PHP + HTML)

---

## 🗂️ Jobs Data Structure

### Sample Job Array:
```php
array(
    'title' => 'Senior Electrical Engineer',
    'content' => 'Full job description...',
    'location' => 'London',
    'salary' => '£55,000 - £65,000 per annum',
    'contract_type' => 'Permanent',
    'experience' => '5+ years',
    'deadline' => '2026-02-12', // Auto-calculated
    'responsibilities' => "Line 1\nLine 2\nLine 3",
    'requirements' => "Line 1\nLine 2\nLine 3",
    'qualifications' => "Line 1\nLine 2\nLine 3",
    'contact_email' => 'recruitment@mesite.com',
    'contact_phone' => '020 8298 9977',
    'category' => 'Electrical',
    'type' => 'Permanent',
    'featured' => true
)
```

---

## 📊 Categories & Types Created

### Categories (Taxonomies):
- **Electrical** (6 jobs)
- **Mechanical** (4 jobs)

### Types (Tags):
- **Permanent** (9 jobs)
- **Contract** (1 job)

### Featured Jobs:
- Senior Electrical Engineer
- Electrical Project Manager
- Plumbing Engineer - Data Centre

---

## ⚠️ Before Import Checklist

### Prerequisites:
- [ ] WordPress installed and running
- [ ] M&E Recruitment theme activated
- [ ] "Jobs" custom post type registered
- [ ] Logged in as administrator
- [ ] Database backup taken (recommended)

### File Upload:
- [ ] File uploaded to theme directory
- [ ] Correct path: `/wp-content/themes/m-m/`
- [ ] File permissions: 644 or 755

---

## ✅ After Import Checklist

### Verify:
- [ ] All 10 jobs imported successfully
- [ ] Jobs visible in admin (Jobs menu)
- [ ] Jobs visible on frontend (/jobs/)
- [ ] Meta fields populated correctly
- [ ] Categories assigned
- [ ] Types (tags) assigned
- [ ] 3 jobs marked as featured
- [ ] Deadlines set correctly

### Cleanup:
- [ ] **DELETE import-sample-jobs.php file**
- [ ] Test job detail pages
- [ ] Test application forms
- [ ] Review and edit job content as needed

---

## 🔧 Troubleshooting

### Issue: "Access denied. Admin only."
**Solution:** Log in as WordPress administrator.

### Issue: Jobs not importing
**Solution:** 
1. Check theme is activated
2. Verify custom post type registered
3. Check PHP error logs

### Issue: Categories not created
**Solution:**
Taxonomies create automatically. If issue persists:
```php
// Manually create in WordPress admin
// Jobs > Categories
```

### Issue: Featured status not working
**Solution:**
Check if meta box saves featured field. Test by:
```php
// Edit a job in admin
// Check "Featured" checkbox
// Update job
```

### Issue: Page loads but nothing happens
**Solution:**
Click "Start Import Now" button. Don't just load the page.

---

## 📧 Contact Information in Jobs

All imported jobs have:
- **Email:** recruitment@mesite.com
- **Phone:** 020 8298 9977

**Note:** Update these in individual jobs after import if needed.

---

## 🎯 Use Cases

### When to Use:
1. **Fresh WordPress install** - Need sample jobs quickly
2. **Theme demo** - Show clients how it works
3. **Testing** - Test job functionality
4. **Development** - Develop with real-looking data

### When NOT to Use:
1. **Live site with existing jobs** - May create duplicates
2. **Production without backup** - Always backup first
3. **Multiple times** - Only run once (creates duplicates)

---

## 🔄 Re-importing

### If you need to re-import:

1. **Delete existing sample jobs** first:
   ```
   WordPress Admin > Jobs > Select All > Move to Trash
   ```

2. **Empty Trash:**
   ```
   Jobs > Trash > Empty Trash
   ```

3. **Run import again:**
   ```
   Visit import-sample-jobs.php URL
   Click "Start Import Now"
   ```

---

## 📈 Expected Results

### After successful import:

**WordPress Admin:**
```
Jobs (10)
├── Senior Electrical Engineer ⭐
├── Mechanical Maintenance Engineer
├── Electrical Project Manager ⭐
├── HVAC Technician - Contract
├── Electrical Installations Supervisor
├── BMS Controls Engineer
├── Plumbing Engineer - Data Centre ⭐
├── Electrical Testing & Inspection Engineer
├── Junior Mechanical Fitter
└── Fire Alarm Engineer
```

**Frontend (/jobs/):**
- 10 jobs displayed in grid
- Featured jobs highlighted
- Filter by category works
- Pagination (if theme supports)

**Single Job Pages:**
- All details display correctly
- Meta fields show properly
- Application form works
- Related jobs sidebar

---

## 🎨 Customization

### To modify jobs before import:

Edit the `$sample_jobs` array in `import-sample-jobs.php`:

```php
array(
    'title' => 'Your Custom Job Title',
    'content' => 'Your job description...',
    'location' => 'Your Location',
    'salary' => 'Your Salary Range',
    // ... etc
)
```

### To add more jobs:

Add another array to `$sample_jobs`:

```php
$sample_jobs = array(
    // ... existing 10 jobs
    array(
        'title' => 'New Job 11',
        // ... all fields
    ),
    array(
        'title' => 'New Job 12',
        // ... all fields
    )
);
```

---

## 🔒 Security Best Practices

### 1. Admin-Only Access
File checks for admin privileges.

### 2. WordPress Environment
Loads full WordPress (not standalone PHP).

### 3. Delete After Use
**CRITICAL:** Remove file after import!

### 4. One-Time Use
Designed to run once, then delete.

### 5. No User Input
No forms that could be exploited.

---

## 📝 Summary

### What This File Does:
✅ Imports 10 professional M&E jobs
✅ Creates categories and types
✅ Sets featured jobs
✅ Populates all meta fields
✅ Shows visual progress
✅ Provides admin/frontend links

### What You Need To Do:
1. Upload file to theme folder
2. Visit the URL in browser
3. Click "Start Import Now"
4. Wait for completion
5. **DELETE the file**
6. Edit jobs as needed

### Time Required:
- Upload: 30 seconds
- Import: 10-20 seconds
- Review: 2-3 minutes
- **Total: ~3 minutes**

---

## 🎉 Result

**After running this import, you'll have:**

- 🎯 10 professional M&E jobs
- 📁 2 categories (Electrical, Mechanical)
- 🏷️ 2 types (Permanent, Contract)
- ⭐ 3 featured jobs
- 📝 Complete job details for each
- 📧 Contact information
- 📅 Application deadlines
- 🎨 Ready-to-use job board!

**Your theme will look professional and complete!** 🚀

---

**Ready to import? Upload the file and visit the URL!** 🎯
