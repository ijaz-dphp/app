# 🏥 BVH Medical Lab System - Complete Flow Diagram

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    USER FLOW                                │
└─────────────────────────────────────────────────────────────┘

1. LOGIN PAGE (index.php)
   │
   ├─► Enter username: admin
   ├─► Enter password: BVH@2026$Secure#Lab!PathologyAdmin
   └─► Submit → login_process.php
       │
       ├─► Verify credentials from SQLite database
       └─► Create session
           │
           └─► Redirect to Dashboard

2. DASHBOARD (dashboard.php)
   │
   ├─► Display Statistics:
   │   ├─► Total Reports
   │   ├─► Total Patients
   │   └─► Pending Reports
   │
   ├─► Show Test Categories:
   │   ├─► CBC - Complete Blood Count
   │   ├─► LFT - Liver Function Test
   │   ├─► LIPID - Lipid Profile
   │   ├─► CRP - High Sensitivity
   │   └─► SCREENING - Serology Tests
   │
   └─► Recent Reports Table
       └─► View/Print existing reports

3. NEW REPORT (new_report.php?category=X)
   │
   ├─► Patient Information Form:
   │   ├─► MRN (Medical Record Number)
   │   ├─► Patient Name
   │   ├─► Contact Number
   │   ├─► Age (format: 28Y 8M 16D)
   │   ├─► Gender (Male/Female)
   │   └─► Father/Husband Name
   │
   ├─► Test Information:
   │   ├─► Department (OPD/IPD)
   │   ├─► Ward
   │   ├─► Request Date
   │   ├─► Performed Date
   │   ├─► Published Date
   │   └─► Verified By (Doctor Name)
   │
   └─► Test Parameters Table:
       │
       ├─► Dynamic fields based on test type
       ├─► Shows reference ranges
       └─► Enter result values
           │
           └─► Submit → Save to database
               │
               └─► Redirect to PDF generation

4. GENERATE PDF (generate_pdf.php?id=X)
   │
   ├─► Fetch report from database
   ├─► Fetch patient information
   ├─► Fetch test results
   │
   ├─► Check for abnormal values:
   │   ├─► If value < min → Mark with ↓ (red)
   │   └─► If value > max → Mark with ↑ (red)
   │
   └─► Display Professional Report:
       ├─► Hospital Header (BVH)
       ├─► Patient Details
       ├─► Test Results Table
       ├─► Footer with verification
       └─► Print Button → Save as PDF


┌─────────────────────────────────────────────────────────────┐
│                  DATABASE STRUCTURE                         │
└─────────────────────────────────────────────────────────────┘

USERS TABLE
├─► id (Primary Key)
├─► username
├─► password (hashed)
├─► full_name
└─► role

PATIENTS TABLE
├─► id (Primary Key)
├─► mrn (Unique)
├─► name
├─► contact
├─► age
├─► gender
└─► father_husband_name

TEST_CATEGORIES TABLE
├─► id (Primary Key)
├─► name (e.g., "Haematology")
├─► code (e.g., "CBC")
└─► description

TEST_PARAMETERS TABLE
├─► id (Primary Key)
├─► category_id (Foreign Key)
├─► test_name (e.g., "WBC")
├─► min_value
├─► max_value
├─► unit
└─► display_order

REPORTS TABLE
├─► id (Primary Key)
├─► patient_id (Foreign Key)
├─► category_id (Foreign Key)
├─► request_date
├─► performed_date
├─► published_date
├─► department
├─► ward
├─► verified_by
└─► status

REPORT_RESULTS TABLE
├─► id (Primary Key)
├─► report_id (Foreign Key)
├─► parameter_id (Foreign Key)
├─► result_value
└─► is_abnormal (0 or 1)


┌─────────────────────────────────────────────────────────────┐
│              TEST PARAMETERS BREAKDOWN                      │
└─────────────────────────────────────────────────────────────┘

CBC - COMPLETE BLOOD COUNT
├─► WBC          : 4.0 - 11.0 /mm³
├─► RBC          : 3.6 - 5.8 mm³
├─► HGB          : 10.0 - 15.0 g/dl
├─► HCT          : 37.0 - 47.0 %
├─► MCV          : 76.0 - 96.0 fl
├─► MCHC         : 30.0 - 35.0 G/dl
├─► PLT          : 150.0 - 400.0 mm³
├─► %Neut        : (no range) %
├─► %LYMP        : 20.0 - 45.0 %
├─► MCH          : 27.0 - 32.0 pg
└─► MXD%         : 4.0 - 10.0 %

