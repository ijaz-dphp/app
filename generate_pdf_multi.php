<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    //header('Location: index.php');
    //exit;
}

require_once 'config/database.php';

$report_ids = explode(',', $_GET['ids'] ?? '');
if (empty($report_ids[0])) {
    die('No reports specified!');
}

$db = new Database();
$conn = $db->getConnection();

// Get all reports with details
$placeholders = implode(',', array_fill(0, count($report_ids), '?'));

// DEBUG
if (isset($_GET['debug'])) {
    echo "<!-- DEBUG: Placeholders: $placeholders -->\n";
    echo "<!-- DEBUG: Params to execute: " . print_r($report_ids, true) . " -->\n";
}

$stmt = $conn->prepare("
    SELECT r.*, p.*, tc.name as category_name, tc.code as category_code, tc.description as test_description,
           p.name as patient_name, p.mrn, p.contact, p.age, p.gender, p.father_husband_name,
           r.id as report_id
    FROM reports r 
    JOIN patients p ON r.patient_id = p.id 
    JOIN test_categories tc ON r.category_id = tc.id 
    WHERE r.id IN ($placeholders)
    ORDER BY r.id
");

// Convert report_ids to integers and re-index array
$params = array_values(array_map('intval', $report_ids));

if (isset($_GET['debug'])) {
    echo "<!-- DEBUG: Final params: " . print_r($params, true) . " -->\n";
}

$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DEBUG
if (isset($_GET['debug'])) {
    echo "<!-- DEBUG: Number of reports fetched: " . count($reports) . " -->\n";
    echo "<!-- DEBUG: Report IDs fetched: ";
    foreach ($reports as $r) {
        echo $r['id'] . " ";
    }
    echo " -->\n";
}

if (empty($reports)) {
    die('Reports not found!');
}

// Use first report for patient and general info
$mainReport = $reports[0];

// Get results for all reports
$allResults = [];
foreach ($report_ids as $rid) {
    $rid = intval($rid); // Convert to integer
    $stmt = $conn->prepare("
        SELECT rr.*, tp.test_name, tp.min_value, tp.max_value, tp.unit, tc.name as category_name 
        FROM report_results rr 
        JOIN test_parameters tp ON rr.parameter_id = tp.id 
        JOIN test_categories tc ON tp.category_id = tc.id
        WHERE rr.report_id = :id 
        ORDER BY tp.display_order
    ");
    $stmt->execute(['id' => $rid]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $allResults[$rid] = $results;
    
    // DEBUG - Remove after checking
    if (isset($_GET['debug'])) {
        echo "<!-- DEBUG: Report ID $rid has " . count($results) . " results -->\n";
        echo "<!-- DEBUG: Results: " . print_r($results, true) . " -->\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report - <?php echo htmlspecialchars($mainReport['patient_name']); ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 15px; }
            @page { margin: 15mm; }
            .page-break { page-break-after: always; }
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
            padding: 0;
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
            page-break-inside: avoid;
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
            margin-top: 8px;
            font-size: 11px;
        }
        
        .results-table th {
            background-color: #bfbfbf;
            padding: 8px;
            text-align: left;
            border: 1px solid #999;
            font-weight: bold;
            font-size: 11px;
            color: #000;
        }
        
        .results-table td {
            padding: 6px 8px;
            border: none;
        }
        
        .abnormal {
            background-color: #ffebee !important;
            color: #c62828 !important;
            font-weight: bold !important;
        }
        
        .arrow {
            font-size: 10px;
            margin-left: 3px;
            color: #c62828;
        }
        
        .footer {
            margin-top: 40px;
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
  
    
    <?php 
    // Set $report variable for the header include (use main report data)
    $report = $mainReport;
    include 'includes/report_header.php'; 
    ?>
    
    <?php foreach ($reports as $index => $report): 
        $rid = isset($report['report_id']) ? intval($report['report_id']) : intval($report['id']);
        $results = $allResults[$rid] ?? [];
    ?>
        <!-- Test Section -->
        <div class="test-section">
            <h2 class="test-title"><?php echo htmlspecialchars($report['category_name']); ?></h2>
            <div class="test-subtitle-row">
                <h3 class="test-subtitle"><?php echo htmlspecialchars($report['category_code']); ?> - <?php echo htmlspecialchars($report['test_description']); ?></h3>
                <div class="test-meta">
                    <strong>Performed at:</strong> <?php echo date('d/M/Y - h:i A', strtotime($report['performed_date'])); ?>&nbsp;&nbsp;&nbsp;
                    <strong>Published at:</strong> <?php echo date('d/M/Y - h:i A', strtotime($report['published_date'])); ?>
                </div>
            </div>
            
            <!-- Results Table -->
            <table class="results-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Test</th>
                        <th style="width: 25%;">Reference Ranges</th>
                        <th style="width: 15%;">Unit</th>
                        <th style="width: 20%;">Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (isset($_GET['debug']) && empty($results)) {
                        echo "<!-- DEBUG: No results in this loop! -->\n";
                    }
                    
                    foreach ($results as $result): 
                        $reference_range = $result['min_value'];
                        if ($result['max_value'] && strpos($result['max_value'], '<') === false) {
                            $reference_range .= ' - ' . $result['max_value'];
                        } elseif ($result['max_value']) {
                            $reference_range = $result['max_value'];
                        }
                        
                        $is_abnormal = $result['is_abnormal'];
                        $arrow = '';
                        if ($is_abnormal) {
                            $numeric_value = floatval($result['result_value']);
                            if ($result['min_value'] !== '' && $numeric_value < floatval($result['min_value'])) {
                                $arrow = ' <span class="arrow">↓</span>';
                            } elseif ($result['max_value'] !== '' && strpos($result['max_value'], '<') === false && $numeric_value > floatval($result['max_value'])) {
                                $arrow = ' <span class="arrow">↑</span>';
                            }
                        }
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($result['test_name']); ?></td>
                            <td><?php echo htmlspecialchars($reference_range); ?></td>
                            <td><?php echo htmlspecialchars($result['unit']); ?></td>
                            <td class="<?php echo $is_abnormal ? 'abnormal' : ''; ?>">
                                <?php echo htmlspecialchars($result['result_value']); ?><?php echo $arrow; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($index < count($reports) - 1): ?>
            <div style="border-bottom: 2px solid #ddd; margin: 30px 0;"></div>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <?php 
    // Restore $report variable for footer include
    $report = $mainReport;
    include 'includes/report_footer.php'; 
    ?>
</body>
</html>
