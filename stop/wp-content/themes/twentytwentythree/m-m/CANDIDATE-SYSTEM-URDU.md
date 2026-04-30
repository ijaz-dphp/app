# 🎓 Candidate Submissions System - اردو گائیڈ

## 📊 کیا ہے؟

یہ ایک **Professional Database System** ہے جو candidates کی CV submissions کو manage کرتا ہے۔

## ✨ خصوصیات

### 1. Custom Database Table
- ✅ الگ table: `wp_candidate_submissions`
- ✅ 15 fields مکمل معلومات کے لیے
- ✅ Fast searching
- ✅ Theme activate کرتے ہی بن جاتی ہے

### 2. Admin Interface
- ✅ WordPress admin میں **Candidates** menu
- ✅ تمام submissions کی list
- ✅ Status filters (New, Reviewing, Shortlisted, etc.)
- ✅ Search کی سہولت
- ✅ CSV export

### 3. مینجمنٹ Features
- ✅ تمام applications دیکھیں
- ✅ ہر candidate کی تفصیل
- ✅ CV download کریں
- ✅ Status update کریں
- ✅ Notes شامل کریں
- ✅ Applications delete کریں
- ✅ Job سے link دیکھیں

## 🗄️ Database کی تفصیل

### Table Fields:

```
id                  - Unique number
candidate_name      - نام
job_title           - Job title
candidate_email     - Email
candidate_phone     - Phone number
cv_file_path        - CV کی location
applied_job_id      - کس job کے لیے apply کیا
status              - موجودہ status
notes               - Admin کے notes
submission_date     - تاریخ
```

## 📝 کیسے کام کرتا ہے؟

### Step 1: Candidate Form بھرتا ہے

**Candidate Page سے:**
1. Form بھریں:
   - نام
   - Job Title
   - Email
   - Phone
   - Message (optional)
   - CV Upload (required)

2. Submit کریں
3. Database میں save ہو جاتا ہے
4. CV secure folder میں store ہوتی ہے
5. Emails بھیجے جاتے ہیں (admin + candidate)

**Job Page سے:**
1. Job detail page پر "Submit Your CV" click کریں
2. Candidate page کھل جاتا ہے
3. Job ID automatically link ہو جاتی ہے
4. باقی process same ہے

### Step 2: Admin Submissions دیکھتا ہے

**Admin Menu: Candidates > All Submissions**

یہاں نظر آئے گا:
- ID
- نام
- Email
- Phone
- Job Title
- کس job کے لیے apply کیا
- CV Download button
- Status (change کر سکتے ہیں)
- تاریخ
- Actions (View/Delete)

### Step 3: Details دیکھیں

**View** button پر click کریں:
- مکمل candidate information
- Applied job کا link
- CV download
- Status (color coded)
- Submission details
- **Notes section** (private notes شامل کریں)

### Step 4: Manage کریں

**Status Change کریں:**
1. Status dropdown click کریں
2. نیا status select کریں
3. Automatically save ہو جاتا ہے

**Status Colors:**
- 🟡 Yellow = New (نیا)
- 🔵 Blue = Reviewing (review میں)
- 🟢 Green = Shortlisted (منتخب)
- ⚫ Gray = Contacted (رابطہ کیا)
- 🔴 Red = Rejected (رد کیا)

**CV Download:**
- Download button click کریں
- CV download ہو جاتی ہے
- Secure - صرف admin download کر سکتے ہیں

**Notes شامل کریں:**
1. Submission detail page پر جائیں
2. "Internal Notes" section میں notes لکھیں
3. "Update Notes" click کریں

**Delete کریں:**
1. Delete button click کریں
2. Confirm کریں
3. Database سے remove ہو جاتا ہے
4. CV file بھی delete ہو جاتی ہے

### Step 5: Export کریں

**CSV Export:**
1. "Export to CSV" button click کریں
2. تمام submissions کی CSV file download ہو گی
3. Excel میں کھول سکتے ہیں

## 🔒 Security

### File Upload:
- ✅ صرف PDF, DOC, DOCX allowed
- ✅ Maximum 5MB
- ✅ Secure storage
- ✅ Unique filenames
- ✅ Protected directory

### Form Security:
- ✅ WordPress nonce verification
- ✅ Data sanitization
- ✅ SQL injection prevention
- ✅ XSS protection

## 📧 Email Notifications

### Admin کو Email:
```
Subject: New Candidate Submission - [نام]

نیا candidate application:
نام: [نام]
Email: [Email]
Phone: [Phone]
Job: [Job Title]
```

### Candidate کو Email:
```
Subject: Thank you for your application

Dear [نام],

آپ کی CV submit ہو گئی ہے۔
ہم review کر کے رابطہ کریں گے۔

Best regards,
M&E Recruitment Team
```

## 🎨 Admin Interface

### Visual Indicators:
- **Status Colors** (color coded status)
- **Download Icons** (CV available)
- **Job Links** (applied job link)

### Quick Actions:
- ✅ One-click status update
- ✅ Quick view
- ✅ Instant download
- ✅ Fast delete

## 🔗 Job سے Link

### Automatic Linking:
جب candidate job page سے apply کرتا ہے:
1. Job ID automatically save ہوتی ہے
2. Job title store ہوتا ہے
3. Admin میں link نظر آتا ہے
4. Job edit/view کر سکتے ہیں

### Future Ready:
یہ system مستقبل میں بڑھایا جا سکتا ہے:
- Multiple applications
- Interview scheduling
- Email templates
- Application tracking
- Candidate matching

## 📊 Reporting

### Data Available:
- کل submissions
- Status کے حساب سے
- تاریخ کے حساب سے
- Job کے حساب سے
- CSV export

## 🚀 استعمال کی ہدایات

### Admin کے لیے:

1. **Theme Install کرنے کے بعد:**
   - Database table automatically بن جاتی ہے
   - "Candidates" menu نظر آتا ہے

2. **Submissions دیکھنے کے لیے:**
   - **Candidates > All Submissions** پر جائیں
   - تمام applications کی list

3. **Review کرنے کے لیے:**
   - "View" click کریں
   - تفصیل دیکھیں
   - CV download کریں
   - Notes شامل کریں

4. **Status Update:**
   - Dropdown سے status change کریں

5. **Export:**
   - "Export to CSV" click کریں
   - Excel میں analyze کریں

### Candidates کے لیے:

1. **Apply کریں:**
   - Candidate page پر جائیں
   - یا job page سے "Submit Your CV" click کریں
   - Form بھریں
   - CV upload کریں
   - Submit کریں

2. **Confirmation:**
   - Success message نظر آئے گا
   - Email آئے گی

## 📈 فوائد

✅ **Organized** - سب ایک جگہ
✅ **Trackable** - Status track کریں
✅ **Linked** - Jobs سے link
✅ **Searchable** - آسانی سے تلاش
✅ **Exportable** - CSV export
✅ **Secure** - محفوظ storage
✅ **Professional** - خوبصورت interface
✅ **Scalable** - unlimited submissions

## 🎯 خلاصہ

اب آپ کے پاس **Professional System** ہے:

✅ Custom database table
✅ Admin interface
✅ CV file management
✅ Status tracking
✅ Job linking
✅ Email notifications
✅ CSV export
✅ Search & filter
✅ مکمل security

**کوئی plugin کی ضرورت نہیں - سب کچھ theme میں!**

---

**استعمال:**
- Recruitment agencies
- HR departments
- Job boards
- Employment sites

**Theme activate کرتے ہی استعمال کے لیے تیار!** 🎉
