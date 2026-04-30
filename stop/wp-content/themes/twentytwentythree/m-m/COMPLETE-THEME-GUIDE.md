# 🚀 M&E Recruitment WordPress Theme - Complete Guide

## 📋 Table of Contents
1. [Overview](#overview)
2. [Installation](#installation)
3. [Features](#features)
4. [Page Templates](#page-templates)
5. [Jobs System](#jobs-system)
6. [Candidate System](#candidate-system)
7. [Customization](#customization)
8. [Troubleshooting](#troubleshooting)

---

## 📖 Overview

**M&E Recruitment Theme** is a professional WordPress theme designed for recruitment agencies and job boards.

### Key Highlights:
- ✅ **Custom Post Type** for Jobs
- ✅ **Custom Database** for Candidate Submissions
- ✅ **4 Custom Page Templates**
- ✅ **Professional Admin Interface**
- ✅ **No Plugin Dependencies**
- ✅ **Bootstrap 5.3.2**
- ✅ **Font Awesome Icons**
- ✅ **Fully Responsive**

---

## 🔧 Installation

### Step 1: Upload Theme
1. ZIP the entire `m-m` folder
2. Go to WordPress admin
3. Navigate to **Appearance > Themes > Add New > Upload Theme**
4. Choose the ZIP file
5. Click **Install Now**
6. Click **Activate**

### Step 2: Theme Activation
Upon activation, the theme automatically:
- ✅ Creates custom database table `wp_candidate_submissions`
- ✅ Registers "Jobs" custom post type
- ✅ Adds 2 custom taxonomies
- ✅ Creates "Candidates" admin menu

### Step 3: Setup Pages
Create these pages and assign templates:

1. **Homepage** (Front Page)
   - Template: Automatically uses `front-page.php`
   - Go to **Settings > Reading**
   - Set "A static page" as homepage

2. **About Page**
   - Create page titled "About"
   - Template: `About Page Template`

3. **Jobs Page**
   - Create page titled "Jobs"
   - Template: `Jobs Page Template`

4. **Candidate Page**
   - Create page titled "Apply" or "Submit CV"
   - Template: `Candidate Page Template`

### Step 4: Setup Menu
1. Go to **Appearance > Menus**
2. Create a new menu
3. Add pages: Home, About, Jobs, Apply
4. Assign to "Primary Menu" location

---

## ✨ Features

### 1. Custom Post Type: Jobs

**Access:** `Posts > Jobs` in admin menu

**Features:**
- Add/Edit/Delete jobs
- 2 custom meta boxes with 11 fields
- Featured jobs support
- Custom taxonomies
- Archive page
- Single job detail page

**Custom Fields:**
- Location
- Salary
- Contract Type
- Experience Required
- Application Deadline
- Responsibilities
- Requirements
- Qualifications
- Contact Email
- Contact Phone
- Company Website

**Taxonomies:**
- **Job Categories** (hierarchical)
- **Job Types** (tags)

### 2. Candidate Submission System

**Access:** `Candidates` menu in admin

**Features:**
- Custom database table
- CV file uploads
- Status management (5 statuses)
- Search & filter
- CSV export
- Email notifications
- Job linking
- Internal notes

**Status Options:**
1. 🟡 **New** - Just submitted
2. 🔵 **Reviewing** - Under review
3. 🟢 **Shortlisted** - Selected for interview
4. ⚫ **Contacted** - Contacted candidate
5. 🔴 **Rejected** - Not suitable

### 3. Responsive Design
- Mobile-friendly
- Bootstrap 5.3.2
- Professional styling
- Modern UI/UX

### 4. SEO Ready
- Proper heading structure
- Meta descriptions support
- Schema markup ready
- Clean code

---

## 📄 Page Templates

### 1. Front Page (Homepage)
**File:** `front-page.php`

**Sections:**
- Hero banner
- Video introduction
- Features (benefits)
- Job categories
- How it works
- Professionals section
- Latest jobs (6 recent jobs)
- Call to action

**Customization:**
Edit content directly in the file or use page builder plugins.

### 2. About Page
**File:** `page-about.php`
**Template:** About Page Template

**Sections:**
- Page title
- Mission statement
- Team section
- Values section

**Customization:**
Edit content in the template file.

### 3. Jobs Page
**File:** `page-jobs.php`
**Template:** Jobs Page Template

**Features:**
- Lists all jobs from custom post type
- Grid layout
- Pagination
- Category filter links
- Job type badges

**Customization:**
Jobs are pulled from the Jobs custom post type. Add jobs via admin.

### 4. Candidate Page
**File:** `page-candidate.php`
**Template:** Candidate Page Template

**Features:**
- CV submission form
- File upload (PDF, DOC, DOCX)
- Form validation
- Success/error messages
- Auto-linking from job pages

**Form Fields:**
- Full Name (required)
- Job Title (required)
- Email (required)
- Phone (required)
- Message (optional)
- CV Upload (required, max 5MB)

---

## 💼 Jobs System

### Adding a New Job

1. **Go to:** `Jobs > Add New`

2. **Fill in:**
   - Job title
   - Job description (main content area)
   - Featured image (optional)
   - Excerpt (optional)

3. **Job Details Meta Box:**
   - Location
   - Salary
   - Contract Type (Full-time, Part-time, Contract)
   - Experience Required
   - Application Deadline

4. **Application Information Meta Box:**
   - Responsibilities (one per line)
   - Requirements (one per line)
   - Qualifications (one per line)
   - Contact Email
   - Contact Phone
   - Company Website

5. **Assign Taxonomies:**
   - Select job category
   - Add job type tags

6. **Publish**

### Job Display

**Archive Page:** `/jobs/`
- Lists all jobs
- Filter by category/type
- Pagination
- Featured job badge

**Single Job Page:** `/jobs/job-title/`
- Full job details
- All custom fields
- Categories & types
- "Submit Your CV" button (links to candidate page with job ID)
- Related jobs sidebar

### Featured Jobs

To feature a job:
1. Edit job
2. Check "Featured" in Job Details meta box
3. Update
4. Featured jobs show with special badge

---

## 👤 Candidate System

### How Candidates Apply

**Method 1: From Job Page**
1. User views job at `/jobs/job-title/`
2. Clicks "Submit Your CV" button
3. Redirected to candidate page with job ID
4. Fills form
5. Submits

**Method 2: Direct Application**
1. User goes to candidate page
2. Manually enters job title
3. Fills form
4. Submits

### Submission Process

1. **Form Validation:**
   - All required fields checked
   - Email format validated
   - File type validated (PDF, DOC, DOCX only)
   - File size checked (max 5MB)

2. **File Upload:**
   - CV stored in `/wp-content/uploads/candidate-cvs/`
   - Unique filename generated
   - Protected directory (.htaccess)

3. **Database Storage:**
   - All data saved to `wp_candidate_submissions` table
   - Timestamps recorded
   - IP address and user agent stored

4. **Email Notifications:**
   - Admin receives submission notification
   - Candidate receives confirmation email

5. **Success Message:**
   - Displayed on form page

### Managing Submissions

**Access:** `Candidates > All Submissions`

**Features:**

**1. List View:**
- All submissions in table
- Columns: ID, Name, Email, Phone, Job Title, Applied Job, CV, Status, Date, Actions
- Sortable by date
- Pagination (20 per page)

**2. Filters:**
- Status filter (dropdown)
- Search box (name, email, phone, job title)

**3. Quick Actions:**
- **Download CV** - Instant download
- **View** - See full details
- **Delete** - Remove submission
- **Status Change** - Update inline

**4. Detail View:**
- Full candidate information
- Link to applied job (if applicable)
- CV download button
- Status update
- Internal notes section
- Delete option

**5. Export:**
- "Export to CSV" button
- Downloads all submissions
- Opens in Excel

### Status Management

**Workflow:**
1. **New** - Submission received
2. **Reviewing** - Admin is reviewing
3. **Shortlisted** - Selected for interview
4. **Contacted** - Reached out to candidate
5. **Rejected** - Not suitable

**Changing Status:**
- From list view: Select from dropdown, auto-saves
- From detail view: Update status, click Update

### Internal Notes

**Purpose:** Private notes for admin team

**Usage:**
1. Open submission detail
2. Scroll to "Internal Notes"
3. Add notes
4. Click "Update Notes"
5. Notes saved (not visible to candidate)

---

## 🎨 Customization

### Changing Colors

**Primary Color (Blue):**
Edit in `css/theme-styles.css`:
```css
:root {
  --primary-color: #3498db; /* Change this */
}
```

### Changing Fonts

**Google Font:**
Edit in `functions.php`:
```php
// Line ~35
wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null);
```

Change `Inter` to your preferred font.

### Editing Homepage Content

Edit `front-page.php`:
- Hero section: Lines 10-55
- Video section: Lines 58-85
- Features: Lines 88-130
- Job categories: Lines 133-165
- Process steps: Lines 168-230
- Latest jobs: Lines 290-340

### Adding More Job Fields

**Step 1:** Edit `functions.php`
- Find `me_recruitment_job_details_callback()` (around line 363)
- Add new field HTML
- Save

**Step 2:** Update save function
- Find `me_recruitment_save_job_details()` (around line 493)
- Add `update_post_meta()` for new field
- Save

**Step 3:** Display on single job
- Edit `single-job.php`
- Retrieve field: `get_post_meta($post->ID, 'field_name', true)`
- Display where needed

### Changing Email Content

Edit `functions.php`, function `me_recruitment_process_candidate_submission()`:

**Admin Email:**
Around line 1120:
```php
$admin_message = "New candidate submission...";
```

**Candidate Email:**
Around line 1135:
```php
$candidate_message = "Dear $candidate_name...";
```

### Custom Logo

**Option 1: In Header**
Edit `header.php`, line ~15:
```php
<img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="Logo">
```

**Option 2: Customizer Support**
Add to `functions.php`:
```php
add_theme_support('custom-logo');
```

Then use **Appearance > Customize > Site Identity**.

---

## 🐛 Troubleshooting

### Issue: Jobs not showing

**Solution:**
1. Go to `Settings > Permalinks`
2. Click "Save Changes" (flushes rewrite rules)
3. Visit `/jobs/` page

### Issue: Database table not created

**Solution:**
1. Deactivate theme
2. Reactivate theme
3. Check database for `wp_candidate_submissions` table

**Manual Creation:**
Run this SQL in phpMyAdmin:
```sql
CREATE TABLE IF NOT EXISTS `wp_candidate_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candidate_name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `candidate_email` varchar(255) NOT NULL,
  `candidate_phone` varchar(50) DEFAULT NULL,
  `cv_file_path` varchar(500) DEFAULT NULL,
  `cv_file_name` varchar(255) DEFAULT NULL,
  `applied_job_id` int(11) DEFAULT NULL,
  `applied_job_title` varchar(255) DEFAULT NULL,
  `message` text,
  `status` varchar(50) DEFAULT 'new',
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text,
  `submission_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `email` (`candidate_email`),
  KEY `job_id` (`applied_job_id`),
  KEY `status` (`status`),
  KEY `date` (`submission_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Issue: CV upload not working

**Solution 1: Check permissions**
```bash
chmod 755 wp-content/uploads
chmod 755 wp-content/uploads/candidate-cvs
```

**Solution 2: Check PHP upload limit**
In `php.ini`:
```
upload_max_filesize = 10M
post_max_size = 10M
```

**Solution 3: Check .htaccess**
Ensure this file exists in `/wp-content/uploads/candidate-cvs/.htaccess`:
```
Order Deny,Allow
Deny from all
```

### Issue: Emails not sending

**Solution 1: Install SMTP plugin**
- WP Mail SMTP (recommended)
- Easy WP SMTP

**Solution 2: Check server mail**
Test with:
```php
wp_mail('your@email.com', 'Test', 'Test message');
```

**Solution 3: Check spam folder**
Emails might be in spam.

### Issue: Meta boxes not saving

**Solution:**
1. Check user permissions (admin role)
2. Verify nonce is being generated
3. Check for JavaScript errors in console
4. Ensure save function is hooked to `save_post_job`

### Issue: Styles not loading

**Solution:**
1. Clear browser cache
2. Hard refresh (Ctrl+Shift+R)
3. Check file paths in `functions.php`
4. Verify files exist in `/css/` folder

### Issue: 404 on job pages

**Solution:**
1. Go to `Settings > Permalinks`
2. Select "Post name"
3. Save changes
4. Try accessing job again

---

## 📊 Performance Tips

### 1. Caching
Use a caching plugin:
- WP Super Cache
- W3 Total Cache
- WP Rocket

### 2. Image Optimization
- Compress images before upload
- Use WebP format
- Install Smush or EWWW Image Optimizer

### 3. Database Optimization
- Regularly clean spam submissions
- Use WP-Optimize plugin
- Delete old revisions

### 4. CDN for Bootstrap & Font Awesome
Already using CDN links - no action needed.

---

## 🔐 Security Checklist

✅ **Nonce verification** - All forms protected
✅ **Data sanitization** - All inputs sanitized
✅ **SQL injection prevention** - Using $wpdb->prepare()
✅ **XSS protection** - Using esc_* functions
✅ **File upload validation** - Type and size checks
✅ **Capability checks** - Admin-only access
✅ **Protected uploads** - .htaccess in CV folder

---

## 📱 Responsive Breakpoints

- **Desktop:** 1200px+
- **Laptop:** 992px - 1199px
- **Tablet:** 768px - 991px
- **Mobile:** 320px - 767px

All pages are fully responsive using Bootstrap 5.3.2 grid system.

---

## 🌍 Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ⚠️ IE11 (basic support)

---

## 📞 Support & Documentation

### Files to Reference:
1. `README.md` - Installation (English)
2. `README-URDU.md` - Installation (Urdu)
3. `CUSTOM-POST-TYPE-GUIDE.md` - Jobs system guide
4. `CUSTOM-POST-TYPE-URDU.md` - Jobs guide (Urdu)
5. `CANDIDATE-SYSTEM-GUIDE.md` - Candidate system guide
6. `CANDIDATE-SYSTEM-URDU.md` - Candidate guide (Urdu)
7. `THEME-SUMMARY.md` - Theme overview
8. `COMPLETE-THEME-GUIDE.md` - This file

### Quick Links:
- WordPress Codex: https://codex.wordpress.org/
- Bootstrap Docs: https://getbootstrap.com/docs/5.3/
- Font Awesome: https://fontawesome.com/icons

---

## 🎯 Quick Start Checklist

After installation:

- [ ] Upload and activate theme
- [ ] Go to Settings > Permalinks, save
- [ ] Create pages (Home, About, Jobs, Apply)
- [ ] Assign page templates
- [ ] Set homepage in Settings > Reading
- [ ] Create menu and assign to Primary Menu
- [ ] Add first job in Jobs > Add New
- [ ] Test candidate submission form
- [ ] Check Candidates > All Submissions
- [ ] Add logo (edit header.php or use customizer)
- [ ] Customize colors in theme-styles.css
- [ ] Test on mobile device

---

## 🚀 Going Live Checklist

Before launching:

- [ ] Test all forms
- [ ] Test file uploads
- [ ] Verify emails working
- [ ] Check all pages on mobile
- [ ] Test with real job posting
- [ ] Submit test CV
- [ ] Verify admin interface
- [ ] Check CSV export
- [ ] Test all links
- [ ] Add Google Analytics (optional)
- [ ] Set up SMTP for reliable emails
- [ ] Install security plugin (Wordfence)
- [ ] Install backup plugin (UpdraftPlus)
- [ ] Install caching plugin
- [ ] Test page load speed
- [ ] Add SSL certificate (HTTPS)

---

## 📈 Future Enhancements

The theme is built to support future features:

### Candidate Portal
- Login system for candidates
- Application status tracking
- Profile management
- Application history

### Interview Scheduling
- Calendar integration
- Email reminders
- Interview feedback forms
- Multiple interview rounds

### Advanced Matching
- Skills-based matching
- Resume parsing
- AI recommendations
- Auto-shortlisting

### Email Automation
- Custom email templates
- Automated workflows
- Status-based triggers
- Scheduled communications

### Analytics Dashboard
- Applications by status
- Jobs by category
- Time-to-hire metrics
- Conversion rates

### Multi-language Support
- WPML compatibility
- Translation ready
- RTL support

---

## 💡 Best Practices

### For Admins:

1. **Regular Backups**
   - Daily database backups
   - Weekly file backups

2. **Update Jobs Regularly**
   - Remove expired jobs
   - Update deadlines
   - Keep content fresh

3. **Manage Submissions Promptly**
   - Review within 24 hours
   - Update statuses
   - Respond to candidates

4. **Clean Up**
   - Delete old rejected submissions monthly
   - Archive fulfilled positions
   - Export data before deletion

### For Content:

1. **Job Descriptions**
   - Be specific and clear
   - Use bullet points
   - Include all requirements
   - Set realistic deadlines

2. **Images**
   - Use high-quality images
   - Optimize file sizes
   - Use consistent dimensions
   - Add alt text for SEO

3. **SEO**
   - Use descriptive job titles
   - Add meta descriptions
   - Use relevant keywords
   - Create unique content

---

## ✅ Testing Checklist

### Functionality Tests:

- [ ] Add new job
- [ ] Edit job
- [ ] Delete job
- [ ] Filter jobs by category
- [ ] View single job
- [ ] Submit CV from job page
- [ ] Submit CV from candidate page
- [ ] Upload different file types (PDF, DOC, DOCX)
- [ ] Test file size limit
- [ ] Verify email notifications
- [ ] Change submission status
- [ ] Add internal notes
- [ ] Download CV
- [ ] Export CSV
- [ ] Search submissions
- [ ] Filter by status
- [ ] Delete submission

### Visual Tests:

- [ ] Homepage displays correctly
- [ ] About page layout
- [ ] Jobs grid layout
- [ ] Single job layout
- [ ] Candidate form styling
- [ ] Admin interface design
- [ ] Mobile responsiveness
- [ ] Tablet view
- [ ] Desktop view
- [ ] Large screen view

---

**Theme Version:** 2.0.0  
**WordPress Required:** 5.0+  
**PHP Required:** 7.0+  
**License:** GPL v2 or later

**Ready to use! 🎉**
