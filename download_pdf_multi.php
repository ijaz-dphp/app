<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die('Unauthorized access');
}

require_once 'vendor/autoload.php';
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
           p.name as patient_name, p.mrn, p.contact, p.age, p.gender, p.father_husband_name,
           r.id as report_id
    FROM reports r 
    JOIN patients p ON r.patient_id = p.id 
    JOIN test_categories tc ON r.category_id = tc.id 
    WHERE r.id IN ($placeholders)
    ORDER BY r.id
");

$params = array_values(array_map('intval', $report_ids));
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($reports)) {
    die('Reports not found!');
}

$mainReport = $reports[0];

// Generate or get printed dates for all reports (between 1:20 PM - 2:30 PM)
foreach ($report_ids as $rid) {
    $rid = intval($rid);
    $stmt = $conn->prepare("SELECT printed_date FROM reports WHERE id = :id");
    $stmt->execute(['id' => $rid]);
    $report_check = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($report_check && empty($report_check['printed_date'])) {
        // Generate random printed time between 1:20 PM (13:20) and 2:30 PM (14:30)
        $today = date('Y-m-d');
        $min_time = strtotime($today . ' 13:20');
        $max_time = strtotime($today . ' 14:30');
        $random_time = rand($min_time, $max_time);
        $printed_datetime = date('Y-m-d H:i:s', $random_time);
        
        // Save the generated printed date to database
        $update_stmt = $conn->prepare("UPDATE reports SET printed_date = :printed_date WHERE id = :id");
        $update_stmt->execute([':printed_date' => $printed_datetime, ':id' => $rid]);
    }
}

// Refresh mainReport to get updated printed_date
if (empty($mainReport['printed_date'])) {
    $stmt = $conn->prepare("SELECT printed_date FROM reports WHERE id = :id");
    $stmt->execute(['id' => $mainReport['id']]);
    $printed_info = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($printed_info) {
        $mainReport['printed_date'] = $printed_info['printed_date'];
    }
}

// Get results for all reports
$allResults = [];
foreach ($report_ids as $rid) {
    $rid = intval($rid);
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
}

// Create PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator('Bahawal Victoria Hospital');
$pdf->SetAuthor('Department of Pathology');
$pdf->SetTitle('Medical Report - ' . $mainReport['patient_name']);
$pdf->SetSubject('Medical Laboratory Report');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

// Build HTML content with inline styles for TCPDF
ob_start();
$report = $mainReport;
?>
<table style="width: 100%; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #000;">
    <tr>
        <td style="width: 80px; vertical-align: middle;">
            <img src="assets/images/punjab-logo.png" style="height: 60px;">
        </td>
        <td style="vertical-align: middle;">
            <h1 style="font-size: 18px; font-weight: bold; color: #000; margin: 0; padding: 0;">Bahawal Victoria Hospital, Bahawalpur</h1>
            <p style="font-size: 11px; color: #000; font-weight: bold; margin: 5px 0 0 0; text-align: center;">Department of Pathology</p>
        </td>
    </tr>
</table>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 5px; font-size: 10px;">
    <tr>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">MRN</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo htmlspecialchars($report['mrn']); ?></td>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">Department:</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo htmlspecialchars($report['department']); ?></td>
    </tr>
    <tr>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">Name:</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo strtoupper(htmlspecialchars($report['patient_name'])); ?></td>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">F/H's Name:</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo htmlspecialchars($report['father_husband_name'] ?? ''); ?></td>
    </tr>
    <tr>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">Contact No :</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo htmlspecialchars($report['contact']); ?></td>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">Request Date:</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo date('d/M/Y - h:i A', strtotime($report['request_date'])); ?></td>
    </tr>
    <tr>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">Age/ Gender:</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo htmlspecialchars($report['age']); ?> / <?php echo htmlspecialchars($report['gender']); ?></td>
        <td style="width: 95px; font-weight: 600; padding: 1px 0;">Printed Date:</td>
        <td style="font-weight: bold; padding: 1px 0;"><?php echo date('d/M/Y - h:i A', strtotime($mainReport['printed_date'])); ?></td>
    </tr>
</table>

<hr style="border: 0; border-top: 1px solid #000; margin: 10px 0 12px 0;">

