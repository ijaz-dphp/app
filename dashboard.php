<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Get statistics
$total_reports = $conn->query("SELECT COUNT(*) FROM reports")->fetchColumn();
$total_patients = $conn->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$pending_reports = $conn->query("SELECT COUNT(*) FROM reports WHERE status = 'pending'")->fetchColumn();

// Get test categories (only active parent categories, sorted alphabetically)
$categories = $conn->query("SELECT * FROM test_categories WHERE is_active = 1 AND parent_category_id IS NULL ORDER BY code ASC")->fetchAll(PDO::FETCH_ASSOC);

// Get recent reports with user information
$recent_reports = $conn->query("
    SELECT r.*, p.name as patient_name, p.mrn, tc.name as test_name,
           u.full_name as created_by_name, u.username as created_by_username
    FROM reports r 
    JOIN patients p ON r.patient_id = p.id 
    JOIN test_categories tc ON r.category_id = tc.id 
    LEFT JOIN users u ON r.created_by = u.id
    ORDER BY r.created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BVH Medical Lab</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
        }
        
        .btn-success {
            background: #26de81;
            color: white;
        }
        
        .btn-success:hover {
            background: #20bf6b;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid;
        }
        
        .stat-card.blue {
            border-color: #667eea;
        }
        
        .stat-card.green {
            border-color: #26de81;
        }
        
        .stat-card.orange {
            border-color: #fd9644;
        }
        
        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }
        
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }
        
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .test-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s;
            text-decoration: none;
            display: block;
        }
        
        .test-card:hover {
            transform: translateY(-5px);
        }
        
        .test-card h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .test-card p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .search-box {
            margin-bottom: 20px;
        }
        
        .search-box input {
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
        }
        
        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <h3>Total Reports</h3>
                <div class="number"><?php echo $total_reports; ?></div>
            </div>
            <div class="stat-card green">
                <h3>Total Patients</h3>
                <div class="number"><?php echo $total_patients; ?></div>
            </div>
            <div class="stat-card orange">
                <h3>Pending Reports</h3>
                <div class="number"><?php echo $pending_reports; ?></div>
            </div>
        </div>
        
        <!-- New Report Section -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">🧪 Create New Report</h2>
            </div>
            
            <!-- Search Box -->
            <div class="search-box">
                <input type="text" id="testSearch" placeholder="Search tests by code or description..." onkeyup="filterTests()">
            </div>
            
            <div class="test-grid" id="testGrid">
                <?php foreach ($categories as $category): ?>
                    <a href="new_report.php?category=<?php echo $category['id']; ?>" class="test-card" data-code="<?php echo strtolower($category['code']); ?>" data-desc="<?php echo strtolower($category['description']); ?>">
                        <h4><?php echo htmlspecialchars($category['code']); ?></h4>
                        <p><?php echo htmlspecialchars($category['description']); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Recent Reports -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">📋 Recent Reports</h2>
                <a href="reports_list.php" class="btn btn-primary">View All</a>
            </div>
            
            <?php if (count($recent_reports) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>MRN</th>
                            <th>Patient Name</th>
                            <th>Test Type</th>
                            <th>Date</th>
                            
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_reports as $report): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($report['mrn']); ?></td>
                                <td><?php echo htmlspecialchars($report['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($report['test_name']); ?></td>
                                <td><?php echo date('d-M-Y', strtotime($report['created_at'])); ?></td>
  
                                <td>
                                    <?php
                                    $badge_class = $report['status'] == 'completed' ? 'badge-success' : 'badge-warning';
                                    echo '<span class="badge ' . $badge_class . '">' . ucfirst($report['status']) . '</span>';
                                    ?>
                                </td>
                                <td>
                                    <a href="view_report.php?id=<?php echo $report['id']; ?>" class="btn btn-primary" style="padding: 5px 15px; font-size: 12px;">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #999; padding: 20px;">No reports found. Create your first report!</p>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    function filterTests() {
        const searchValue = document.getElementById('testSearch').value.toLowerCase();
        const testCards = document.querySelectorAll('.test-card');
        
        testCards.forEach(card => {
            const code = card.getAttribute('data-code');
            const desc = card.getAttribute('data-desc');
            
            if (code.includes(searchValue) || desc.includes(searchValue)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    </script>
</body>
</html>
