<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Get all parent test categories (both active and inactive)
$stmt = $conn->query("SELECT * FROM test_categories WHERE parent_category_id IS NULL ORDER BY name, code");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get count of parameters for each category
$param_counts = [];
$stmt = $conn->query("SELECT category_id, COUNT(*) as count FROM test_parameters GROUP BY category_id");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $param_counts[$row['category_id']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Test Management - BVH Medical Lab</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Version 2.0 - Table View Updated -->
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
        
        .view-toggle {
            background: white;
            padding: 5px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            display: flex;
            gap: 5px;
        }
        
        .view-btn {
            flex: 1;
            padding: 10px 15px;
            border: none;
            background: transparent;
            border-radius: 4px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            color: #555;
        }
        
        .view-btn:hover {
            background: #f5f5f5;
        }
        
        .view-btn.active {
            background: #5a67d8;
            color: white;
        }
        
        .view-panel {
            display: none;
        }
        
        .view-panel.active {
            display: block;
        }
        
        /* Statistics Bar */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        /* Search and Filters */
        .controls-bar {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        
        .search-box {
            flex: 1;
            min-width: 250px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .search-box:focus {
            outline: none;
            border-color: #5a67d8;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-label {
            color: #555;
            font-weight: 500;
            font-size: 13px;
        }
        
        .filter-tabs {
            display: flex;
            gap: 5px;
        }
        
        .filter-tab {
            padding: 6px 12px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            color: #555;
        }
        
        .filter-tab:hover {
            background: #e8e8e8;
        }
        
        .filter-tab.active {
            background: #5a67d8;
            color: white;
            border-color: #5a67d8;
        }
        
        /* Cards Grid - Not used anymore but keep for compatibility */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 15px;
        }
        
        /* Bulk Actions */
        .bulk-mode-toggle {
            background: white;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }
        
        .bulk-mode-toggle label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        
        .bulk-mode-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .bulk-actions {
            background: #5a67d8;
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .selection-info {
            font-size: 14px;
            font-weight: 500;
        }
        
        .bulk-btn-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .btn-bulk {
            padding: 8px 15px;
            border: 1px solid rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.15);
            color: white;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            font-size: 13px;
        }
        
        .btn-bulk:hover {
            background: rgba(255,255,255,0.25);
        }
        
        .checkbox-wrapper {
            position: absolute;
            top: 12px;
            left: 12px;
        }
        
        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        /* Modal Styles */
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
            margin: 40px auto;
            padding: 0;
            border-radius: 6px;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
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
            width: 30px;
            height: 30px;
            border-radius: 4px;
            cursor: pointer;
            line-height: 1;
        }
        
        .close-modal:hover {
            background: rgba(255,255,255,0.2);
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
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5a67d8;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 70px;
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
        
        /* Parameter List in Modal */
        .param-list {
            margin-top: 15px;
        }
        
        .param-table-wrapper {
            overflow-x: auto;
            margin-top: 15px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }
        
        .param-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        .param-table thead {
            background: #f5f5f5;
        }
        
        .param-table th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .param-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .param-table tbody tr:hover {
            background: #f9f9f9;
        }
        
        .param-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .param-row-number {
            text-align: center;
            color: #666;
            font-weight: 500;
            font-size: 12px;
        }
        
        .param-name-cell {
            font-weight: 500;
            color: #333;
            font-size: 13px;
        }
        
        .param-unit-cell {
            color: #666;
            text-align: center;
        }
        
        .param-range-cell {
            color: #333;
        }
        
        .param-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .badge-visible {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-hidden {
            background: #f8d7da;
            color: #721c24;
        }
        
        .param-actions-cell {
            text-align: center;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            margin: 0 2px;
        }
        
        .btn-edit-small {
            background: #28a745;
            color: white;
        }
        
        .btn-edit-small:hover {
            background: #218838;
        }
        
        .btn-delete-small {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete-small:hover {
            background: #c82333;
        }
        
        .add-param-btn {
            background: #5a67d8;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            font-size: 13px;
        }
        
        .add-param-btn:hover {
            background: #4c51bf;
        }
        
        /* Main Tables for Tests and Categories */
        .main-table-wrapper {
            background: white;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .main-table thead {
            background: #f5f5f5;
        }
        
        .main-table th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .main-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .main-table tbody tr {
            background: white;
        }
        
        .main-table tbody tr:nth-child(even) {
            background: #fafafa;
        }
        
        .main-table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .main-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .main-table tbody tr.inactive {
            opacity: 0.6;
        }
        
        .test-code-badge {
            display: inline-block;
            background: #5a67d8;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .test-name-col {
            font-weight: 500;
            color: #333;
            font-size: 13px;
        }
        
        .description-col {
            color: #666;
            font-size: 13px;
        }
        
        .count-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .count-badge.has-params {
            background: #d4edda;
            color: #155724;
        }
        
        .count-badge.no-params {
            background: #fff3cd;
            color: #856404;
        }
        
        .actions-col {
            white-space: nowrap;
        }
        
        .actions-col .btn {
            margin: 2px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .view-toggle {
                flex-direction: column;
            }
            
            .cards-grid {
                grid-template-columns: 1fr;
            }
            
            .controls-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-tabs {
                flex-wrap: wrap;
            }
            
            .modal-content {
                margin: 20px;
                max-height: 90vh;
            }
            
            /* Make tables responsive */
            .main-table-wrapper,
            .param-table-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .main-table,
            .param-table {
                min-width: 900px;
            }
            
            .main-table th,
            .main-table td,
            .param-table th,
            .param-table td {
                padding: 10px 8px;
                font-size: 12px;
            }
            
            .btn-small {
                padding: 6px 10px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="header">
            <h1>Test Management Center</h1>
            <p>Manage test categories, parameters, reference ranges, and activation status</p>
        </div>
        
        <!-- View Toggle -->
        <div class="view-toggle">
            <button class="view-btn active" onclick="switchView('tests')" id="btnTestsView">
                Test Categories
            </button>
            <button class="view-btn" onclick="switchView('parameters')" id="btnParametersView">
                Test Parameters
            </button>
        </div>
        
        <!-- Tests View -->
        <div id="testsView" class="view-panel active">
            <?php
            $total_tests = count($categories);
            $active_tests = count(array_filter($categories, function($t) { return $t['is_active']; }));
            $inactive_tests = $total_tests - $active_tests;
            ?>
            
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_tests; ?></div>
                    <div class="stat-label">Total Tests</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $active_tests; ?></div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $inactive_tests; ?></div>
                    <div class="stat-label">Inactive</div>
                </div>
            </div>
            
            <div class="controls-bar">
                <input type="text" class="search-box" id="searchTests" placeholder="🔍 Search tests by name, code, or description..." onkeyup="filterTests()">
                
                <div class="filter-group">
                    <span class="filter-label">Filter:</span>
                    <div class="filter-tabs">
                        <div class="filter-tab active" onclick="filterTestsByStatus('all')">All Tests</div>
                        <div class="filter-tab" onclick="filterTestsByStatus('active')">Active Only</div>
                        <div class="filter-tab" onclick="filterTestsByStatus('inactive')">Inactive Only</div>
                    </div>
                </div>
            </div>
            
            <div class="bulk-mode-toggle">
                <label>
                    <input type="checkbox" id="toggleBulkMode" onchange="toggleBulkMode()">
                    <span>🔲 Enable Bulk Selection Mode</span>
                </label>
            </div>
            
            <div class="bulk-actions" id="bulkActions" style="display: none;">
                <div class="selection-info">
                    <span id="selectedCount">0</span> test(s) selected
                </div>
                <div class="bulk-btn-group">
                    <button class="btn-bulk" onclick="selectAllTests()">✓ Select All</button>
                    <button class="btn-bulk" onclick="clearSelection()">✗ Clear</button>
                    <button class="btn-bulk" onclick="bulkActivate()">✅ Activate</button>
                    <button class="btn-bulk" onclick="bulkDeactivate()">🚫 Deactivate</button>
                </div>
            </div>
            
            <div class="main-table-wrapper" id="testsTableWrapper">
                <table class="main-table" id="testsTable">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">
                                <input type="checkbox" id="bulkSelectAll" style="width: 18px; height: 18px; display: none;">
                            </th>
                            <th style="width: 10%;">Code</th>
                            <th style="width: 20%;">Test Name</th>
                            <th style="width: 35%;">Description</th>
                            <th style="width: 10%; text-align: center;">Status</th>
                            <th style="width: 20%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="testsTableBody">
                        <?php foreach ($categories as $test): ?>
                        <tr class="<?php echo $test['is_active'] ? '' : 'inactive'; ?>" 
                            data-status="<?php echo $test['is_active'] ? 'active' : 'inactive'; ?>"
                            data-id="<?php echo $test['id']; ?>"
                            data-search="<?php echo strtolower($test['name'] . ' ' . $test['code'] . ' ' . $test['description']); ?>">
                            
                            <td style="text-align: center;">
                                <input type="checkbox" class="test-checkbox" value="<?php echo $test['id']; ?>" 
                                       data-status="<?php echo $test['is_active']; ?>" 
                                       onchange="updateSelection()"
                                       style="width: 18px; height: 18px; cursor: pointer; display: none;">
                            </td>
                            
                            <td>
                                <span class="test-code-badge"><?php echo htmlspecialchars($test['code']); ?></span>
                            </td>
                            
                            <td class="test-name-col">
                                <?php echo htmlspecialchars($test['name']); ?>
                            </td>
                            
                            <td class="description-col">
                                <?php echo htmlspecialchars($test['description']); ?>
                            </td>
                            
                            <td style="text-align: center;">
                                <span class="status-badge <?php echo $test['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $test['is_active'] ? '✓ Active' : '✗ Inactive'; ?>
                                </span>
                            </td>
                            
                            <td class="actions-col" style="text-align: center;">
                                <button class="btn btn-edit" 
                                        data-id="<?php echo $test['id']; ?>"
                                        data-code="<?php echo htmlspecialchars($test['code']); ?>"
                                        data-name="<?php echo htmlspecialchars($test['name']); ?>"
                                        data-description="<?php echo htmlspecialchars($test['description']); ?>"
                                        onclick="openEditTestModal(this)">
                                    ✏️ Edit
                                </button>
                                <button class="btn btn-toggle <?php echo $test['is_active'] ? '' : 'inactive'; ?>" 
                                        onclick="toggleTestStatus(<?php echo $test['id']; ?>, <?php echo $test['is_active']; ?>)">
                                    <?php echo $test['is_active'] ? '🚫 Disable' : '✅ Enable'; ?>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Parameters View -->
        <div id="parametersView" class="view-panel">
            <?php
            $total_categories = count($categories);
            $categories_with_params = count(array_filter($param_counts));
            $categories_without_params = $total_categories - $categories_with_params;
            $total_params = array_sum($param_counts);
            ?>
            
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_categories; ?></div>
                    <div class="stat-label">Total Categories</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_params; ?></div>
                    <div class="stat-label">Total Parameters</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $categories_with_params; ?></div>
                    <div class="stat-label">Configured</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $categories_without_params; ?></div>
                    <div class="stat-label">Empty</div>
                </div>
            </div>
            
            <div class="controls-bar">
                <input type="text" class="search-box" id="searchParameters" placeholder="🔍 Search categories..." onkeyup="filterParameters()">
                
                <div class="filter-group">
                    <span class="filter-label">Filter:</span>
                    <div class="filter-tabs">
                        <div class="filter-tab active" onclick="filterParametersByStatus('all')">All Categories</div>
                        <div class="filter-tab" onclick="filterParametersByStatus('with-params')">With Parameters</div>
                        <div class="filter-tab" onclick="filterParametersByStatus('empty')">Empty Only</div>
                    </div>
                </div>
            </div>
            
            <div class="main-table-wrapper" id="parametersTableWrapper">
                <table class="main-table" id="parametersTable">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Code</th>
                            <th style="width: 25%;">Category Name</th>
                            <th style="width: 40%;">Description</th>
                            <th style="width: 10%; text-align: center;">Parameters</th>
                            <th style="width: 15%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="parametersTableBody">
                        <?php foreach ($categories as $category): 
                            $paramCount = $param_counts[$category['id']] ?? 0;
                        ?>
                        <tr data-status="<?php echo $paramCount > 0 ? 'with-params' : 'empty'; ?>"
                            data-search="<?php echo strtolower($category['name'] . ' ' . $category['code'] . ' ' . $category['description']); ?>">
                            
                            <td>
                                <span class="test-code-badge"><?php echo htmlspecialchars($category['code']); ?></span>
                            </td>
                            
                            <td class="test-name-col">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </td>
                            
                            <td class="description-col">
                                <?php echo htmlspecialchars($category['description']); ?>
                            </td>
                            
                            <td style="text-align: center;">
                                <span class="count-badge <?php echo $paramCount > 0 ? 'has-params' : 'no-params'; ?>">
                                    <?php echo $paramCount; ?> test<?php echo $paramCount != 1 ? 's' : ''; ?>
                                </span>
                            </td>
                            
                            <td class="actions-col" style="text-align: center;">
                                <?php if ($paramCount > 0): ?>
                                <button class="btn btn-manage" onclick="viewParameters(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars(addslashes($category['name'])); ?>')">
                                    📋 Manage
                                </button>
                                <?php endif; ?>
                                <button class="btn btn-add" onclick="addParameter(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars(addslashes($category['name'])); ?>')">
                                    ➕ <?php echo $paramCount > 0 ? 'Add' : 'Add First'; ?>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Edit Test Modal -->
    <div id="editTestModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title">✏️ Edit Test Details</h3>
                <button type="button" class="close-modal" onclick="closeEditTestModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editTestForm" onsubmit="saveTestEdit(event)">
                    <input type="hidden" id="editTestId">
                    
                    <div class="form-group">
                        <label>Test Code *</label>
                        <input type="text" id="editCode" required placeholder="e.g., CBC, LFT">
                    </div>
                    
                    <div class="form-group">
                        <label>Test Name *</label>
                        <input type="text" id="editName" required placeholder="e.g., Complete Blood Count">
                    </div>
                    
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea id="editDescription" rows="3" required placeholder="Detailed description of the test"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-save">💾 Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Parameter Modal -->
    <div id="addParameterModal" class="modal">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="addParamTitle">➕ Add Test Parameter</h3>
                    <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">Configure individual test with reference ranges and default comments</p>
                </div>
                <button type="button" class="close-modal" onclick="closeAddParameterModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addParameterForm" onsubmit="saveParameter(event)">
                    <input type="hidden" id="paramCategoryId">
                    <input type="hidden" id="paramId">
                    
                    <!-- Test Basic Info Section -->
                    <div style="background: #f5f5f5; padding: 12px; border-radius: 4px; margin-bottom: 15px;">
                        <div style="font-weight: 600; color: #333; margin-bottom: 12px; font-size: 13px;">
                            Test Parameter Details
                        </div>
                        
                        <div class="form-group">
                            <label>Test Name *</label>
                            <input type="text" id="paramTestName" required placeholder="e.g., Hemoglobin">
                        </div>
                        
                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" id="paramUnit" placeholder="e.g., g/dL">
                        </div>
                    </div>
                    
                    <!-- Reference Range Section -->
                    <div style="background: #f5f5f5; padding: 12px; border-radius: 4px; margin-bottom: 15px;">
                        <div style="font-weight: 600; color: #333; margin-bottom: 12px; font-size: 13px;">
                            Reference Range
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label>Min Value</label>
                                <input type="text" id="paramMinValue" placeholder="e.g., 12.0">
                            </div>
                            
                            <div class="form-group">
                                <label>Max Value</label>
                                <input type="text" id="paramMaxValue" placeholder="e.g., 16.0">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" id="paramShowInPdf" checked style="width: 16px; height: 16px; cursor: pointer;">
                                <span>Show reference range in PDF reports</span>
                            </label>
                        </div>
                    </div>
                    
                    </div>
                    
                    <button type="submit" class="btn-save" style="font-size: 15px;">Save Test Parameter</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- View Parameters Modal -->
    <div id="viewParametersModal" class="modal">
        <div class="modal-content" style="max-width: 1200px;">
            <div class="modal-header">
                <h3 class="modal-title" id="viewParamsTitle">📋 Test Parameters</h3>
                <button type="button" class="close-modal" onclick="closeViewParametersModal()">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Updated to Table View v2.0 -->
                <div id="parametersList"></div>
            </div>
        </div>
    </div>
    
    <script>
        let currentTestFilter = 'all';
        let currentParamFilter = 'all';
        let bulkModeEnabled = false;
        let selectedTests = new Set();
        
        // View Switching
        function switchView(view) {
            document.querySelectorAll('.view-panel').forEach(panel => panel.classList.remove('active'));
            document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
            
            if (view === 'tests') {
                document.getElementById('testsView').classList.add('active');
                document.getElementById('btnTestsView').classList.add('active');
            } else {
                document.getElementById('parametersView').classList.add('active');
                document.getElementById('btnParametersView').classList.add('active');
            }
        }
        
        // Tests Management
        function filterTests() {
            const searchTerm = document.getElementById('searchTests').value.toLowerCase();
            const rows = document.querySelectorAll('#testsTableBody tr');
            
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                const status = row.getAttribute('data-status');
                
                const matchesSearch = searchData.includes(searchTerm);
                const matchesFilter = currentTestFilter === 'all' || status === currentTestFilter;
                
                row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
            });
        }
        
        function filterTestsByStatus(status) {
            currentTestFilter = status;
            
            document.querySelectorAll('#testsView .filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            filterTests();
        }
        
        function toggleBulkMode() {
            bulkModeEnabled = document.getElementById('toggleBulkMode').checked;
            const checkboxes = document.querySelectorAll('#testsTableBody .test-checkbox');
            const bulkSelectAll = document.getElementById('bulkSelectAll');
            const bulkActions = document.getElementById('bulkActions');
            
            checkboxes.forEach(checkbox => {
                checkbox.style.display = bulkModeEnabled ? 'inline-block' : 'none';
            });
            
            bulkSelectAll.style.display = bulkModeEnabled ? 'inline-block' : 'none';
            bulkActions.style.display = bulkModeEnabled ? 'flex' : 'none';
            
            if (!bulkModeEnabled) {
                clearSelection();
            }
        }
        
        function updateSelection() {
            selectedTests.clear();
            document.querySelectorAll('.test-checkbox:checked').forEach(cb => {
                selectedTests.add(cb.value);
                cb.closest('tr').style.background = '#e3f2fd';
            });
            
            document.querySelectorAll('.test-checkbox:not(:checked)').forEach(cb => {
                const tr = cb.closest('tr');
                if (tr.classList.contains('inactive')) {
                    tr.style.background = '';
                } else {
                    tr.style.background = '';
                }
            });
            
            document.getElementById('selectedCount').textContent = selectedTests.size;
        }
        
        function selectAllTests() {
            document.querySelectorAll('#testsTableBody tr:not([style*="display: none"]) .test-checkbox').forEach(cb => {
                cb.checked = true;
            });
            updateSelection();
        }
        
        function clearSelection() {
            document.querySelectorAll('.test-checkbox').forEach(cb => {
                cb.checked = false;
            });
            updateSelection();
        }
        
        function bulkActivate() {
            if (selectedTests.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one test',
                    confirmButtonColor: '#5a67d8'
                });
                return;
            }
            
            Swal.fire({
                title: 'Activate Tests?',
                text: `Activate ${selectedTests.size} selected test(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#26de81',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, activate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkUpdateStatus(1);
                }
            });
        }
        
        function bulkDeactivate() {
            if (selectedTests.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one test',
                    confirmButtonColor: '#5a67d8'
                });
                return;
            }
            
            Swal.fire({
                title: 'Deactivate Tests?',
                text: `Deactivate ${selectedTests.size} selected test(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, deactivate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkUpdateStatus(0);
                }
            });
        }
        
        function bulkUpdateStatus(status) {
            const formData = new FormData();
            formData.append('action', 'bulk_update');
            formData.append('test_ids', Array.from(selectedTests).join(','));
            formData.append('status', status);
            
            fetch('ajax_manage_tests.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: `Tests ${status ? 'activated' : 'deactivated'} successfully`,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Unknown error occurred',
                        confirmButtonColor: '#5a67d8'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating tests',
                    confirmButtonColor: '#5a67d8'
                });
            });
        }
        
        function toggleTestStatus(id, currentStatus) {
            const newStatus = currentStatus ? 0 : 1;
            const action = newStatus ? 'activate' : 'deactivate';
            
            Swal.fire({
                title: `${action.charAt(0).toUpperCase() + action.slice(1)} Test?`,
                text: `Are you sure you want to ${action} this test?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: newStatus ? '#26de81' : '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${action}!`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'toggle_status');
                    formData.append('id', id);
                    formData.append('status', newStatus);
                    
                    fetch('ajax_manage_tests.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: `Test ${action}d successfully`,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'Unknown error occurred',
                                confirmButtonColor: '#5a67d8'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating test',
                            confirmButtonColor: '#5a67d8'
                        });
                    });
                }
            });
        }
        
        function openEditTestModal(button) {
            const id = button.getAttribute('data-id');
            const code = button.getAttribute('data-code');
            const name = button.getAttribute('data-name');
            const description = button.getAttribute('data-description');
            
            document.getElementById('editTestId').value = id;
            document.getElementById('editCode').value = code;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editTestModal').style.display = 'block';
        }
        
        function closeEditTestModal() {
            document.getElementById('editTestModal').style.display = 'none';
        }
        
        function saveTestEdit(event) {
            event.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'update_test');
            formData.append('id', document.getElementById('editTestId').value);
            formData.append('code', document.getElementById('editCode').value);
            formData.append('name', document.getElementById('editName').value);
            formData.append('description', document.getElementById('editDescription').value);
            
            fetch('ajax_manage_tests.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Test category updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Unknown error occurred',
                        confirmButtonColor: '#5a67d8'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating test',
                    confirmButtonColor: '#5a67d8'
                });
            });
        }
        
        // Parameters Management
        function filterParameters() {
            const searchTerm = document.getElementById('searchParameters').value.toLowerCase();
            const rows = document.querySelectorAll('#parametersTableBody tr');
            
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                const status = row.getAttribute('data-status');
                
                const matchesSearch = searchData.includes(searchTerm);
                const matchesFilter = currentParamFilter === 'all' || status === currentParamFilter;
                
                row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
            });
        }
        
        function filterParametersByStatus(status) {
            currentParamFilter = status;
            
            document.querySelectorAll('#parametersView .filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            filterParameters();
        }
        
        function addParameter(categoryId, categoryName) {
            document.getElementById('paramCategoryId').value = categoryId;
            document.getElementById('paramId').value = '';
            document.getElementById('addParamTitle').textContent = `➕ Add Parameter to ${categoryName}`;
            document.getElementById('addParameterForm').reset();
            document.getElementById('paramCategoryId').value = categoryId; // Restore after reset
            document.getElementById('paramShowInPdf').checked = true;
            document.getElementById('addParameterModal').style.display = 'block';
        }
        
        function closeAddParameterModal() {
            document.getElementById('addParameterModal').style.display = 'none';
        }
        
        function saveParameter(event) {
            event.preventDefault();
            
            const formData = new FormData();
            const paramId = document.getElementById('paramId').value;
            
            formData.append('action', paramId ? 'update' : 'add');
            if (paramId) formData.append('id', paramId);
            formData.append('category_id', document.getElementById('paramCategoryId').value);
            formData.append('test_name', document.getElementById('paramTestName').value);
            formData.append('unit', document.getElementById('paramUnit').value);
            formData.append('min_value', document.getElementById('paramMinValue').value);
            formData.append('max_value', document.getElementById('paramMaxValue').value);
            formData.append('show_in_pdf', document.getElementById('paramShowInPdf').checked ? 1 : 0);
            
            fetch('save_test_parameter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: paramId ? 'Updated!' : 'Added!',
                        text: `Parameter ${paramId ? 'updated' : 'added'} successfully`,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        closeAddParameterModal();
                       // location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Unknown error occurred',
                        confirmButtonColor: '#5a67d8'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while saving parameter',
                    confirmButtonColor: '#5a67d8'
                });
            });
        }
        
        function viewParameters(categoryId, categoryName) {
            document.getElementById('viewParamsTitle').textContent = `📋 ${categoryName} - Parameters`;
            
            // Add cache busting timestamp
            const timestamp = new Date().getTime();
            fetch(`get_test_parameters.php?category_id=${categoryId}&_=${timestamp}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayParameters(data.parameters, categoryId, categoryName);
                        document.getElementById('viewParametersModal').style.display = 'block';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error loading parameters',
                            confirmButtonColor: '#5a67d8'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while loading parameters',
                        confirmButtonColor: '#5a67d8'
                    });
                });
        }
        
        function displayParameters(parameters, categoryId, categoryName) {
            const container = document.getElementById('parametersList');
            
            console.log('Displaying parameters - Table view version 2.0', parameters.length);
            
            if (parameters.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px;">
                        <div style="font-size: 64px; opacity: 0.3; margin-bottom: 20px;">📋</div>
                        <p style="color: #999; font-size: 16px; margin-bottom: 20px;">No parameters configured yet</p>
                        <button class="add-param-btn" onclick="addParameter(${categoryId}, '${categoryName}')">
                            ➕ Add First Parameter
                        </button>
                    </div>
                `;
                return;
            }
            
            // Header with count and add button
            let html = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div style="color: #666; font-size: 13px; font-weight: 600;">
                        <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 5px 12px; border-radius: 15px; font-size: 12px;">
                            ${parameters.length} Parameter${parameters.length !== 1 ? 's' : ''}
                        </span>
                    </div>
                    <button class="add-param-btn" onclick="addParameter(${categoryId}, '${categoryName}')" style="padding: 10px 20px; font-size: 13px;">
                        ➕ Add New Parameter
                    </button>
                </div>
                
                <div class="param-table-wrapper" style="margin-bottom: 15px;">
                    <table class="param-table">
                        <thead>
                            <tr>
                                <th style="width: 4%; text-align: center; padding: 12px 8px;">#</th>
                                <th style="width: 30%; padding: 12px 10px;">Test Name</th>
                                <th style="width: 8%; text-align: center; padding: 12px 8px;">Unit</th>
                                <th style="width: 18%; padding: 12px 10px;">Reference Range</th>
                                <th style="width: 10%; text-align: center; padding: 12px 8px;">PDF</th>
                                <th style="width: 30%; text-align: center; padding: 12px 8px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            parameters.forEach((param, index) => {
                // Build reference range display
                let rangeDisplay = '';
                if (param.min_value || param.max_value) {
                    const minVal = param.min_value || '...';
                    const maxVal = param.max_value || '...';
                    rangeDisplay = `<span class="range-value" style="padding: 3px 8px; font-size: 12px;">${minVal} - ${maxVal}</span>`;
                } else {
                    rangeDisplay = '<span style="color: #bbb; font-style: italic; font-size: 12px;">Not set</span>';
                }
                
                // Unit display
                const unitDisplay = param.unit 
                    ? `<strong style="font-size: 13px;">${escapeHtml(param.unit)}</strong>` 
                    : '<span style="color: #bbb;">-</span>';
                
                // PDF visibility
                const showInPdf = param.show_in_pdf == 1;
                const pdfBadge = showInPdf 
                    ? '<span class="param-badge badge-visible" style="padding: 4px 10px; font-size: 11px;">✓</span>'
                    : '<span class="param-badge badge-hidden" style="padding: 4px 10px; font-size: 11px;">✗</span>';
                
                html += `
                    <tr>
                        <td class="param-row-number" style="padding: 10px 8px; font-size: 12px;">${index + 1}</td>
                        <td class="param-name-cell" style="padding: 10px; font-size: 14px;">
                            ${escapeHtml(param.test_name)}
                        </td>
                        <td class="param-unit-cell" style="padding: 10px 8px;">${unitDisplay}</td>
                        <td class="param-range-cell" style="padding: 10px;">${rangeDisplay}</td>
                        <td style="text-align: center; padding: 10px 8px;">${pdfBadge}</td>
                        <td class="param-actions-cell" style="padding: 10px 8px;">
                            <button class="btn-small btn-edit-small" onclick='editParameter(${JSON.stringify(param)}, "${escapeHtml(categoryName)}")' title="Edit parameter" style="padding: 6px 12px; font-size: 11px;">
                                ✏️ Edit
                            </button>
                            <button class="btn-small btn-delete-small" onclick="deleteParameter(${param.id})" title="Delete parameter" style="padding: 6px 12px; font-size: 11px;">
                                🗑️ Delete
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
                
                <!-- Category-Level Comment Box -->
                <div style="background: #fff8e1; padding: 12px; border-radius: 4px; border-left: 3px solid #ffc107; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label style="font-weight: 600; color: #333; margin: 0; font-size: 13px;">
                            Category Comment for ${escapeHtml(categoryName)}
                        </label>
                        <button onclick="saveCategoryComment(${categoryId})" class="btn btn-edit" style="padding: 6px 12px; font-size: 12px;">
                            Save Comment
                        </button>
                    </div>
                    <textarea id="categoryComment_${categoryId}" rows="2" 
                              style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; font-family: inherit; resize: vertical;"
                              placeholder="Enter a general comment for all ${escapeHtml(categoryName)} tests."></textarea>
                    <div style="margin-top: 6px; font-size: 11px; color: #666;">
                        This comment applies to the entire category and will appear in all reports.
                    </div>
                </div>
                
               
            `;
            
            container.innerHTML = html;
            
            // Load existing category comment if any
            loadCategoryComment(categoryId);
        }
        
        function loadCategoryComment(categoryId) {
            // TODO: Fetch category comment from backend
            // For now, just a placeholder
            fetch(`get_category_comment.php?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.comment) {
                        document.getElementById(`categoryComment_${categoryId}`).value = data.comment;
                    }
                })
                .catch(error => {
                    console.log('No category comment found or error loading:', error);
                });
        }
        
        function saveCategoryComment(categoryId) {
            const comment = document.getElementById(`categoryComment_${categoryId}`).value;
            
            const formData = new FormData();
            formData.append('action', 'save_category_comment');
            formData.append('category_id', categoryId);
            formData.append('comment', comment);
            
            fetch('save_category_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: 'Category comment saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Unknown error occurred',
                        confirmButtonColor: '#5a67d8'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while saving',
                    confirmButtonColor: '#5a67d8'
                });
            });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function editParameter(param, categoryName) {
            document.getElementById('paramId').value = param.id;
            document.getElementById('paramCategoryId').value = param.category_id;
            document.getElementById('paramTestName').value = param.test_name;
            document.getElementById('paramUnit').value = param.unit || '';
            document.getElementById('paramMinValue').value = param.min_value || '';
            document.getElementById('paramMaxValue').value = param.max_value || '';
            document.getElementById('paramShowInPdf').checked = param.show_in_pdf == 1;
            document.getElementById('addParamTitle').textContent = `Edit Parameter - ${categoryName}`;
            
            closeViewParametersModal();
            document.getElementById('addParameterModal').style.display = 'block';
        }
        
        function deleteParameter(id) {
            Swal.fire({
                title: 'Delete Parameter?',
                text: 'Are you sure you want to delete this parameter? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('id', id);
                    
                    fetch('save_test_parameter.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Parameter deleted successfully',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'Unknown error occurred',
                                confirmButtonColor: '#5a67d8'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting parameter',
                            confirmButtonColor: '#5a67d8'
                        });
                    });
                }
            });
        }
        
        function closeViewParametersModal() {
            document.getElementById('viewParametersModal').style.display = 'none';
        }
        
        // Close modals on outside click
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
