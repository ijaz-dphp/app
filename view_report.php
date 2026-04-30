<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

$report_id = $_GET['id'] ?? 0;

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

// Get test results - show all test parameters for this category
$stmt = $conn->prepare("
    SELECT tp.id as parameter_id, tp.test_name, tp.min_value, tp.max_value, tp.unit, tp.display_order,
           COALESCE(rr.id, NULL) as result_id,
           COALESCE(rr.result_value, '') as result_value,
           COALESCE(rr.is_abnormal, 0) as is_abnormal
    FROM test_parameters tp
    LEFT JOIN report_results rr ON rr.parameter_id = tp.id AND rr.report_id = :id
    WHERE tp.category_id = :category_id
    ORDER BY tp.display_order
");
$stmt->execute(['id' => $report_id, 'category_id' => $report['category_id']]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report - <?php echo htmlspecialchars($report['patient_name']); ?></title>
    <link rel="stylesheet" href="includes/report_styles.css">
    <style>
        .test-section {
            margin: 15px 0;
        }
        .test-title {
            font-size: 13px;
            font-weight: bold;
            color: #000;
            margin: 12px 0 4px 0;
        }
        .test-subtitle {
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin: 0 0 3px 0;
        }
        .test-meta {
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
        }
        .download-btn {
            position: fixed;
            top: 20px;
            right: 200px;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            background: #28a745;
            color: white;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1000;
        }
        .download-btn:hover {
            background: #218838;
        }
        .edit-btn {
            position: fixed;
            top: 20px;
            right: 100px;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            background: #ffc107;
            color: #333;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1000;
        }
        .edit-btn:hover {
            background: #e0a800;
        }
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            background: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1000;
        }
        .back-btn:hover {
            background: #5a6268;
        }
        .alert {
            padding: 15px;
            margin: 20px;
            border-radius: 5px;
            font-size: 13px;
            display: none;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
    </style>
</head>
<body>
    <a href="reports_list.php" class="back-btn">← Back to Reports</a>
    <a href="edit_report.php?id=<?php echo $report_id; ?>" class="edit-btn">✏️ Edit</a>
    <a href="download_pdf.php?id=<?php echo $report_id; ?>" class="download-btn">⬇️ Download PDF</a>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            ✓ <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            ✗ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
   
    <?php include 'includes/report_header.php'; ?>
    
    <div class="test-section">
        <h2 class="test-title"><?php echo htmlspecialchars($report['category_name']); ?></h2>
        <h3 class="test-subtitle"><?php echo htmlspecialchars($report['test_description']); ?></h3>
        <div class="test-meta">
            Published: <?php echo date('d/M/Y - h:i A', strtotime($report['published_date'])); ?> 
            | Performed: <?php echo date('d/M/Y - h:i A', strtotime($report['performed_date'])); ?>
        </div>
        
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
