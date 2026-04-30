<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Get all reports with patient, category, and user information
$stmt = $conn->query("
    SELECT r.*, p.name as patient_name, p.mrn, tc.code as test_code, tc.description as test_name,
           tc.name as parent_category_name,
           u.full_name as created_by_name, u.username as created_by_username
    FROM reports r
    JOIN patients p ON r.patient_id = p.id
    JOIN test_categories tc ON r.category_id = tc.id
    LEFT JOIN users u ON r.created_by = u.id
    ORDER BY r.created_at DESC
");
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group reports by patient and parent category for bulk download
$bulkGroups = [];
foreach ($reports as $report) {
    if ($report['status'] == 'completed') {
        $key = $report['patient_id'] . '_' . $report['parent_category_name'];
        if (!isset($bulkGroups[$key])) {
            $bulkGroups[$key] = [
                'patient_id' => $report['patient_id'],
                'patient_name' => $report['patient_name'],
                'mrn' => $report['mrn'],
                'parent_category' => $report['parent_category_name'],
                'report_ids' => []
            ];
        }
        $bulkGroups[$key]['report_ids'][] = $report['id'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reports - BVH Medical Lab</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 1400px;
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
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        
        .filter-section {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }
        
        .filter-section input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .filter-section input:focus {
            outline: none;
            border-color: #5a67d8;
        }
        
        .reports-table-container {
            background: white;
            border-radius: 6px;
            overflow: hidden;
        
        .reports-table-container {
            background: white;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table thead {
            background: #f5f5f5;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        table tbody tr:hover {
            background: #f9f9f9;
        }
        
        table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .badge {
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            display: inline-block;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-view {
            background: #28a745;
            color: white;
        }
        
        .btn-view:hover {
            background: #218838;
        }
        
        .btn-download {
            background: #17a2b8;
            color: white;
        }
        
        .btn-download:hover {
            background: #138496;
        }
        
        .btn-bulk {
            background: #6f42c1;
            color: white;
        }
        
        .btn-bulk:hover {
            background: #5a32a3;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .btn-edit {
            background: #ffc107;
            color: #333;
        }
        
        .btn-edit:hover {
            background: #e0a800;
        }
        
        .no-reports {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .no-reports h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="header">
            <h1>Medical Reports</h1>
            <p>View and manage all laboratory test reports</p>
        </div>
        
        <div class="stats-row">
            <div class="stat-card">
                <h3>Total Reports</h3>
                <div class="number"><?php echo count($reports); ?></div>
            </div>
            <div class="stat-card">
                <h3>Today's Reports</h3>
                <div class="number">
                    <?php 
                    $today = array_filter($reports, function($r) {
                        return date('Y-m-d', strtotime($r['created_at'])) == date('Y-m-d');
                    });
                    echo count($today);
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>This Week</h3>
                <div class="number">
                    <?php 
                    $week_start = date('Y-m-d', strtotime('monday this week'));
                    $week_reports = array_filter($reports, function($r) use ($week_start) {
                        return date('Y-m-d', strtotime($r['created_at'])) >= $week_start;
                    });
                    echo count($week_reports);
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>This Month</h3>
                <div class="number">
                    <?php 
                    $month_reports = array_filter($reports, function($r) {
                        return date('Y-m', strtotime($r['created_at'])) == date('Y-m');
                    });
                    echo count($month_reports);
                    ?>
                </div>
            </div>
        </div>
        
        <div class="filter-section">
            <input type="text" id="searchInput" placeholder="🔍 Search by MRN, Patient Name, Test Type..." onkeyup="filterTable()">
        </div>
        
        <div class="reports-table-container">
            <?php if (count($reports) > 0): ?>
                <div class="table-responsive">
                    <table id="reportsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>MRN</th>
                                <th>Patient Name</th>
                                <th>Test Type</th>
                                <th>Parent Category</th>
                                <th>Department</th>
                             
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): 
                                // Check if this report has bulk download option
                                $bulkKey = $report['patient_id'] . '_' . $report['parent_category_name'];
                                $hasBulk = isset($bulkGroups[$bulkKey]) && count($bulkGroups[$bulkKey]['report_ids']) > 1;
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $report['id']; ?></strong></td>
                                    <td><?php echo date('d M Y, h:i A', strtotime($report['created_at'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($report['mrn']); ?></strong></td>
                                    <td><?php echo htmlspecialchars(strtoupper($report['patient_name'])); ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo htmlspecialchars($report['test_code']); ?>
                                        </span>
                                        <br>
                                        <small style="color: #666;"><?php echo htmlspecialchars($report['test_name']); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($report['parent_category_name']); ?></strong>
                                        <?php if ($hasBulk): ?>
                                            <br><small style="color: #6f42c1;">📦 <?php echo count($bulkGroups[$bulkKey]['report_ids']); ?> reports</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($report['department']); ?></td>
                                    
                                    <td>
                                        <span class="badge <?php echo $report['status'] == 'completed' ? 'badge-success' : 'badge-warning'; ?>">
                                            <?php echo ucfirst($report['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="view_report.php?id=<?php echo $report['id']; ?>" 
                                               class="btn btn-sm btn-view" 
                                               target="_blank">
                                                👁️ View
                                            </a>
                                            
                                            <a href="edit_report.php?id=<?php echo $report['id']; ?>" 
                                               class="btn btn-sm btn-edit">
                                                ✏️ Edit
                                            </a>
                                           
                                            <a href="download_pdf.php?id=<?php echo $report['id']; ?>" 
                                               class="btn btn-sm btn-download">
                                                ⬇️ Download
                                            </a>
                                            
                                            <?php if ($hasBulk && $report['status'] == 'completed'): ?>
                                                <a href="download_pdf_bulk.php?ids=<?php echo implode(',', $bulkGroups[$bulkKey]['report_ids']); ?>" 
                                                   class="btn btn-sm btn-bulk"
                                                   title="Download all <?php echo count($bulkGroups[$bulkKey]['report_ids']); ?> reports for <?php echo htmlspecialchars($report['parent_category_name']); ?>">
                                                    📦 Bulk (<?php echo count($bulkGroups[$bulkKey]['report_ids']); ?>)
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-reports">
                    <h3>📭 No Reports Found</h3>
                    <p>No medical reports have been created yet.</p>
                    <a href="dashboard.php" class="btn btn-primary" style="margin-top: 20px;">Create First Report</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('reportsTable');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                const tds = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < tds.length; j++) {
                    const td = tds[j];
                    if (td) {
                        const txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        }
    </script>
</body>
</html>
