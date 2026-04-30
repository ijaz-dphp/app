# Custom Post Type - Jobs Documentation

## 🎯 Overview

Theme mein ab Jobs ko **Custom Post Type** bana diya gaya hai with professional meta boxes. Yeh standard WordPress posts se alag hai aur sirf jobs ke liye dedicated hai.

## ✨ Features Added

### 1. Custom Post Type: "Jobs"
- Dedicated Jobs section in WordPress admin
- Custom icon (businessman icon)
- Archive page support
- SEO-friendly URLs (yoursite.com/jobs/job-title)

### 2. Custom Taxonomies

**Job Categories** (Hierarchical - like Categories)
- Mechanical Jobs
- Electrical Jobs
- M&E Management
- Supervisors
- etc.

**Job Types** (Non-hierarchical - like Tags)
- Contract
- Permanent
- Part Time
- Temporary
- Hybrid

### 3. Custom Meta Boxes

#### Job Details Meta Box
Main job information fields:
- **Job Location** - City/Area (e.g., Dartford, London)
- **Salary Range** - Salary details (e.g., £26,000 – £32,000)
- **Contract Type** - Dropdown menu:
  - Permanent
  - Contract
  - Part Time
  - Temporary
  - Permanent Hybrid Working
- **Required Experience** - Years of experience (e.g., 2-5 years)
- **Qualifications Required** - Textarea (one per line)
- **Key Responsibilities** - Textarea (one per line)
- **Job Requirements** - Textarea (one per line)

#### Application Information Meta Box (Sidebar)
Application related fields:
- **Application Deadline** - Date picker
- **Contact Email** - Email for applications
- **Contact Phone** - Phone number
- **Featured Job** - Checkbox to feature the job

### 4. Custom Admin Columns
Jobs listing page mein yeh columns show hote hain:
- Title
- Location
- Salary
- Contract Type
- Featured (⭐)
- Job Category
- Date

All columns are sortable!

## 📝 How to Add a New Job

### Step 1: Go to Jobs Section
WordPress admin mein **Jobs > Add New** par jao

### Step 2: Fill Basic Information
- **Title**: Job title (e.g., "Mechanical Supervisor")
- **Description**: Main job description (use the editor)
- **Featured Image**: Upload a relevant image (optional)

### Step 3: Fill Job Details Meta Box
Scroll down to "Job Details" meta box:
1. Enter **Location** (e.g., "Dartford")
2. Enter **Salary** (e.g., "£35,000 - £45,000")
3. Select **Contract Type** from dropdown
4. Enter **Experience** required (e.g., "3-5 years")
5. Add **Qualifications** (one per line):
   ```
   City & Guilds qualified
   18th Edition certified
   Valid CSCS card
   ```
6. Add **Responsibilities** (one per line):
   ```
   Supervise M&E installation teams
   Liaise with project managers
   Ensure health and safety compliance
   ```
7. Add **Requirements** (one per line):
   ```
   Minimum 3 years M&E experience
   Strong leadership skills
   Excellent communication
   ```

### Step 4: Fill Application Information
Right sidebar mein "Application Information":
1. Set **Application Deadline** (optional)
2. Enter **Contact Email** for applications
3. Enter **Contact Phone** (optional)
4. Check **Featured** if you want to highlight this job

### Step 5: Set Categories and Types
Right sidebar mein:
1. Select **Job Categories** (e.g., Mechanical, Supervisors)
2. Add **Job Types** tags (e.g., Permanent, Contract)

### Step 6: Publish
Click **Publish** button!

## 🎨 Template Files

### Front-end Display Templates:

1. **page-jobs.php** - Jobs listing page
   - Shows all jobs
   - Uses custom post type 'job'
   - Displays meta fields

2. **single-job.php** - Individual job detail page
   - Full job description
   - All meta fields displayed
   - Related jobs sidebar
   - Apply section
   - Professional layout

3. **front-page.php** - Homepage
   - Shows latest 6 jobs
   - Updated to use custom post type

## 🔧 Meta Fields (for developers)

All meta fields are stored with underscore prefix (private):

```php
_job_location          // Text
_job_salary            // Text
_job_contract_type     // Text (from dropdown)
_job_experience        // Text
_job_qualifications    // Textarea
_job_responsibilities  // Textarea
_job_requirements      // Textarea
_job_deadline          // Date (YYYY-MM-DD)
_job_contact_email     // Email
_job_contact_phone     // Text
_job_featured          // 1 or 0
```

### How to Get Meta Fields in Templates:

```php
$location = get_post_meta(get_the_ID(), '_job_location', true);
$salary = get_post_meta(get_the_ID(), '_job_salary', true);
$contract_type = get_post_meta(get_the_ID(), '_job_contract_type', true);
```

## 📊 Custom Queries

### Get All Jobs:
```php
$args = array(
    'post_type' => 'job',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);
$jobs = new WP_Query($args);
```

### Get Featured Jobs:
```php
$args = array(
    'post_type' => 'job',
    'meta_key' => '_job_featured',
    'meta_value' => '1',
    'posts_per_page' => 5
);
$featured_jobs = new WP_Query($args);
```

### Get Jobs by Category:
```php
$args = array(
    'post_type' => 'job',
    'tax_query' => array(
        array(
            'taxonomy' => 'job_category',
            'field' => 'slug',
            'terms' => 'mechanical'
        )
    )
);
$mechanical_jobs = new WP_Query($args);
```

## 🌐 URLs Structure

- All Jobs: `yoursite.com/jobs/`
- Single Job: `yoursite.com/jobs/job-title/`
- Job Category: `yoursite.com/job-category/mechanical/`
- Job Type: `yoursite.com/job-type/permanent/`

## ⚙️ Admin Features

1. **Bulk Edit** - Edit multiple jobs at once
2. **Quick Edit** - Quick inline editing
3. **Custom Columns** - See important info at a glance
4. **Sorting** - Click column headers to sort
5. **Filtering** - Filter by categories and types

## 🎯 Benefits of Custom Post Type

✅ **Better Organization** - Jobs separate from blog posts
✅ **Custom Fields** - All job-specific information in one place
✅ **Professional URLs** - SEO-friendly job URLs
✅ **Better Management** - Easy to manage and filter jobs
✅ **Scalability** - Can handle thousands of jobs easily
✅ **Categories & Tags** - Better job classification
✅ **Custom Templates** - Dedicated templates for jobs
✅ **Admin Columns** - Quick overview in admin panel

## 🚀 Next Steps After Installation

1. Install theme in WordPress
2. Go to **Jobs > Add New**
3. Create job categories (Jobs > Job Categories)
4. Add some job types (Jobs > Job Types)
5. Create your first job post
6. View it on the Jobs page

## 📱 Responsive Design

All job templates are fully responsive:
- Mobile-friendly cards
- Touch-friendly buttons
- Responsive meta information
- Optimized for all devices

## 🔍 SEO Features

- Custom meta descriptions support
- Clean URL structure
- Schema markup ready
- Breadcrumbs included
- Archive pages optimized

---

**Note**: Yeh sab features WordPress environment mein hi properly kaam karengi. Local development mein PHP errors normal hain.

Happy Job Posting! 🎉
