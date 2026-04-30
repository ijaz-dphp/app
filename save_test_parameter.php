<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            $category_id = $_POST['category_id'] ?? 0;
            $test_name = $_POST['test_name'] ?? '';
            $unit = $_POST['unit'] ?? '';
            $min_value = $_POST['min_value'] ?? '';
            $max_value = $_POST['max_value'] ?? '';
            $show_in_pdf = $_POST['show_in_pdf'] ?? 0;
            
            if (empty($category_id) || empty($test_name)) {
                throw new Exception('Category and test name are required');
            }
            
            // Get the next display_order value
            $stmt = $conn->prepare("SELECT MAX(display_order) as max_order FROM test_parameters WHERE category_id = :category_id");
            $stmt->execute(['category_id' => $category_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $display_order = ($result['max_order'] ?? 0) + 1;
            
            // Insert test parameter
            $stmt = $conn->prepare("INSERT INTO test_parameters 
                                   (category_id, test_name, unit, min_value, max_value, show_in_pdf, display_order) 
                                   VALUES (:category_id, :test_name, :unit, :min_value, :max_value, :show_in_pdf, :display_order)");
            $stmt->execute([
                'category_id' => $category_id,
                'test_name' => $test_name,
                'unit' => $unit,
                'min_value' => $min_value,
                'max_value' => $max_value,
                'show_in_pdf' => $show_in_pdf,
                'display_order' => $display_order
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Parameter added successfully']);
            break;
            
        case 'update':
            $id = $_POST['id'] ?? 0;
            $category_id = $_POST['category_id'] ?? 0;
            $test_name = $_POST['test_name'] ?? '';
            $unit = $_POST['unit'] ?? '';
            $min_value = $_POST['min_value'] ?? '';
            $max_value = $_POST['max_value'] ?? '';
            $show_in_pdf = $_POST['show_in_pdf'] ?? 0;
            
            if (empty($id) || empty($test_name)) {
                throw new Exception('ID and test name are required');
            }
            
            // Update test parameter
            $stmt = $conn->prepare("UPDATE test_parameters 
                                   SET test_name = :test_name, unit = :unit, min_value = :min_value, 
                                       max_value = :max_value, show_in_pdf = :show_in_pdf
                                   WHERE id = :id");
            $stmt->execute([
                'test_name' => $test_name,
                'unit' => $unit,
                'min_value' => $min_value,
                'max_value' => $max_value,
                'show_in_pdf' => $show_in_pdf,
                'id' => $id
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Parameter updated successfully']);
            break;
            
        case 'delete':
            $id = $_POST['id'] ?? 0;
            
            if (empty($id)) {
                throw new Exception('ID is required');
            }
            
            // Delete the parameter
            $stmt = $conn->prepare("DELETE FROM test_parameters WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            echo json_encode(['success' => true, 'message' => 'Parameter deleted successfully']);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
