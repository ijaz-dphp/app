# HCV Quantitative PCR Report Implementation

## Report Format (Based on Sample)

### Header Information
- Same as standard reports (Hospital name, patient info, etc.)

### Test Results Table

The HCV Quantitative PCR report has a special format with merged cells:

| Test | Reference Ranges | Unit | Result |
|------|------------------|------|--------|
| *Merged cell showing:* | | | BVH<br>26-Aug-25<br>01:36 PM |
| HCV Quantitative by PCR | | | **Detected** |
| Result Value | < 13 | IU/ml | **3.8x10^4** |

### Comments Section
**Comments:** The test is performed on semi automated GENTIER 96 PCR analyzer.

---

## Current Implementation Status

### ✅ What's Already Working:
1. Basic PCR tests (Hepatitis C PCR) with Detected/Not Detected dropdown
2. Patient information section
3. Header formatting
4. PDF generation

### ❌ What Needs to Be Added:

1. **Quantitative Result Field**
   - Currently only has qualitative (Detected/Not Detected)
   - Need to add numeric result field for viral load

2. **Special Table Format**
   - Multi-row merged cells for institution info
   - Separate rows for qualitative and quantitative results

3. **Comments Field**
   - Already added to database (comments column in reports table)
   - Need to add to forms and display in PDF

---

## Implementation Options

### Option 1: Create New Test Category "Quantitative PCR"
- Add new category with parameters:
  - HCV Quantitative (Qualitative) - Detected/Not Detected
  - HCV Quantitative (Viral Load) - Numeric with unit IU/ml
  - Reference: < 13 IU/ml

### Option 2: Extend Existing Test Parameters
- Add a second parameter for each quantitative test
- Link them as a group

### Option 3: Add Custom Fields to Test Parameters
- Add `has_quantitative_result` flag
- Add `quantitative_unit` field
- Add `quantitative_reference` field

---

## Recommended Solution

**Use Option 1** - Create specific Quantitative PCR tests:

1. Update `test_categories` - Add "Quantitative PCR" category
2. Add test parameters:
   - HCV Quantitative PCR (Qualitative)
   - HCV Quantitative PCR (Viral Load)
3. Update forms to handle both results
4. Create custom PDF template for quantitative PCR format
5. Add comments field to report creation forms

---

## Database Changes Completed

✅ Added `comments` field to `reports` table

## Still Need To Do

1. Create Quantitative PCR category and parameters
2. Update `new_report.php` to include comments textarea
3. Update `new_report_multi.php` to include comments textarea
4. Update `generate_pdf.php` to display comments
5. Update `generate_pdf_multi.php` to display comments
6. Create special formatting for quantitative PCR results

---

## Sample SQL for Creating Quantitative Tests

```sql
-- Add Quantitative PCR category
INSERT INTO test_categories (name, code, description) 
VALUES ('Quantitative PCR', 'QPCR', 'Quantitative PCR Tests');

-- Add HCV Quantitative parameters (assuming category_id = 17)
INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
VALUES 
(17, 'HCV Quantitative by PCR', 'Not Detected', 'Not Detected', '', 1),
(17, 'Result Value', '', '< 13', 'IU/ml', 2);
```

---

**Date:** February 12, 2026
**Status:** Database updated, implementation pending
