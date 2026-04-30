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
            $username = $_POST['username'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'staff';
            
            if (empty($username) || empty($full_name) || empty($password)) {
                throw new Exception('All fields are required');
            }
            
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            if ($stmt->fetch()) {
                throw new Exception('Username already exists');
            }
            
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, role) 
                                   VALUES (:username, :password, :full_name, :role)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password,
                'full_name' => $full_name,
                'role' => $role
            ]);
            
            echo json_encode(['success' => true, 'message' => 'User added successfully']);
            break;
            
        case 'update':
            $id = $_POST['id'] ?? 0;
            $username = $_POST['username'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'staff';
            
            if (empty($username) || empty($full_name)) {
                throw new Exception('Username and full name are required');
            }
            
            // Check if username is taken by another user
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username AND id != :id");
            $stmt->execute(['username' => $username, 'id' => $id]);
            if ($stmt->fetch()) {
                throw new Exception('Username already exists');
            }
            
            // Update user
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username = :username, password = :password, 
                                       full_name = :full_name, role = :role WHERE id = :id");
                $stmt->execute([
                    'username' => $username,
                    'password' => $hashed_password,
                    'full_name' => $full_name,
                    'role' => $role,
                    'id' => $id
                ]);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username = :username, 
                                       full_name = :full_name, role = :role WHERE id = :id");
                $stmt->execute([
                    'username' => $username,
                    'full_name' => $full_name,
                    'role' => $role,
                    'id' => $id
                ]);
            }
            
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
            break;
            
        case 'delete':
            $id = $_POST['id'] ?? 0;
            
            // Prevent deleting own account
            if ($id == $_SESSION['user_id']) {
                throw new Exception('Cannot delete your own account');
            }
            
            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
