# Last Results Feature - PDF Reports

## Overview
Updated both `download_pdf.php` and `download_pdf_bulk.php` to display the last 3 previous test results alongside the current result for easy comparison.

## Changes Made

### 1. Database Queries
- Added query to fetch the last 3 previous reports for the same patient and category
- Query filters by: same patient_id, same category_id, earlier report (id < current), status = 'completed'
- Results ordered by performed_date DESC, limited to 3

### 2. Column Structure
**Table Headers:**
- Test: 55mm (left-aligned)
- Reference Ranges: 45mm (left-aligned)
- Unit: 25mm (left-aligned)
- Result: 18mm (center-aligned) - Current result
- Last Results: 37mm (center-aligned) - Split into columns for previous results

### 3. Dynamic Column Division
- Previous results column divides equally based on number of reports found (1-3)
- Each previous result gets its own sub-column under "Last Results"
- If no previous reports exist, "Last Results" column is not shown

### 4. Visual Format Example
```
┌─────────────┬──────────────────┬──────┬────────┬─────────────────┐
│ Test        │ Reference Ranges │ Unit │ Result │  Last Results   │
├─────────────┼──────────────────┼──────┼────────┼─────┬─────┬─────┤
│             │                  │      │  BVH   │ BVH │ BVH │ BVH │
│             │                  │      │13-Feb-26│12-Feb│10-Feb│
│             │                  │      │05:25 PM│03:37A│12:47A│
├─────────────┼──────────────────┼──────┼────────┼─────┼─────┼─────┤
│ Sodium      │ 135.0 - 145.0    │mmol/L│  143   │ 145 │ 137 │     │
│ Potassium   │ 3.5-5.5          │mmol/L│  4.0   │ 3.9 │ 4.1 │     │
└─────────────┴──────────────────┴──────┴────────┴─────┴─────┴─────┘
```

### 5. Features
- ✅ Shows up to 3 previous results per test parameter in separate columns
- ✅ Only displays "Last Results" column if previous reports exist
- ✅ Each previous result gets equal column width under "Last Results"
- ✅ Maintains red bold formatting for abnormal current values
- ✅ Center-aligned result columns for better readability
- ✅ Works for both single PDF (`download_pdf.php`) and bulk PDF (`download_pdf_bulk.php`)
- ✅ BVH header, date, and time shown for each result column
- ✅ Empty cells when fewer than 3 previous results exist

### 6. Technical Details
- Previous results fetched per parameter_id to ensure correct matching
- Column width calculation: `$prevColWidth = $prevResultCol / $numPrevReports`
- Date format: `d-M-y` (e.g., 13-Feb-26)
- Time format: `h:i A` (e.g., 05:25 PM)
- Results positioned with proper spacing for readability

## Files Modified
1. `/download_pdf.php` - Single report PDF generation
2. `/download_pdf_bulk.php` - Multiple reports in one PDF

## Database Schema Used
- `reports` table: patient_id, category_id, performed_date, status
- `report_results` table: report_id, parameter_id, result_value
- Relationship: patient + category → multiple reports over time

## Testing
Database shows multiple reports available:
- Patient 1, Category 1: 2 reports
- Patient 1, Category 2: 2 reports  
- Patient 1, Category 17: 5 reports
- Patient 2, Category 4: 2 reports
- Patient 2, Category 17: 2 reports

All syntax validated successfully. ✅

### 4. Features
- ✅ Shows up to 3 previous results per test parameter
- ✅ Only displays if previous reports exist (no empty columns)
- ✅ Maintains red bold formatting for abnormal current values
- ✅ Center-aligned result columns for better readability
- ✅ Works for both single PDF (`download_pdf.php`) and bulk PDF (`download_pdf_bulk.php`)

### 5. Technical Details
- Previous results fetched per parameter_id to ensure correct matching
- Empty check prevents display issues when no previous data exists
- Date/time formatting consistent with original report format
- Results positioned with proper spacing for readability

## Files Modified
1. `/download_pdf.php` - Single report PDF generation
2. `/download_pdf_bulk.php` - Multiple reports in one PDF

## Database Schema Used
- `reports` table: patient_id, category_id, performed_date, status
- `report_results` table: report_id, parameter_id, result_value
- Relationship: patient + category → multiple reports over time

## Testing
Database shows multiple reports available:
- Patient 1, Category 1: 2 reports
- Patient 1, Category 2: 2 reports  
- Patient 1, Category 17: 5 reports
- Patient 2, Category 4: 2 reports
- Patient 2, Category 17: 2 reports

All syntax validated successfully.
