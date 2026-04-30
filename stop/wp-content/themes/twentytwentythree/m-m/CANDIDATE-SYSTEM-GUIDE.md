# 🎓 Candidate Submissions System - Complete Guide

## 📊 Overview

A complete **Custom Database Table System** for managing candidate CV submissions with a professional admin interface.

## ✨ Features

### 1. Custom Database Table
- ✅ Dedicated table: `wp_candidate_submissions`
- ✅ 15 fields for complete candidate information
- ✅ Indexes for fast queries
- ✅ Auto-created on theme activation

### 2. Admin Interface
- ✅ **Candidates** menu in WordPress admin
- ✅ Professional listing page
- ✅ Status filters (New, Reviewing, Shortlisted, Contacted, Rejected)
- ✅ Search functionality
- ✅ Pagination
- ✅ CSV export

### 3. Candidate Management
- ✅ View all submissions
- ✅ View individual submission details
- ✅ Download CV files
- ✅ Update status
- ✅ Add internal notes
- ✅ Delete submissions
- ✅ Link to applied job

### 4. Form Features
- ✅ CV file upload (PDF, DOC, DOCX)
- ✅ File size validation (5MB max)
- ✅ Secure file storage
- ✅ Auto-link to jobs
- ✅ Email notifications
- ✅ Success/Error messages

## 🗄️ Database Structure

### Table: `wp_candidate_submissions`

```sql
id                  - Unique submission ID
candidate_name      - Full name
job_title           - Current/desired job title
candidate_email     - Email address
candidate_phone     - Phone number
cv_file_path        - Server path to CV file
cv_file_name        - Original CV filename
applied_job_id      - ID of job applied for (if any)
applied_job_title   - Title of job applied for
message             - Cover letter/message
status              - Current status (new, reviewing, shortlisted, contacted, rejected)
ip_address          - Submitter's IP
user_agent          - Browser information
submission_date     - When submitted
last_updated        - Last modification date
notes               - Internal admin notes
```

## 📝 How It Works

### Step 1: Candidate Submits CV

**From Candidate Page:**
```
1. Candidate fills form:
   - Name
   - Job Title
   - Email
   - Phone
   - Message (optional)
   - CV Upload (required)

2. Form validates:
   - Required fields
   - File type (PDF, DOC, DOCX)
   - File size (max 5MB)

3. Data saved to database
4. CV stored securely in /wp-content/uploads/candidate-cvs/
5. Emails sent (admin + candidate)
```

**From Job Page:**
```
1. Candidate clicks "Submit Your CV" on job detail page
2. Redirected to candidate page with ?job_id=123
3. Job ID automatically linked to submission
4. All above steps apply
```

### Step 2: Admin Views Submissions

**Admin Menu: Candidates**

#### All Submissions Page:
- View all candidate submissions in table format
- Columns shown:
  - ID
  - Name
  - Email
  - Phone
  - Job Title
  - Applied For (linked job)
  - CV Download button
  - Status dropdown
  - Date
  - Actions (View/Delete)

#### Status Filters:
Click on status tabs to filter:
- **All** - All submissions
- **New** - Fresh submissions
- **Reviewing** - Under review
- **Shortlisted** - Selected candidates
- **Contacted** - Already contacted
- **Rejected** - Not suitable

#### Search:
Search by:
- Candidate name
- Email address
- Job title

### Step 3: View Submission Details

Click **View** button to see:
- Complete candidate information
- Link to applied job (if any)
- CV download button
- Status badge (color-coded)
- Submission details (IP, date, browser)
- **Internal Notes** section (add private notes)

### Step 4: Manage Submissions

**Change Status:**
- Click status dropdown in list
- Select new status
- Auto-saves immediately
- Color-coded:
  - Yellow = New
  - Blue = Reviewing
  - Green = Shortlisted
  - Gray = Contacted
  - Red = Rejected

**Download CV:**
- Click Download button
- File downloads with original filename
- Secure - only admins can download

**Add Notes:**
- Go to submission detail page
- Scroll to "Internal Notes"
- Add private notes (not visible to candidate)
- Click "Update Notes"

**Delete Submission:**
- Click Delete button
- Confirm deletion
- Removes from database
- Deletes CV file from server

### Step 5: Export Data

**CSV Export:**
1. Click "Export to CSV" button
2. Downloads CSV file with all submissions
3. Includes: ID, Name, Email, Phone, Job Title, Applied For, Status, Date
4. Filename: `candidate-submissions-YYYY-MM-DD.csv`

## 🔒 Security Features

### File Upload Security:
- ✅ File type validation (only PDF, DOC, DOCX)
- ✅ File size limit (5MB)
- ✅ Unique filenames (prevents overwriting)
- ✅ Secure storage directory
- ✅ .htaccess protection (no direct access)
- ✅ Admin-only download

### Form Security:
- ✅ WordPress nonce verification
- ✅ Data sanitization
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ CSRF protection

### Admin Security:
- ✅ Capability checking (manage_options)
- ✅ Nonce verification for all actions
- ✅ Secure file deletion

## 📧 Email Notifications

