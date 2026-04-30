<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

$action = $_POST['action'] ?? '';
$category_id = $_POST['category_id'] ?? 0;
$comment = $_POST['comment'] ?? '';

try {
    if ($action === 'save_category_comment') {
        // Check if category comment exists
        $stmt = $conn->prepare("SELECT id FROM test_categories WHERE id = :category_id");
        $stmt->execute(['category_id' => $category_id]);
        
        if ($stmt->fetch()) {
            // Update the category with comment
            $stmt = $conn->prepare("UPDATE test_categories SET category_comment = :comment WHERE id = :category_id");
            $stmt->execute([
                'comment' => $comment,
                'category_id' => $category_id
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Category comment saved']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Category not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
