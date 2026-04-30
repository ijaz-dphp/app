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

$category_id = $_GET['category_id'] ?? '';

if (empty($category_id)) {
    echo json_encode(['success' => false, 'error' => 'Category ID is required']);
    exit;
}

try {
    // Get all parameters for the category, ordered by display_order
    $stmt = $conn->prepare("
        SELECT 
            id,
            category_id,
            test_name,
            unit,
            min_value,
            max_value,
            show_in_pdf,
            display_order,
            default_comment
        FROM test_parameters 
        WHERE category_id = :category_id
        ORDER BY display_order ASC, id ASC
    ");
    
    $stmt->execute(['category_id' => $category_id]);
    $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the data
    foreach ($parameters as &$param) {
        $param['show_in_pdf'] = (int)$param['show_in_pdf'];
        $param['display_order'] = (int)$param['display_order'];
    }
    
    echo json_encode([
        'success' => true,
        'parameters' => $parameters,
        'count' => count($parameters)
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get_test_parameters.php: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'error' => 'Database error occurred',
        'details' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error in get_test_parameters.php: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'error' => 'An error occurred',
        'details' => $e->getMessage()
    ]);
}
?>
