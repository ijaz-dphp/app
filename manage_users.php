<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Get all users
$stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - BVH Medical Lab</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: #5a67d8;
            color: white;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .actions-bar {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #5a67d8;
            color: white;
        }
        
        .btn-primary:hover {
            background: #4c51bf;
        }
        
        .btn-edit {
            background: #28a745;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .btn-edit:hover {
            background: #218838;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .users-table-wrapper {
            background: white;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .users-table thead {
            background: #f5f5f5;
        }
        
        .users-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .users-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .users-table tbody tr:hover {
            background: #f9f9f9;
        }
        
        .users-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .role-admin {
            background: #dc3545;
            color: white;
        }
        
        .role-staff {
            background: #17a2b8;
            color: white;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 0;
            border-radius: 6px;
            max-width: 500px;
        }
        
        .modal-header {
            background: #5a67d8;
            color: white;
            padding: 15px 20px;
            border-radius: 6px 6px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 18px;
            margin: 0;
            font-weight: 500;
        }
        
        .close-modal {
            background: transparent;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            line-height: 1;
        }
        
        .close-modal:hover {
            opacity: 0.8;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
            font-size: 13px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5a67d8;
        }
        
        .btn-save {
            background: #5a67d8;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
        }
        
        .btn-save:hover {
            background: #4c51bf;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="header">
            <h1>User Management</h1>
            <p>Manage system users and their permissions</p>
        </div>
        
        <div class="actions-bar">
            <div>
                <strong>Total Users:</strong> <?php echo count($users); ?>
            </div>
            <button class="btn btn-primary" onclick="openAddUserModal()">+ Add New User</button>
        </div>
        
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-edit" onclick='editUser(<?php echo json_encode($user); ?>)'>Edit</button>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <button class="btn btn-delete" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Add New User</h3>
                <button type="button" class="close-modal" onclick="closeUserModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="userForm" onsubmit="saveUser(event)">
                    <input type="hidden" id="userId">
                    
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" id="username" required placeholder="e.g., john.doe">
                    </div>
                    
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" id="fullName" required placeholder="e.g., John Doe">
                    </div>
                    
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" id="password" placeholder="Leave empty to keep current password">
                    </div>
                    
                    <div class="form-group">
                        <label>Role *</label>
                        <select id="role" required>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-save">Save User</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function openAddUserModal() {
            document.getElementById('modalTitle').textContent = 'Add New User';
            document.getElementById('userId').value = '';
            document.getElementById('username').value = '';
            document.getElementById('fullName').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password').required = true;
            document.getElementById('password').placeholder = 'Enter password';
            document.getElementById('role').value = 'staff';
            document.getElementById('userModal').style.display = 'block';
        }
        
        function editUser(user) {
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('userId').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('fullName').value = user.full_name;
            document.getElementById('password').value = '';
            document.getElementById('password').required = false;
            document.getElementById('password').placeholder = 'Leave empty to keep current password';
            document.getElementById('role').value = user.role;
            document.getElementById('userModal').style.display = 'block';
        }
        
        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
        }
        
        function saveUser(event) {
            event.preventDefault();
            
            const formData = new FormData();
            const userId = document.getElementById('userId').value;
            
            formData.append('action', userId ? 'update' : 'add');
            if (userId) formData.append('id', userId);
            formData.append('username', document.getElementById('username').value);
            formData.append('full_name', document.getElementById('fullName').value);
            formData.append('password', document.getElementById('password').value);
            formData.append('role', document.getElementById('role').value);
            
            fetch('save_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the user');
            });
        }
        
        function deleteUser(id, username) {
            if (!confirm(`Are you sure you want to delete user "${username}"?`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);
            
            fetch('save_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the user');
            });
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                closeUserModal();
            }
        }
    </script>
</body>
</html>
