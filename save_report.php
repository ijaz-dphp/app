<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reports_list.php');
    exit;
}

$report_id = $_POST['report_id'] ?? 0;

// Validate report exists
$stmt = $conn->prepare("SELECT id FROM reports WHERE id = :id");
$stmt->execute(['id' => $report_id]);
if (!$stmt->fetch()) {
    $_SESSION['error'] = 'Report not found';
    header('Location: reports_list.php');
    exit;
}

try {
    $conn->beginTransaction();
    
    // Update report details
    $request_date = !empty($_POST['request_date']) ? $_POST['request_date'] : null;
    $performed_date = !empty($_POST['performed_date']) ? $_POST['performed_date'] : null;
    $published_date = !empty($_POST['published_date']) ? $_POST['published_date'] : null;
    $printed_date = !empty($_POST['printed_date']) ? $_POST['printed_date'] : null;
    $department = $_POST['department'] ?? '';
    $ward = $_POST['ward'] ?? '';
    $verified_by = $_POST['verified_by'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    
    // Validate status
    $valid_statuses = ['pending', 'in_progress', 'completed'];
    if (!in_array($status, $valid_statuses)) {
        $status = 'pending';
    }
    
    $stmt = $conn->prepare("
        UPDATE reports 
        SET request_date = :request_date,
            performed_date = :performed_date,
            published_date = :published_date,
            printed_date = :printed_date,
            department = :department,
            ward = :ward,
            verified_by = :verified_by,
            status = :status
        WHERE id = :id
    ");
    
    $stmt->execute([
        ':request_date' => $request_date,
        ':performed_date' => $performed_date,
        ':published_date' => $published_date,
        ':printed_date' => $printed_date,
        ':department' => $department,
        ':ward' => $ward,
        ':verified_by' => $verified_by,
        ':status' => $status,
        ':id' => $report_id
    ]);
    
    // Update test results
    if (isset($_POST['result_values']) && is_array($_POST['result_values'])) {
        foreach ($_POST['result_values'] as $parameter_id => $value) {
            // Sanitize parameter ID
            $parameter_id = intval($parameter_id);
            $value = trim($value);
            
            // Check if abnormal flag is set
            $is_abnormal = isset($_POST['abnormal_flags'][$parameter_id]) ? 1 : 0;
            
            // Check if result already exists for this parameter
            $check_stmt = $conn->prepare("
                SELECT id FROM report_results 
                WHERE report_id = :report_id AND parameter_id = :parameter_id
            ");
            $check_stmt->execute([':report_id' => $report_id, ':parameter_id' => $parameter_id]);
            $existing = $check_stmt->fetch();
            
            if ($existing) {
                // Update existing result
                $update_stmt = $conn->prepare("
                    UPDATE report_results 
                    SET result_value = :value,
                        is_abnormal = :is_abnormal
                    WHERE report_id = :report_id AND parameter_id = :parameter_id
                ");
                
                $update_stmt->execute([
                    ':value' => $value,
                    ':is_abnormal' => $is_abnormal,
                    ':report_id' => $report_id,
                    ':parameter_id' => $parameter_id
                ]);
            } else if ($value !== '') {
                // Insert new result if value is not empty
                $insert_stmt = $conn->prepare("
                    INSERT INTO report_results (report_id, parameter_id, result_value, is_abnormal)
                    VALUES (:report_id, :parameter_id, :value, :is_abnormal)
                ");
                
                $insert_stmt->execute([
                    ':report_id' => $report_id,
                    ':parameter_id' => $parameter_id,
                    ':value' => $value,
                    ':is_abnormal' => $is_abnormal
                ]);
            }
        }
    }
    
    $conn->commit();
    
    $_SESSION['success'] = 'Report updated successfully';
    header('Location: view_report.php?id=' . $report_id);
    exit;
    
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error'] = 'Error updating report: ' . $e->getMessage();
    header('Location: edit_report.php?id=' . $report_id);
    exit;
}
?>
