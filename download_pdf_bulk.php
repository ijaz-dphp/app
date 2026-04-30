<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    //die('Unauthorized access');
}

require_once 'vendor/autoload.php';
require_once 'config/database.php';

// Get report IDs (comma-separated)
$report_ids = $_GET['ids'] ?? '';

if (empty($report_ids)) {
    die('No reports specified!');
}

$ids_array = explode(',', $report_ids);
$ids_array = array_map('intval', $ids_array);

if (empty($ids_array)) {
    die('Invalid report IDs!');
}

$db = new Database();
$conn = $db->getConnection();

// Create PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Bahawal Victoria Hospital');
$pdf->SetAuthor('Department of Pathology');
$pdf->SetTitle('Medical Reports - Bulk Download');
$pdf->SetSubject('Medical Laboratory Reports');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(15, 10, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

$firstReport = true;
$patientName = '';
$patientInfo = null;

// Loop through each report ID
foreach ($ids_array as $report_id) {
    // Get report details
    $stmt = $conn->prepare("
        SELECT r.*, p.*, tc.name as category_name, tc.code as category_code, tc.description as test_description,
               p.name as patient_name
        FROM reports r 
        JOIN patients p ON r.patient_id = p.id 
        JOIN test_categories tc ON r.category_id = tc.id 
        WHERE r.id = :id
    ");
    $stmt->execute(['id' => $report_id]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        continue; // Skip if report not found
    }

    // Generate or get printed date (between 1:20 PM - 2:30 PM)
    if (empty($report['printed_date'])) {
        // Generate random printed time between 1:20 PM (13:20) and 2:30 PM (14:30)
        $today = date('Y-m-d');
        $min_time = strtotime($today . ' 13:20');
        $max_time = strtotime($today . ' 14:30');
        $random_time = rand($min_time, $max_time);
        $printed_datetime = date('Y-m-d H:i:s', $random_time);
        
        // Save the generated printed date to database
        $update_stmt = $conn->prepare("UPDATE reports SET printed_date = :printed_date WHERE id = :id");
        $update_stmt->execute([':printed_date' => $printed_datetime, ':id' => $report_id]);
        
        // Update report array with new printed_date
        $report['printed_date'] = $printed_datetime;
    }

    // Store patient info from first report for header
    if ($firstReport) {
        $patientName = $report['patient_name'];
        $patientInfo = $report;
        
        // Add the first page with header
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 9);

        // Logo and Hospital Header - top left
        $pdf->Image('assets/images/punjab-logo.png', 15, 10, 15, 15, 'PNG');
        $pdf->SetXY(32, 12);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 5, 'Bahawal Victoria Hospital, Bahawalpur', 0, 1, 'L');

        // Department of Pathology - left aligned, below logo
        $pdf->SetY(26);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(0, 4, 'Department of Pathology', 0, 1, 'C');

        // Horizontal line
        $pdf->SetLineWidth(0.2);
        $pdf->Line(15, $pdf->GetY() + 1, 195, $pdf->GetY() + 1);

        // Patient Information Table
        $pdf->SetY($pdf->GetY() + 3);
        $pdf->SetFont('helvetica', '', 8);

        // Row 1 - Labels normal, values bold
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(25, 4, 'MRN', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(55, 4, $report['mrn'], 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(30, 4, 'Department:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(0, 4, $report['department'], 0, 1, 'L');

        // Row 2
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(25, 4, 'Name:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(55, 4, strtoupper($report['patient_name']), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(30, 4, 'F/H\'s Name:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(0, 4, $report['father_husband_name'] ?? '', 0, 1, 'L');

        // Row 3
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(25, 4, 'Contact No :', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(55, 4, $report['contact'], 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(30, 4, 'Request Date:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(0, 4, date('d/M/Y - h:i A', strtotime($report['request_date'])), 0, 1, 'L');

        // Row 4
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(25, 4, 'Age/ Gender:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(55, 4, $report['age'] . ' / ' . $report['gender'], 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(30, 4, 'Printed Date:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(0, 4, date('d/M/Y - h:i A', strtotime($report['printed_date'])), 0, 1, 'L');

        // Horizontal line after patient info
        $pdf->Ln(1);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(3);
        
        $firstReport = false;
    } else {
        // Add spacing between tests (not a new page)
        $pdf->Ln(5);
    }

    // Get test results
    $stmt = $conn->prepare("
        SELECT rr.*, tp.test_name, tp.min_value, tp.max_value, tp.unit, tp.id as parameter_id
        FROM report_results rr 
        JOIN test_parameters tp ON rr.parameter_id = tp.id 
        WHERE rr.report_id = :id 
        ORDER BY tp.display_order
    ");
    $stmt->execute(['id' => $report_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get last 3 previous reports for this patient and category
    $stmt = $conn->prepare("
        SELECT id, performed_date 
        FROM reports 
        WHERE patient_id = :patient_id 
        AND category_id = :category_id 
        AND id < :current_report_id
        AND status = 'completed'
        ORDER BY performed_date DESC 
        LIMIT 3
    ");
    $stmt->execute([
        ':patient_id' => $report['patient_id'],
        ':category_id' => $report['category_id'],
        ':current_report_id' => $report_id
    ]);
    $previousReports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch results for each previous report
    $previousResults = [];
    foreach ($previousReports as $prevReport) {
        $stmt = $conn->prepare("
            SELECT rr.parameter_id, rr.result_value, r.performed_date
            FROM report_results rr
            JOIN reports r ON rr.report_id = r.id
            WHERE rr.report_id = :report_id
        ");
        $stmt->execute([':report_id' => $prevReport['id']]);
        $prevData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($prevData as $data) {
            $previousResults[$data['parameter_id']][] = [
                'result_value' => $data['result_value'],
                'performed_date' => $data['performed_date']
            ];
        }
    }

    // Test Category Title
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 5, $report['category_name'], 0, 1, 'L');

    $pdf->Ln(0.5);

    // Test Description and Dates on same line
    $pdf->SetFont('helvetica', 'B', 9);
    $testDescWidth = $pdf->GetStringWidth($report['test_description']) + 3;
    $pdf->Cell($testDescWidth, 4, $report['test_description'], 0, 0, 'L');

    // Dates on same row, aligned to the right
    $pdf->SetFont('helvetica', '', 7);
    $performedText = 'Performed at: ' . date('d/M/Y - h:i A', strtotime($report['performed_date']));
    $publishedText = 'Published at: ' . date('d/M/Y - h:i A', strtotime($report['published_date']));
    $datesText = $performedText . '  ' . $publishedText;
    $pdf->Cell(0, 4, $datesText, 0, 1, 'R');

    $pdf->Ln(2);

    // Calculate column widths based on number of previous reports
    $numPrevReports = count($previousReports);
    $testCol = 55;
    $refCol = 45;
    $unitCol = 25;

    if ($numPrevReports > 0) {
        $currentResultCol = 18; // Width for current result
        $prevResultCol = 37; // Width for all previous results
    } else {
        $currentResultCol = 55; // Full width when no previous results
        $prevResultCol = 0;
    }

    // Results Table Header - Top and sides only, no bottom border
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->SetLineWidth(0.2);
    $pdf->Cell($testCol, 6, 'Test', 'LTR', 0, 'L', true);
    $pdf->Cell($refCol, 6, 'Reference Ranges', 'LTR', 0, 'L', true);
    $pdf->Cell($unitCol, 6, 'Unit', 'LTR', 0, 'L', true);
    $pdf->Cell($currentResultCol, 6, 'Result', 'LTR', 0, 'C', true);
    if ($numPrevReports > 0) {
        $pdf->Cell($prevResultCol, 6, 'Last Results', 'LTR', 1, 'C', true);
    } else {
        $pdf->Ln();
    }

    // Add BVH, Date, and Time rows with same background as header
    $pdf->SetFillColor(200, 200, 200); // Same gray as header

    // BVH row - Left and Right borders only
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell($testCol, 4, '', 'LR', 0, 'L', true);
    $pdf->Cell($refCol, 4, '', 'LR', 0, 'L', true);
    $pdf->Cell($unitCol, 4, '', 'LR', 0, 'L', true);
    $pdf->Cell($currentResultCol, 4, 'BVH', 'LR', 0, 'C', true);

    if ($numPrevReports > 0) {
        // Show BVH for each previous report in separate columns
        $prevColWidth = $prevResultCol / $numPrevReports;
        foreach ($previousReports as $idx => $prevReport) {
            $isLast = ($idx == $numPrevReports - 1);
            $pdf->Cell($prevColWidth, 4, 'BVH', $isLast ? 'LR' : 'L', 0, 'C', true);
        }
    }
    $pdf->Ln();

    // Date row - Left and Right borders only
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell($testCol, 3, '', 'LR', 0, 'L', true);
    $pdf->Cell($refCol, 3, '', 'LR', 0, 'L', true);
    $pdf->Cell($unitCol, 3, '', 'LR', 0, 'L', true);
    $pdf->Cell($currentResultCol, 3, date('d-M-y', strtotime($report['performed_date'])), 'LR', 0, 'C', true);

    if ($numPrevReports > 0) {
        $prevColWidth = $prevResultCol / $numPrevReports;
        foreach ($previousReports as $idx => $prevReport) {
            $isLast = ($idx == $numPrevReports - 1);
            $pdf->Cell($prevColWidth, 3, date('d-M-y', strtotime($prevReport['performed_date'])), $isLast ? 'LR' : 'L', 0, 'C', true);
        }
    }
    $pdf->Ln();

    // Time row with bottom border - Left, Right, and Bottom borders
    $pdf->Cell($testCol, 3, '', 'LRB', 0, 'L', true);
    $pdf->Cell($refCol, 3, '', 'LRB', 0, 'L', true);
    $pdf->Cell($unitCol, 3, '', 'LRB', 0, 'L', true);
    $pdf->Cell($currentResultCol, 3, date('h:i A', strtotime($report['performed_date'])), 'LRB', 0, 'C', true);

    if ($numPrevReports > 0) {
        $prevColWidth = $prevResultCol / $numPrevReports;
        foreach ($previousReports as $idx => $prevReport) {
            $isLast = ($idx == $numPrevReports - 1);
            $pdf->Cell($prevColWidth, 3, date('h:i A', strtotime($prevReport['performed_date'])), $isLast ? 'LRB' : 'LB', 0, 'C', true);
        }
    }
    $pdf->Ln();

    // Results Table Data
    $pdf->SetFont('helvetica', '', 9);
    foreach ($results as $result) {
        $range = '';
        if ($result['min_value'] !== '') $range .= $result['min_value'];
        if ($result['min_value'] !== '' && $result['max_value'] !== '' && strpos($result['max_value'], '<') === false && strpos($result['max_value'], '>') === false) $range .= '-';
        if ($result['max_value'] !== '') $range .= $result['max_value'];

        $isAbnormal = $result['is_abnormal'];
        
        // Skip empty placeholder rows
        if (empty($result['test_name'])) {
            continue;
        }
        
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($testCol, 5, $result['test_name'], 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($refCol, 5, $range, 0, 0, 'L');
        $pdf->Cell($unitCol, 5, $result['unit'], 0, 0, 'L');
        
        // Current result
        if ($isAbnormal) {
            $pdf->SetTextColor(220, 53, 69);
            $pdf->SetFont('helvetica', 'B', 9);
        }
        $pdf->Cell($currentResultCol, 5, $result['result_value'], 0, 0, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        
        // Previous results in separate columns
        if ($numPrevReports > 0) {
            $prevColWidth = $prevResultCol / $numPrevReports;
            $prevValues = isset($previousResults[$result['parameter_id']]) ? $previousResults[$result['parameter_id']] : [];
            
            for ($i = 0; $i < $numPrevReports; $i++) {
                $value = isset($prevValues[$i]) ? $prevValues[$i]['result_value'] : '';
                $pdf->Cell($prevColWidth, 5, $value, 0, 0, 'C');
            }
        }
        
        $pdf->Ln();
    }

    // Comments Section - Only show if comments exist
    if (!empty($report['comments'])) {
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(20, 4, 'Comments:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(0, 4, $report['comments'], 0, 'L');
    }
}

// Add footer only once at the end of all reports
$currentY = $pdf->GetY();
$pageHeight = $pdf->getPageHeight();
$bottomMargin = 15;
$footerHeight = 20;

// Move to bottom for footer
if (($pageHeight - $currentY - $bottomMargin) > $footerHeight) {
    $pdf->SetY($pageHeight - $bottomMargin - $footerHeight);
} else {
    $pdf->Ln(3);
}

$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(102, 102, 102);
$pdf->Cell(0, 4, '*Electronically verified report, signatures are not required', 0, 1, 'L');

// Verification line and text
$pdf->Ln(2);
$pdf->SetLineWidth(0.3);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(2);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, 'Verified By', 0, 1, 'R');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(0, 4, $patientInfo['verified_by'] ?? 'Dr.Farheen Aslam - N/A', 0, 1, 'R');

// Set filename
$filename = 'Medical_Reports_' . preg_replace('/[^A-Za-z0-9_]/', '_', $patientName) . '_' . date('Y-m-d') . '.pdf';

// Output PDF as download
$pdf->Output($filename, 'D');
?>
