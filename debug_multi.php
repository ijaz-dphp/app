<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
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
$stmt = $conn->prepare("
    SELECT r.*, p.*, tc.name as category_name, tc.code as category_code, tc.description as test_description,
           p.name as patient_name, p.mrn, p.contact, p.age, p.gender, p.father_husband_name
    FROM reports r 
    JOIN patients p ON r.patient_id = p.id 
    JOIN test_categories tc ON r.category_id = tc.id 
    WHERE r.id IN ($placeholders)
    ORDER BY tc.id
");
$stmt->execute($report_ids);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Debug Multi Report</h1>";
echo "<h2>Report IDs: " . implode(', ', $report_ids) . "</h2>";
echo "<h3>Number of reports found: " . count($reports) . "</h3>";

if (empty($reports)) {
    die('Reports not found!');
}

// Use first report for patient and general info
$mainReport = $reports[0];

// Get results for all reports
$allResults = [];
foreach ($report_ids as $rid) {
    echo "<hr><h3>Fetching results for Report ID: $rid</h3>";
    
    $stmt = $conn->prepare("
        SELECT rr.*, tp.test_name, tp.min_value, tp.max_value, tp.unit, tc.name as category_name 
        FROM report_results rr 
        JOIN test_parameters tp ON rr.parameter_id = tp.id 
        JOIN test_categories tc ON tp.category_id = tc.id
        WHERE rr.report_id = :id 
        ORDER BY tp.display_order
    ");
    $stmt->execute(['id' => $rid]);
    $allResults[$rid] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Number of results: " . count($allResults[$rid]) . "</p>";
    
    if (!empty($allResults[$rid])) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Test</th><th>Min</th><th>Max</th><th>Unit</th><th>Result</th><th>Abnormal</th></tr>";
        foreach ($allResults[$rid] as $r) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($r['test_name']) . "</td>";
            echo "<td>" . htmlspecialchars($r['min_value']) . "</td>";
            echo "<td>" . htmlspecialchars($r['max_value']) . "</td>";
            echo "<td>" . htmlspecialchars($r['unit']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($r['result_value']) . "</strong></td>";
            echo "<td>" . ($r['is_abnormal'] ? 'YES' : 'NO') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red;'>NO RESULTS FOUND!</p>";
    }
}

echo "<hr><h2>Now showing how it appears in the report</h2>";

foreach ($reports as $index => $report) {
    $results = $allResults[$report['id']] ?? [];
    
    echo "<h3>" . htmlspecialchars($report['category_name']) . " - Report ID: " . $report['id'] . "</h3>";
    echo "<p>Results count for this report: " . count($results) . "</p>";
    
    if (empty($results)) {
        echo "<p style='color:red;'>RESULTS ARRAY IS EMPTY!</p>";
    }
}
?>
