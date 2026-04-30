<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    try {
        // Validate inputs
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            throw new Exception('All fields are required');
        }
        
        if (strlen($new_password) < 6) {
            throw new Exception('New password must be at least 6 characters long');
        }
        
        if ($new_password !== $confirm_password) {
            throw new Exception('New password and confirmation do not match');
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        
        // Get current user
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            throw new Exception('User not found');
        }
        
        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            throw new Exception('Current password is incorrect');
        }
        
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute([
            'password' => $hashed_password,
            'id' => $_SESSION['user_id']
        ]);
        
        $_SESSION['password_change_success'] = 'Password changed successfully!';
        header('Location: change_password.php');
        exit;
        
    } catch (Exception $e) {
        $_SESSION['password_change_error'] = $e->getMessage();
        header('Location: change_password.php');
        exit;
    }
} else {
    header('Location: change_password.php');
    exit;
}
?>
