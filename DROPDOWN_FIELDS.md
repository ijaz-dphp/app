# 🎯 Dropdown Fields Guide

## Tests with Auto-Dropdown Fields

The system now automatically shows **dropdown menus** instead of text inputs for tests that have predefined result options (like Positive/Negative).

---

## 📋 Tests with Dropdowns

### 1. **Serology - Screening Tests**
All 3 parameters have dropdowns:
- **Anti HIV**: Negative / Positive
- **HBsAg**: Negative / Positive
- **Anti HCV**: Negative / Positive

### 2. **PCR - RT-PCR Tests**
All 5 parameters have dropdowns:
- **COVID-19 RT-PCR**: Negative / Positive
- **Hepatitis B PCR**: Not Detected / Detected
- **Hepatitis C PCR**: Not Detected / Detected
- **HIV RNA PCR**: Not Detected / Detected
- **TB PCR (MTB)**: Not Detected / Detected

### 3. **Urine Analysis**
Several parameters have dropdowns:

**Physical Examination:**
- **Color**: Pale Yellow / Yellow / Dark Yellow / Amber / Red / Brown
- **Appearance**: Clear / Turbid / Hazy / Cloudy

**Chemical Examination:**
- **Protein**: Negative / Positive
- **Glucose**: Negative / Positive
- **Ketones**: Negative / Positive
- **Blood**: Negative / Positive
- **Bilirubin**: Negative / Positive
- **Urobilinogen**: Normal / Abnormal
- **Nitrite**: Negative / Positive
- **Leukocyte Esterase**: Negative / Positive

**Microscopic Examination:**
- **Bacteria**: Nil / Few / Moderate / Many
- **Crystals**: Nil / Few / Moderate / Many
- **Epithelial Cells**: Nil / Few / Moderate / Many

---

## 🔍 How It Works

The system **automatically detects** which parameters need dropdowns based on their reference ranges:

### Detection Rules:

1. **Negative/Positive Tests**
   - If reference range contains "Negative"
   - Shows: `Negative` or `Positive`

2. **Detected/Not Detected Tests**
   - If reference range contains "Not Detected"
   - Shows: `Not Detected` or `Detected`

3. **Normal/Abnormal Tests**
   - If reference range contains "Normal"
   - Shows: `Normal` or `Abnormal`

4. **Nil/Few/Many Tests**
   - If reference range contains "Nil" or "Few"
   - Shows: `Nil`, `Few`, `Moderate`, `Many`

5. **Color Tests**
   - If test name is "Color" and reference is "Pale Yellow"
   - Shows: Multiple color options

6. **Appearance Tests**
   - If test name is "Appearance" and reference is "Clear"
   - Shows: `Clear`, `Turbid`, `Hazy`, `Cloudy`

---

## 💡 Benefits

✅ **Faster Data Entry** - Select instead of typing  
✅ **No Typos** - Standardized values  
✅ **Consistent Results** - Same format every time  
✅ **Easy to Use** - Click and select  
✅ **Works Automatically** - No configuration needed  

---

## 📝 Example Usage

### Before (Text Input):
```
Anti HIV: [Enter value______]  ❌ Could type: "negative", "Neg", "NEG"
```

### After (Dropdown):
```
Anti HIV: [Negative ▼]  ✅ Select: Negative or Positive
```

---

## 🎨 Visual Indicators

- **Dropdowns** have a ▼ arrow icon
- **Dropdowns** have blue focus border
- **Text inputs** remain for numeric values (like WBC count, glucose levels, etc.)

---

## 🔧 Technical Details

### Files Modified:
- `new_report.php` - Single test report form
- `new_report_multi.php` - Multiple tests report form

### Logic:
- PHP checks each parameter's reference range
- Automatically determines if dropdown is needed
- Generates appropriate input field (dropdown or text)
- Works for both single and multiple test reports

---

## 📊 Summary

**Total Dropdown Parameters:** ~30+ across all tests

**Affected Tests:**
- ✅ Serology (3 dropdowns)
- ✅ PCR (5 dropdowns)
- ✅ Urine Analysis (15+ dropdowns)

**Numeric Input Tests:**
- CBC, LFT, Lipid, RFT, TFT, Hormones, Electrolytes, Tumor Markers (all remain text inputs for numbers)

---

**Last Updated:** February 12, 2026  
**Feature:** Auto-Dropdown Detection System
