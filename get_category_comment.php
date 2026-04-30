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

$category_id = $_GET['category_id'] ?? 0;

try {
    $stmt = $conn->prepare("SELECT category_comment FROM test_categories WHERE id = :category_id");
    $stmt->execute(['category_id' => $category_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'comment' => $result['category_comment'] ?? ''
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'comment' => ''
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
