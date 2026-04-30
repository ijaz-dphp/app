<style>
    .navbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .navbar-brand {
        font-size: 20px;
        font-weight: bold;
    }
    
    .navbar-menu {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    
    .navbar-menu a {
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 4px;
        transition: background 0.3s;
        font-size: 14px;
    }
    
    .navbar-menu a:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .navbar-user {
        display: flex;
        gap: 15px;
        align-items: center;
        position: relative;
    }
    
    .navbar-user span {
        font-size: 14px;
    }
    
    .user-dropdown {
        position: relative;
    }
    
    .user-dropdown-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .user-dropdown-btn:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .user-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-top: 5px;
        min-width: 180px;
        z-index: 1000;
    }
    
    .user-dropdown-menu.show {
        display: block;
    }
    
    .user-dropdown-menu a {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        font-size: 13px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .user-dropdown-menu a:last-child {
        border-bottom: none;
        color: #dc3545;
    }
    
    .user-dropdown-menu a:hover {
        background: #f8f9fa;
    }
    
    .btn-light {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 6px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
    }
    
    .btn-light:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .nav-dropdown {
        position: relative;
    }
    
    .nav-dropdown-btn {
        background: none;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .nav-dropdown-btn:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .nav-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-top: 5px;
        min-width: 200px;
        z-index: 1000;
    }
    
    .nav-dropdown-menu.show {
        display: block;
    }
    
    .nav-dropdown-menu a {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        font-size: 13px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .nav-dropdown-menu a:last-child {
        border-bottom: none;
    }
    
    .nav-dropdown-menu a:hover {
        background: #f8f9fa;
    }
</style>

<nav class="navbar">
    <div class="navbar-brand">🏥 BVH Medical Lab System</div>
    <div class="navbar-menu">
        <a href="dashboard.php">Dashboard</a>
        <div class="nav-dropdown">
            <button class="nav-dropdown-btn" onclick="toggleNavDropdown(event)">
                New Report ▼
            </button>
            <div class="nav-dropdown-menu" id="navDropdownMenu">
                <a href="new_report.php">📝 Single Test Report</a>
                <a href="new_report_multi.php">📋 Multiple Tests Report</a>
            </div>
        </div>
        <a href="reports_list.php">Reports</a>
        <a href="bulk_reports_by_mrn.php">Bulk Download</a>
        <a href="manage_tests_combined.php">Test Management</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="manage_users.php">User Management</a>
        <?php endif; ?>
    </div>
    <div class="navbar-user">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></span>
        <div class="user-dropdown">
            <button class="user-dropdown-btn" onclick="toggleUserDropdown()">
                My Account ▼
            </button>
            <div class="user-dropdown-menu" id="userDropdownMenu">
                <a href="change_password.php">🔒 Change Password</a>
                <a href="logout.php">🚪 Logout</a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleNavDropdown(event) {
    event.stopPropagation();
    const menu = document.getElementById('navDropdownMenu');
    const userMenu = document.getElementById('userDropdownMenu');
    
    // Close user dropdown if open
    if (userMenu && userMenu.classList.contains('show')) {
        userMenu.classList.remove('show');
    }
    
    menu.classList.toggle('show');
}

function toggleUserDropdown() {
    const menu = document.getElementById('userDropdownMenu');
    const navMenu = document.getElementById('navDropdownMenu');
    
    // Close nav dropdown if open
    if (navMenu && navMenu.classList.contains('show')) {
        navMenu.classList.remove('show');
    }
    
    menu.classList.toggle('show');
}

// Close dropdowns when clicking outside
window.addEventListener('click', function(e) {
    if (!e.target.matches('.user-dropdown-btn') && !e.target.matches('.nav-dropdown-btn')) {
        const userDropdown = document.getElementById('userDropdownMenu');
        const navDropdown = document.getElementById('navDropdownMenu');
        
        if (userDropdown && userDropdown.classList.contains('show')) {
            userDropdown.classList.remove('show');
        }
        
        if (navDropdown && navDropdown.classList.contains('show')) {
            navDropdown.classList.remove('show');
        }
    }
});
</script>
