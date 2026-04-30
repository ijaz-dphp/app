<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

require_once 'config/database.php';

header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'update_test':
            $id = $_POST['id'] ?? '';
            $code = $_POST['code'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($id) || empty($code) || empty($name)) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                exit;
            }
            
            // Check if code already exists for a different test
            $checkStmt = $conn->prepare("SELECT id FROM test_categories WHERE code = :code AND id != :id");
            $checkStmt->execute(['code' => $code, 'id' => $id]);
            if ($checkStmt->fetch()) {
                echo json_encode(['success' => false, 'error' => 'Test code "' . $code . '" is already in use by another test. Please use a unique code.']);
                exit;
            }
            
            $stmt = $conn->prepare("UPDATE test_categories SET code = :code, name = :name, description = :description WHERE id = :id");
            $stmt->execute([
                'code' => $code,
                'name' => $name,
                'description' => $description,
                'id' => $id
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Test category updated successfully']);
            break;
            
        case 'toggle_status':
            $id = $_POST['id'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if (empty($id) || $status === '') {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                exit;
            }
            
            $stmt = $conn->prepare("UPDATE test_categories SET is_active = :status WHERE id = :id");
            $stmt->execute([
                'status' => $status,
                'id' => $id
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            break;
            
        case 'bulk_update':
            $test_ids = $_POST['test_ids'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if (empty($test_ids) || $status === '') {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                exit;
            }
            
            $ids = explode(',', $test_ids);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            
            $stmt = $conn->prepare("UPDATE test_categories SET is_active = ? WHERE id IN ($placeholders)");
            $params = array_merge([$status], $ids);
            $stmt->execute($params);
            
            echo json_encode(['success' => true, 'message' => 'Tests updated successfully']);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    error_log("Database error in ajax_manage_tests.php: " . $e->getMessage());
    
    // Provide user-friendly error messages
    if (strpos($e->getMessage(), 'UNIQUE constraint failed: test_categories.code') !== false) {
        echo json_encode(['success' => false, 'error' => 'This test code is already in use. Please choose a unique code.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error occurred. Please try again.']);
    }
} catch (Exception $e) {
    error_log("Error in ajax_manage_tests.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
