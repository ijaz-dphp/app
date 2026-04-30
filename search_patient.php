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

$mrn = $_GET['mrn'] ?? '';

if (empty($mrn)) {
    echo json_encode(['success' => false, 'patients' => []]);
    exit;
}

try {
    // Search for patients by MRN (partial match)
    $stmt = $conn->prepare("
        SELECT mrn, name, contact, age, gender, father_husband_name 
        FROM patients 
        WHERE mrn LIKE :mrn OR name LIKE :name
        ORDER BY mrn DESC
        LIMIT 10
    ");
    
    $searchTerm = '%' . $mrn . '%';
    $stmt->execute([
        'mrn' => $searchTerm,
        'name' => $searchTerm
    ]);
    
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'patients' => $patients
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in search_patient.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
