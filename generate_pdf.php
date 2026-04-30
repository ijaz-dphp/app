<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    //  header('Location: index.php');
    //  exit;
}

require_once 'config/database.php';

$report_id = $_GET['id'] ?? 0;
$action = $_GET['action'] ?? 'view'; // view or download

$db = new Database();
$conn = $db->getConnection();

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
    die('Report not found!');
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

// Get test results
$stmt = $conn->prepare("
    SELECT rr.*, tp.test_name, tp.min_value, tp.max_value, tp.unit 
    FROM report_results rr 
    JOIN test_parameters tp ON rr.parameter_id = tp.id 
    WHERE rr.report_id = :id 
    ORDER BY tp.display_order
");
$stmt->execute(['id' => $report_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate HTML for PDF (printable version)
if ($action == 'download') {
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: inline; filename="report_' . $report_id . '.html"');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report - <?php echo htmlspecialchars($report['patient_name']); ?></title>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                padding: 15px;
            }

            @page {
                margin: 15mm;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            max-width: 100%;
            margin: 0 auto;
            padding: 15px;
            background: #fff;
            font-size: 11px;
            line-height: 1.4;
        }

        .header-section {
            text-align: left;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .logo-img {
            height: 60px;
            vertical-align: middle;
        }

        .hospital-info {
            display: inline-block;
            vertical-align: middle;
            margin-left: 15px;
        }

        .hospital-name {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin: 0;
            line-height: 1.2;
        }

        .department-name {
            font-size: 11px;
            color: #000;
            font-weight: bold;
            margin: 5px 0 0 0;
            padding: 0;
            text-align: left;
            display: block;
        }

        .patient-info {
            margin: 0 0 8px 0;
            padding-bottom: 0;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .patient-table td {
            padding: 1px 0;
            font-size: 10px;
            line-height: 1.6;
        }

        .patient-label {
            font-weight: 600;
            color: #000;
            width: 95px;
        }

        .patient-value {
            color: #000;
            font-weight: bold;
        }

        .date-row {
            margin-top: 5px;
            font-size: 10px;
            line-height: 1.6;
        }

        .date-row .patient-label {
            font-weight: 600;
            color: #000;
        }

        .date-row .patient-value {
            color: #000;
            font-weight: bold;
        }

        .test-section {
            margin: 10px 0;
        }

        .test-title {
            font-size: 13px;
            font-weight: bold;
            color: #000;
            margin: 8px 0 5px 0;
            display: block;
        }

        .test-subtitle-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .test-subtitle {
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .test-meta {
            font-size: 10px;
            color: #000;
            text-align: right;
            line-height: 1.5;
            background-color: #e8eef5;
            padding: 4px 8px;
            border-radius: 3px;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin: 8px 0;
        }

        .results-table th {
            background: #bfbfbf;
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            color: #000;
            border: 1px solid #ccc;
            font-size: 10px;
        }

        .results-table td {
            padding: 5px 8px;
            border: none;
            font-size: 10px;
        }

        .results-table tr:hover {
            background: #f9f9f9;
        }

        .test-name-col {
            font-weight: 600;
            color: #000;
        }

        .abnormal {
            color: #dc3545;
            font-weight: bold;
        }

        .footer-section {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
        }

        .footer-note {
            font-size: 9px;
            color: #666;
            font-style: italic;
            margin: 4px 0;
        }

        .verified-by {
            font-size: 10px;
            margin: 8px 0 0 0;
            font-weight: 600;
        }

        .print-btn,
        .back-btn {
            position: fixed;
            top: 20px;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 1000;
        }

        .print-btn {
            right: 20px;
            background: #2c5aa0;
            color: white;
        }

        .print-btn:hover {
            background: #234a85;
        }

        .back-btn {
            right: 180px;
            background: #28a745;
            color: white;
        }

        .back-btn:hover {
            background: #218838;
        }

        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: #2c5aa0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background-color: #1e3a6f;
        }

        .download-button {
            position: fixed;
            top: 10px;
            right: 150px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .download-button:hover {
            background-color: #218838;
        }

        @media print {
            .print-button, .download-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="download-button no-print" onclick="window.location.href='download_pdf.php?id=<?php echo $report_id; ?>'">📥 Download PDF</button>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print Report</button>

    <?php include 'includes/report_header.php'; ?>

    <!-- Test Section -->
    <div class="test-section">
        <h2 class="test-title"><?php echo htmlspecialchars($report['category_name']); ?></h2>
        <div class="test-subtitle-row">
            <h3 class="test-subtitle"><?php echo htmlspecialchars($report['test_description']); ?></h3>
            <div class="test-meta">
                <strong>Performed at:</strong> <?php echo date('d/M/Y - h:i A', strtotime($report['performed_date'])); ?>&nbsp;&nbsp;&nbsp;
                <strong>Published at:</strong> <?php echo date('d/M/Y - h:i A', strtotime($report['published_date'])); ?>
            </div>
        </div>

        <!-- Results Table -->
        <table class="results-table">
            <thead>
                <tr>
                    <th style="width: 30%;">Test</th>
                    <th style="width: 25%;">Reference Ranges</th>
                    <th style="width: 15%;">Unit</th>
                    <th style="width: 30%;">Result</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <?php
                    $range = '';
                    if ($result['min_value'] !== '') $range .= $result['min_value'];
                    if ($result['min_value'] !== '' && $result['max_value'] !== '' && strpos($result['max_value'], '<') === false && strpos($result['max_value'], '>') === false) $range .= '-';
                    if ($result['max_value'] !== '') $range .= $result['max_value'];
                    if ($range === '') $range = '';

                    $result_class = $result['is_abnormal'] ? 'abnormal' : '';
                    $arrow = '';
                    if ($result['is_abnormal'] && $result['result_value'] !== '') {
                        if ($result['min_value'] !== '' && is_numeric($result['result_value']) && floatval($result['result_value']) < floatval($result['min_value'])) {
                            $arrow = ' ↓';
                        } elseif ($result['max_value'] !== '' && strpos($result['max_value'], '<') === false && strpos($result['max_value'], '>') === false && is_numeric($result['result_value']) && is_numeric($result['max_value']) && floatval($result['result_value']) > floatval($result['max_value'])) {
                            $arrow = ' ↑';
                        }
                    }
                    ?>
                    <tr>
                        <td class="test-name-col"><?php echo htmlspecialchars($result['test_name']); ?></td>
                        <td><?php echo htmlspecialchars($range); ?></td>
                        <td><?php echo htmlspecialchars($result['unit']); ?></td>
                        <td class="<?php echo $result_class; ?>"><strong><?php echo htmlspecialchars($result['result_value']); ?><?php echo $arrow; ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include 'includes/report_footer.php'; ?>
</body>

</html>

<?php
function generateReportHTML($report, $results)
{
    $html = '
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .header-table td {
            padding: 5px;
            font-size: 11px;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 10px;
            margin: 10px 0;
        }
        .test-table {
            margin-top: 15px;
        }
        .test-table th {
            background-color: #e9ecef;
            padding: 8px;
            border: 1px solid #dee2e6;
            font-weight: bold;
            font-size: 10px;
        }
        .test-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            font-size: 10px;
        }
        .abnormal {
            background-color: #ffebee !important;
            color: #c62828 !important;
            font-weight: bold !important;
        }
        h2 {
            color: #2c3e50;
            font-size: 14px;
            margin: 15px 0 10px 0;
        }
        h3 {
            color: #34495e;
            font-size: 12px;
            margin: 10px 0 5px 0;
        }
    </style>
    
    <!-- Hospital Header -->
    <table class="header-table" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 70%; font-size: 18px; font-weight: bold; color: #2c3e50;">
                Bahawal Victoria Hospital, Bahawalpur
            </td>
            <td style="width: 30%; text-align: right; font-size: 14px; font-weight: bold;">
                BVH
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 12px; color: #555;">
                Department of Pathology
            </td>
        </tr>
    </table>
    
    <hr style="border: 1px solid #ddd; margin: 10px 0;">
    
    <!-- Patient Information -->
    <table class="header-table" style="margin: 15px 0;">
        <tr>
            <td style="width: 50%;"><strong>MRN:</strong> ' . htmlspecialchars($report['mrn']) . '</td>
            <td style="width: 50%;"><strong>Department:</strong> ' . htmlspecialchars($report['department']) . '</td>
        </tr>
        <tr>
            <td><strong>Name:</strong> ' . htmlspecialchars($report['patient_name']) . '</td>
            <td><strong>Ward:</strong> ' . htmlspecialchars($report['ward']) . '</td>
        </tr>
        <tr>
            <td><strong>Contact No:</strong> ' . htmlspecialchars($report['contact']) . '</td>
            <td><strong>F/H\'s Name:</strong> ' . htmlspecialchars($report['father_husband_name']) . '</td>
        </tr>
        <tr>
            <td><strong>Age/Gender:</strong> ' . htmlspecialchars($report['age']) . ' / ' . htmlspecialchars($report['gender']) . '</td>
            <td></td>
        </tr>
    </table>
    
    <table class="header-table" style="margin: 10px 0;">
        <tr>
            <td><strong>Request Date:</strong> ' . date('d/M/Y - h:i A', strtotime($report['request_date'])) . '</td>
            <td><strong>Printed Date:</strong> ' . date('d/M/Y - h:i A', strtotime($report['printed_date'])) . '</td>
        </tr>
    </table>
    
    <hr style="border: 1px solid #ddd; margin: 15px 0;">
    
    <!-- Test Name -->
    <h2>' . htmlspecialchars($report['category_name']) . '</h2>
    <h3>' . htmlspecialchars($report['test_description']) . '</h3>
    <p style="font-size: 9px; color: #666;">
        Published at: ' . date('d/M/Y - h:i A', strtotime($report['published_date'])) . ' 
        | Performed at: ' . date('d/M/Y - h:i A', strtotime($report['performed_date'])) . '
    </p>
    
    <!-- Test Results Table -->
    <table class="test-table">
        <thead>
            <tr>
                <th style="width: 30%;">Test</th>
                <th style="width: 30%;">Reference Ranges</th>
                <th style="width: 15%;">Unit</th>
                <th style="width: 25%;">Result</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($results as $result) {
        $range = '';
        if ($result['min_value'] !== '') $range .= $result['min_value'];
        if ($result['min_value'] !== '' && $result['max_value'] !== '') $range .= ' - ';
        if ($result['max_value'] !== '') $range .= $result['max_value'];
        if ($range === '') $range = '-';

        $result_class = $result['is_abnormal'] ? 'abnormal' : '';
        $arrow = '';
        if ($result['is_abnormal']) {
            if ($result['min_value'] !== '' && floatval($result['result_value']) < floatval($result['min_value'])) {
                $arrow = ' ↓';
            } else {
                $arrow = ' ↑';
            }
        }

        $html .= '
            <tr>
                <td><strong>' . htmlspecialchars($result['test_name']) . '</strong></td>
                <td>' . htmlspecialchars($range) . '</td>
                <td>' . htmlspecialchars($result['unit']) . '</td>
                <td class="' . $result_class . '">' . htmlspecialchars($result['result_value']) . $arrow . '</td>
            </tr>';
    }

    $html .= '
        </tbody>
    </table>
    
    <!-- Footer -->
    <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd;">
        <p style="font-size: 9px; color: #666; font-style: italic;">
            *Electronically verified report, signatures are not required
        </p>
        <p style="font-size: 10px; margin-top: 10px;">
            <strong>Verified By:</strong> ' . htmlspecialchars($report['verified_by']) . '
        </p>
    </div>
    ';

    return $html;
}
?>