### Admin Email:
Sent to site admin when new submission received:
```
Subject: New Candidate Submission - [Name]

Body:
New candidate submission received:

Name: [Name]
Email: [Email]
Phone: [Phone]
Job Title: [Job Title]
Applied For: [Job Name] (if any)

View in admin: [Link]
```

### Candidate Email:
Sent to candidate as confirmation:
```
Subject: Thank you for your application - M&E Recruitment

Body:
Dear [Name],

Thank you for submitting your CV to M&E Recruitment.

We have received your application and will review it shortly. 
If your qualifications match our current opportunities, we will be in touch.

Best regards,
M&E Recruitment Team
```

## 🎨 Admin Interface Features

### Visual Indicators:
- **Status Colors:**
  - 🟡 Yellow = New
  - 🔵 Blue = Reviewing
  - 🟢 Green = Shortlisted
  - ⚫ Gray = Contacted
  - 🔴 Red = Rejected

- **Icons:**
  - 📥 Download = CV available
  - ➖ Dash = No CV/No job link
  - ⭐ Star = Featured job

### Quick Actions:
- ✅ One-click status update
- ✅ Quick view submission
- ✅ Instant CV download
- ✅ Fast delete with confirmation

## 🔗 Job Integration

### Automatic Linking:
When candidate applies from job page:
1. Job ID automatically captured
2. Job title stored
3. Link created in database
4. Visible in admin:
   - Clickable job title
   - Link to edit job
   - Link to view job on site

### Future Expansion Ready:
The system is designed for future features:
- Multiple job applications per candidate
- Application history tracking
- Interview scheduling
- Candidate matching
- Email templates
- Automated workflows

## 📊 Reporting

### Available Data:
- Total submissions
- Submissions by status
- Submissions by date
- Applications per job
- Search and filter
- CSV export for external analysis

## 🛠️ Technical Details

### Functions Added:

1. **me_recruitment_create_candidate_table()**
   - Creates database table on theme activation
   - Runs on `after_switch_theme` hook

2. **me_recruitment_add_candidate_menu()**
   - Adds admin menu items
   - Runs on `admin_menu` hook

3. **me_recruitment_candidate_submissions_page()**
   - Main listing page
   - Handles filters, search, pagination
   - Status updates, delete actions

4. **me_recruitment_view_candidate_submission()**
   - Individual submission view
   - Notes management

5. **me_recruitment_process_candidate_submission()**
   - Handles form submission
   - File upload
   - Data validation
   - Email sending

6. **me_recruitment_download_cv()**
   - Secure CV download
   - Runs on `admin_init` hook

7. **me_recruitment_export_candidates_csv()**
   - CSV export functionality
   - Runs on `admin_init` hook

### File Storage:
- **Location:** `/wp-content/uploads/candidate-cvs/`
- **Protection:** `.htaccess` denies direct access
- **Naming:** `[Name]_[Timestamp].[ext]`
- **Auto-cleanup:** Files deleted when submission deleted

## 🚀 Usage Guide

### For Site Admins:

1. **After Theme Installation:**
   - Database table created automatically
   - "Candidates" menu appears in admin

2. **Check Submissions:**
   - Go to **Candidates > All Submissions**
   - View list of all applications

3. **Review Candidate:**
   - Click "View" on any submission
   - Review all details
   - Download CV
   - Add notes

4. **Update Status:**
   - Change status dropdown
   - Track progress through hiring process

5. **Export Data:**
   - Click "Export to CSV"
   - Use in Excel/Sheets for analysis

### For Candidates:

1. **Submit Application:**
   - Go to Candidate page
   - OR click "Submit Your CV" on job page
   - Fill form
   - Upload CV
   - Submit

2. **Confirmation:**
   - Success message shown
   - Confirmation email sent

## 📈 Benefits

✅ **Organized:** All applications in one place
✅ **Trackable:** Status tracking per candidate
✅ **Linked:** Auto-link to jobs applied for
✅ **Searchable:** Fast search and filter
✅ **Exportable:** CSV export for reporting
✅ **Secure:** Protected file storage
✅ **Professional:** Clean admin interface
✅ **Scalable:** Handles unlimited submissions
✅ **Flexible:** Easy to extend in future

## 🔮 Future Enhancement Ideas

The system is built to support:
- [ ] Multiple applications per candidate
- [ ] Email templates system
- [ ] Interview scheduling
- [ ] Automated status emails
- [ ] Candidate portal
- [ ] Resume parsing
- [ ] Skills matching
- [ ] Application tracking
- [ ] Recruitment pipeline
- [ ] Analytics dashboard

## 🎯 Summary

You now have a **PROFESSIONAL CANDIDATE MANAGEMENT SYSTEM** with:

✅ Custom database table
✅ Admin interface
✅ CV file management
✅ Status tracking
✅ Job linking (ready for future)
✅ Email notifications
✅ CSV export
✅ Search & filter
✅ Complete security

No plugins needed - all built into your theme!

---

**Perfect for:**
- Recruitment agencies
- HR departments
- Job boards
- Employment sites
- Staffing companies

**Ready to use immediately after theme activation!** 🎉