LIVER FUNCTION TEST
├─► ALT                : < 40.0 U/L
└─► Total Bilirubin    : < 1.0 mg/dl

LIPID PROFILE
├─► TRIGLYCERIDES      : < 150 mg/dl
├─► Total Cholesterol  : < 200 mg/dl
├─► HDL CHOLESTEROL    : > 35 mg/dl
├─► LDL                : < 150 mg/dl
└─► VLDL               : < 30 mg/dl

CRP - HIGH SENSITIVITY
└─► HS-CRP             : < 5.0 mg/l

SEROLOGY SCREENING
├─► Anti HIV           : Negative
├─► HBsAg              : Negative
└─► Anti HCV           : Negative


┌─────────────────────────────────────────────────────────────┐
│                    FEATURES                                 │
└─────────────────────────────────────────────────────────────┘

✅ AUTO DATABASE CREATION
   - SQLite database creates automatically
   - No manual SQL import needed
   - All tables and sample data pre-populated

✅ DYNAMIC FORMS
   - Forms change based on test type
   - Reference ranges shown for each parameter
   - Validation for required fields

✅ ABNORMAL DETECTION
   - Automatic comparison with reference ranges
   - Red color for out-of-range values
   - Up/Down arrows (↑↓) for high/low

✅ PDF GENERATION
   - Professional hospital format
   - Print to PDF from browser
   - Matches BVH original reports

✅ PATIENT TRACKING
   - Store patient information
   - Track previous test results
   - MRN-based identification

✅ MULTI-TEST SUPPORT
   - 5 different test categories
   - 22+ total parameters
   - Easy to add more tests


┌─────────────────────────────────────────────────────────────┐
│                 INSTALLATION STEPS                          │
└─────────────────────────────────────────────────────────────┘

STEP 1: Install PHP
────────────────────
brew install php

STEP 2: Navigate to Project
────────────────────────────
cd /Users/ejazmac/Downloads/min-cart/bvh/medical-lab-system

STEP 3: Start Server
────────────────────
php -S localhost:8000

STEP 4: Open Browser
────────────────────
http://localhost:8000

STEP 5: Login
─────────────
Username: admin
Password: BVH@2026$Secure#Lab!PathologyAdmin


┌─────────────────────────────────────────────────────────────┐
│                    USAGE EXAMPLE                            │
└─────────────────────────────────────────────────────────────┘

Example: Creating CBC Report for Patient

1. Login with admin credentials
2. Click "CBC - Complete Blood Count"
3. Fill patient details:
   - MRN: 1202417794119
   - Name: NADIA
   - Contact: 3062360006
   - Age: 28Y 8M 16D
   - Gender: Female
   - Department: IPD
   - Ward: Gynae I

4. Fill test results:
   - WBC: 10.1
   - RBC: 3.88
   - HGB: 10.7
   - (etc...)

5. Click "Generate PDF Report"
6. View/Print professional report
7. Report saved in database automatically


┌─────────────────────────────────────────────────────────────┐
│                  TECHNICAL DETAILS                          │
└─────────────────────────────────────────────────────────────┘

Technology Stack:
├─► Backend: PHP 7.4+
├─► Database: SQLite (portable)
├─► Frontend: HTML5, CSS3
├─► PDF: Browser Print-to-PDF
└─► Authentication: Session-based

File Structure:
medical-lab-system/
├── config/
│   └── database.php       (250+ lines - DB setup)
├── data/
│   └── medical_lab.db     (auto-created)
├── index.php              (Login page)
├── login_process.php      (Auth handler)
├── dashboard.php          (Main dashboard)
├── new_report.php         (Report creation)
├── generate_pdf.php       (PDF generation)
├── logout.php             (Session destroy)
└── README.md              (Documentation)

Database Size: ~50KB (with sample data)
Total Lines of Code: ~1,500+
Dependencies: None (pure PHP + SQLite)


┌─────────────────────────────────────────────────────────────┐
│                    NEXT STEPS                               │
└─────────────────────────────────────────────────────────────┘

1. Install PHP: brew install php
2. Start server: php -S localhost:8000
3. Test the system
4. Customize if needed:
   - Add more tests in database.php
   - Modify report template in generate_pdf.php
   - Add hospital logo/branding
   - Customize colors/styles

🎉 SYSTEM IS READY TO USE!