<?php foreach ($reports as $index => $report): 
    $rid = isset($report['report_id']) ? intval($report['report_id']) : intval($report['id']);
    $results = $allResults[$rid] ?? [];
?>
    <h2 style="font-size: 13px; font-weight: bold; color: #000; margin: 8px 0 5px 0;"><?php echo htmlspecialchars($report['category_name']); ?></h2>

    <table style="width: 100%; margin-bottom: 8px;">
        <tr>
            <td style="font-size: 12px; font-weight: 600; color: #333;"><?php echo htmlspecialchars($report['category_code']); ?> - <?php echo htmlspecialchars($report['test_description']); ?></td>
            <td style="text-align: right; width: 200px;">
                <div style="font-size: 10px; color: #000; background-color: #e8eef5; padding: 4px 8px;">
                    Performed at: <?php echo date('d/M/Y - h:i A', strtotime($report['performed_date'])); ?><br>
                    Published at: <?php echo date('d/M/Y - h:i A', strtotime($report['published_date'])); ?>
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; font-size: 11px; margin: 8px 0;" border="0" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th style="background-color: #bfbfbf; padding: 8px; text-align: left; border: 1px solid #999; font-weight: bold; font-size: 11px; color: #000; width: 40%;">Test</th>
                <th style="background-color: #bfbfbf; padding: 8px; text-align: left; border: 1px solid #999; font-weight: bold; font-size: 11px; color: #000; width: 25%;">Reference Ranges</th>
                <th style="background-color: #bfbfbf; padding: 8px; text-align: left; border: 1px solid #999; font-weight: bold; font-size: 11px; color: #000; width: 15%;">Unit</th>
                <th style="background-color: #bfbfbf; padding: 8px; text-align: left; border: 1px solid #999; font-weight: bold; font-size: 11px; color: #000; width: 20%;">Result</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): 
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
                        $arrow = ' ↓';
                    } elseif ($result['max_value'] !== '' && strpos($result['max_value'], '<') === false && $numeric_value > floatval($result['max_value'])) {
                        $arrow = ' ↑';
                    }
                }
            ?>
                <tr>
                    <td style="padding: 6px 8px;"><?php echo htmlspecialchars($result['test_name']); ?></td>
                    <td style="padding: 6px 8px;"><?php echo htmlspecialchars($reference_range); ?></td>
                    <td style="padding: 6px 8px;"><?php echo htmlspecialchars($result['unit']); ?></td>
                    <td style="padding: 6px 8px; <?php echo $is_abnormal ? 'color: #c62828; font-weight: bold;' : ''; ?>">
                        <?php echo htmlspecialchars($result['result_value']) . $arrow; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($index < count($reports) - 1): ?>
        <hr style="border: 0; border-bottom: 2px solid #ddd; margin: 30px 0;">
    <?php endif; ?>
<?php endforeach; ?>

<?php if (!empty($mainReport['comments'])): ?>
<table style="width: 100%; margin: 15px 0; border-left: 3px solid #2c5aa0; background-color: #f8f9fa;">
    <tr>
        <td style="padding: 10px;">
            <strong style="color: #2c5aa0;">Comments:</strong><br>
            <p style="margin: 5px 0 0 0; font-size: 10px;"><?php echo nl2br(htmlspecialchars($mainReport['comments'])); ?></p>
        </td>
    </tr>
</table>
<?php endif; ?>

<p style="margin: 30px 0 5px 0; font-size: 11px; color: #666; font-style: italic;">*Electronically verified report, signatures are not required</p>

<table style="width: 100%; margin-top: 20px; padding-top: 10px; border-top: 2px solid #000;">
    <tr>
        <td style="text-align: right;">
            <p style="margin: 0; font-weight: bold; font-size: 12px;">Verified By</p>
            <p style="margin: 5px 0 0 0; font-size: 11px;"><?php echo htmlspecialchars($mainReport['verified_by'] ?? 'Dr Mehreen Naseer'); ?></p>
        </td>
    </tr>
</table>

<?php
$html = ob_get_clean();

$pdf->writeHTML($html, true, false, true, false, '');

$filename = 'Medical_Report_' . preg_replace('/[^A-Za-z0-9_]/', '_', $mainReport['patient_name']) . '_' . date('Y-m-d') . '.pdf';

$pdf->Output($filename, 'D');
?>
