# BVH Medical Lab Management System

🏥 **Bahawal Victoria Hospital - Medical Lab Report System**

## Features

✅ **Patient Management** - Store and manage patient information  
✅ **16 Test Categories** - CBC, LFT, PCR, Urine, Vitamins, Hormones & more  
✅ **95+ Test Parameters** - Comprehensive medical testing  
✅ **Multiple Tests in One Report** - Combine tests in single PDF  
✅ **Dynamic Forms** - Auto-generated forms based on test type  
✅ **PDF Generation** - Professional medical reports in PDF format  
✅ **SQLite Database** - No installation required, portable database  
✅ **Abnormal Detection** - Automatic highlighting of out-of-range values  
✅ **Punjab Government Branding** - Official hospital letterhead  

---

## 📊 Available Tests (16 Categories)

### 1. **Haematology** (2 tests)
- **CBC** - Complete Blood Count (11 parameters)
- **ESR** - Erythrocyte Sedimentation Rate

### 2. **Routine Chemistry** (6 tests)
- **LFT** - Liver Function Test (2 parameters)
- **LIPID** - Lipid Profile (5 parameters)
- **CRP** - C-Reactive Protein
- **RFT** - Renal Function Test (5 parameters)
- **TFT** - Thyroid Function Test (5 parameters)
- **HbA1c** - Glycated Hemoglobin

### 3. **Molecular Biology**
- **PCR** - RT-PCR Tests (5 parameters: COVID-19, Hep B/C, HIV, TB)

### 4. **Urine Analysis**
- **URINE** - Complete Urine Examination (18 parameters)

### 5. **Vitamins** (2 tests)
- **VITAMIN-D** - 25-OH Vitamin D
- **VITAMIN-B12** - Vitamin B12

### 6. **Hormones**
- **HORMONES** - Hormone Panel (7 parameters: FSH, LH, Prolactin, etc.)

### 7. **Electrolytes**
- **ELECTROLYTES** - Serum Electrolytes (6 parameters)

### 8. **Serology**
- **SCREENING** - Screening Tests (HIV, HBsAg, HCV)

### 9. **Tumor Markers**
- **TUMOR** - Tumor Markers (6 parameters: CEA, CA125, PSA, etc.)

📄 **See [AVAILABLE_TESTS.md](AVAILABLE_TESTS.md) for complete list of all parameters and reference ranges**

---

## 🚀 Installation

### Requirements
- PHP 7.4 or higher
- Web server (Apache/Nginx) or PHP built-in server
- SQLite3 extension (usually included with PHP)

### Setup Steps

1. **Navigate to Directory**
```bash
cd medical-lab-system
```

2. **Start PHP Server**
```bash
php -S localhost:8000
```

3. **Open Browser**
```
http://localhost:8000
```

4. **Default Login**
- Username: `admin`
- Password: `BVH@2026$Secure#Lab!PathologyAdmin`

**⚠️ IMPORTANT SECURITY NOTE:** This is a strong default password. Please change it after first login from the admin panel for enhanced security.

---

## 📁 Project Structure

```
medical-lab-system/
├── config/
│   └── database.php          # Database configuration & setup
├── data/
│   └── medical_lab.db        # SQLite database (auto-created)
├── vendor/                    # Composer dependencies (TCPDF)
├── index.php                  # Login page
├── login_process.php          # Authentication
├── dashboard.php              # Main dashboard
├── new_report.php             # Create new report
├── generate_pdf.php           # PDF generation
├── logout.php                 # Logout
└── composer.json              # PHP dependencies
```

---

## 🗄️ Database Schema

### Tables
1. **patients** - Patient information
2. **test_categories** - Test types (CBC, LFT, etc.)
3. **test_parameters** - Individual test parameters with ranges
4. **reports** - Generated reports
5. **report_results** - Test results for each report
6. **users** - System users

### Auto-Setup
- Database and tables are created automatically on first run
- Sample data is pre-populated
- No manual SQL import needed!

---

## 📋 How to Use

### Creating a Single Test Report

1. **Login** to the system
2. **Click** on desired test type (CBC, LFT, etc.)
3. **Enter** patient information:
   - MRN (Medical Record Number)
   - Name, Age, Gender
   - Contact information
4. **Fill** test parameters with results
5. **Click** "Generate PDF Report"
6. **PDF** opens automatically for viewing/printing

### Creating a Multiple Tests Report (NEW! ⭐)

1. **Login** to the system
2. **Click** "➕ Multiple Tests Report" button (green button on dashboard)
3. **Enter** patient information (once for all tests)
4. **Select** which tests to include (CBC, LFT, Lipid Profile, etc.)
   - Click on test cards to select/deselect
   - Selected tests show in blue
5. **Fill** parameters for each selected test
   - Parameter sections appear automatically when tests are selected
6. **Click** "Generate Combined Report"
7. **Combined PDF** opens with all tests in one document
   - Patient info shown once at top
   - Each test in separate section
   - Clean separators between tests

### Features of Generated PDF

✅ Hospital branding with Punjab Government logo  
✅ Patient details (MRN, Name, Age, etc.)  
✅ Test parameters with reference ranges  
✅ Abnormal values highlighted with arrows (↑↓)  
✅ Dates (Request, Performed, Published)  
✅ Verified by doctor name  
✅ Professional medical report format  
✅ **Multiple tests in single PDF** (NEW!)  

---

## 🎨 Screenshots

### Dashboard
- Statistics cards (Total Reports, Patients, Pending)
- Quick access to create new reports
- Recent reports table

### Report Form
- Patient information section
- Test information (dates, department, ward)
- Parameters table with reference ranges
- Real-time validation

### PDF Report
- Exact format matching BVH hospital reports
- Professional layout
- Color-coded abnormal values

---

## 🔧 Customization

### Adding New Tests

1. Open `config/database.php`
2. Add to `$categories` array:
```php
['Category Name', 'CODE', 'Test Description']
```
3. Add parameters in similar format
4. Database updates automatically

### Modifying Report Template

Edit `generate_pdf.php` → `generateReportHTML()` function

---

## 📞 Support

For issues or questions:
- Check database is created in `/data/` folder
- Ensure PHP version ≥ 7.4
- Run `composer install` if PDF not working

---

## 📄 License

Internal use for Bahawal Victoria Hospital

---

**Made with ❤️ for BVH Medical Laboratory**
