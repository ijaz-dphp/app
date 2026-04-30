# M&E Recruitment WordPress Theme - اردو ہدایات

## تھیم کی معلومات

یہ ایک مکمل WordPress theme ہے جو آپ کے HTML pages سے بنائی گئی ہے۔

## انسٹالیشن کی ہدایات

### طریقہ 1: WordPress Admin سے اپلوڈ کریں

1. پورے theme folder (m-m) کو ZIP file بنائیں
2. اپنے WordPress admin panel میں لاگ ان کریں
3. **Appearance > Themes** پر جائیں
4. **Add New** پر کلک کریں
5. **Upload Theme** پر کلک کریں
6. ZIP file منتخب کریں اور **Install Now** پر کلک کریں
7. Install ہونے کے بعد **Activate** پر کلک کریں

### طریقہ 2: FTP سے Manual Installation

1. Theme folder کو download کریں
2. FTP سے اپنے server سے connect کریں
3. `/wp-content/themes/` folder میں جائیں
4. پورا theme folder یہاں upload کریں
5. WordPress admin میں **Appearance > Themes** پر جائیں
6. "M&E Recruitment" theme کو **Activate** کریں

## Setup کی ہدایات

### 1. Pages بنائیں

WordPress میں یہ pages بنائیں:

- **Home** - Template: "Front Page (Home)" منتخب کریں
- **About** - Template: "About Page" منتخب کریں
- **Candidate** - Template: "Candidate Page" منتخب کریں
- **Jobs** - Template: "Jobs Page" منتخب کریں

### 2. Homepage کو Configure کریں

1. **Settings > Reading** پر جائیں
2. "A static page" کو select کریں
3. Homepage کے لیے "Home" page منتخب کریں
4. Save کریں

### 3. Menus بنائیں

1. **Appearance > Menus** پر جائیں
2. "Primary Menu" نام سے نیا menu بنائیں
3. Pages شامل کریں: Jobs, Candidate
4. "Primary Menu" location میں assign کریں
5. "Footer Menu" نام سے دوسرا menu بنائیں
6. Pages شامل کریں اور "Footer Menu" location میں assign کریں

### 4. رابطہ کی معلومات

1. **Appearance > Customize** پر جائیں
2. **Contact Information** پر کلک کریں
3. اپنی معلومات update کریں:
   - Phone Number
   - WhatsApp Number
   - Email
   - Address

### 5. Social Media Links

1. Customizer میں **Social Media Links** پر کلک کریں
2. اپنے social media URLs شامل کریں

### 6. Logo Upload کریں

1. Customizer میں **Site Identity** پر کلک کریں
2. **Select Logo** پر کلک کریں
3. اپنا logo upload کریں

## Jobs کیسے شامل کریں

1. **Posts > Add New** پر جائیں
2. Job کا title اور description لکھیں
3. Custom fields شامل کریں:
   - job_location
   - job_salary
   - job_type
4. Post publish کریں

## فائلوں کی تفصیل

```
m-m/
├── css/ - تمام CSS files
├── images/ - تمام تصاویر
├── js/ - JavaScript files
├── functions.php - Theme کی اہم functions
├── header.php - Header template
├── footer.php - Footer template
├── front-page.php - Homepage template
├── page-about.php - About page template
├── page-candidate.php - Candidate page template
├── page-jobs.php - Jobs page template
├── style.css - Theme کی main stylesheet
└── README.md - تفصیلی ہدایات
```

## اہم نوٹس

- تمام WordPress functions کی errors نظر آئیں تو پریشان نہ ہوں - یہ صرف WordPress environment میں کام کریں گے
- Theme کو WordPress میں install کرنے کے بعد سب کچھ ٹھیک کام کرے گا
- اپنی HTML files کو backup رکھیں

## مدد کے لیے

کسی بھی مسئلے کے لیے M&E Recruitment سے رابطہ کریں۔

خوش آمدید! آپ کی WordPress theme تیار ہے! 🎉
