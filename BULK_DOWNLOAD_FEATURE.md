# Bulk Download Feature - Reports List

## Overview
Added bulk download functionality to `reports_list.php` that groups reports by patient and parent category (e.g., "Routine Chemistry", "Haematology"), allowing users to download all related reports in one PDF.

## Features Implemented

### 1. Parent Category Grouping
- Reports are automatically grouped by:
  - **Patient ID** (same patient)
  - **Parent Category Name** (e.g., Routine Chemistry, Haematology, Serology)
- Only completed reports are included in bulk groups

### 2. Visual Indicators
- **New Column**: "Parent Category" shows the category name
- **Report Count**: Shows "📦 X reports" when multiple reports exist for same category
- **Bulk Button**: Purple "📦 Bulk (X)" button appears when 2+ reports are available

### 3. Bulk Download Button
- **Color**: Purple (#6f42c1) to distinguish from regular download
- **Icon**: 📦 with count (e.g., "📦 Bulk (3)")
- **Tooltip**: Shows category and count on hover
- **Link**: `download_pdf_bulk.php?ids=1,2,3` with comma-separated report IDs

## How It Works

### Example Scenario:
**Patient**: John Doe (MRN: 12345)
**Parent Category**: Routine Chemistry

Reports:
- Report #1: LFT (Liver Function Test)
- Report #2: LIPID (Lipid Profile)
- Report #3: CRP (C-Reactive Protein)

**Result**: All 3 reports will show a "📦 Bulk (3)" button that downloads them together in one PDF.

## Database Changes
**Query Updated** to include:
```sql
tc.name as parent_category_name
```

## UI Changes

### New Column in Table:
```
| Parent Category       |
|-----------------------|
| Routine Chemistry     |
| 📦 3 reports          |
```

### Action Buttons:
```
[👁️ View] [⬇️ Download] [📦 Bulk (3)]
```

### Grouping Logic:
```php
$bulkKey = patient_id . '_' . parent_category_name;
// Example: "1_Routine Chemistry"
```

## Files Modified
1. `reports_list.php`:
   - Added parent_category_name to query
   - Created $bulkGroups array for grouping
   - Added "Parent Category" column
   - Added bulk download button with conditional display
   - Added purple button styling (.btn-bulk)

## Testing
- Patient 1 with multiple Routine Chemistry tests will show bulk option
- Patient 2 with single test will NOT show bulk option
- Bulk button only shows for completed reports
- Each parent category gets its own bulk group

## User Experience
✅ Easy to identify multiple reports for same category
✅ One-click download for all related tests
✅ Visual count indicator shows how many reports included
✅ Color-coded button (purple) distinguishes from single download
✅ Works seamlessly with existing download_pdf_bulk.php

## Example Use Cases
1. **Routine Chemistry**: LFT + LIPID + CRP → Download all 3 together
2. **Haematology**: CBC + ESR → Download both together
3. **Serology**: Multiple immunology tests → Download as one PDF
