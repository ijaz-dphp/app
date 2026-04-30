<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

// Helper function to get default datetime values with random times within ranges
function getDefaultDateTime($minHour, $minMinute, $maxHour, $maxMinute) {
    $today = date('Y-m-d');
    
    // Convert times to minutes for easier calculation
    $minTotalMinutes = $minHour * 60 + $minMinute;
    $maxTotalMinutes = $maxHour * 60 + $maxMinute;
    
    // Generate random time within range
    $randomTotalMinutes = rand($minTotalMinutes, $maxTotalMinutes);
    $hour = intdiv($randomTotalMinutes, 60);
    $minute = $randomTotalMinutes % 60;
    
    return $today . 'T' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT);
}

// Helper function to get editable datetime (shows saved value if exists, otherwise empty for user input)
function getEditableDateTime($dateValue) {
    if ($dateValue) {
        return date('Y-m-d\TH:i', strtotime($dateValue));
    }
    return '';
}

$report_id = $_GET['id'] ?? 0;

$db = new Database();
$conn = $db->getConnection();

// Get report details
$stmt = $conn->prepare("
    SELECT r.*, p.name as patient_name, p.mrn,
           tc.name as category_name, tc.code as category_code, tc.description as test_description
    FROM reports r 
    JOIN patients p ON r.patient_id = p.id 
    JOIN test_categories tc ON r.category_id = tc.id 
    WHERE r.id = :id
");
$stmt->execute(['id' => $report_id]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    die('Report not found!');
}

// Get test results - show all test parameters for this category
$stmt = $conn->prepare("
    SELECT tp.id as parameter_id, tp.test_name, tp.min_value, tp.max_value, tp.unit, tp.display_order,
           COALESCE(rr.id, NULL) as result_id,
           COALESCE(rr.result_value, '') as result_value,
           COALESCE(rr.is_abnormal, 0) as is_abnormal
    FROM test_parameters tp
    LEFT JOIN report_results rr ON rr.parameter_id = tp.id AND rr.report_id = :id
    WHERE tp.category_id = :category_id
    ORDER BY tp.display_order
");
$stmt->execute(['id' => $report_id, 'category_id' => $report['category_id']]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Report - <?php echo htmlspecialchars($report['patient_name']); ?></title>
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
            max-width: 1000px;
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
        
        .back-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
            margin-right: 15px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .form-container {
            background: white;
            border-radius: 6px;
            padding: 25px;
            border: 1px solid #e0e0e0;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #5a67d8;
        }
        
        .form-section {
            margin-bottom: 25px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-row.full {
            grid-template-columns: 1fr;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        label {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            font-size: 13px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="datetime-local"],
        input[type="number"],
        select,
        textarea {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="datetime-local"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #5a67d8;
            box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .patient-info {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        .patient-info strong {
            color: #333;
        }
        
        .patient-info span {
            color: #666;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .results-table thead {
            background: #f5f5f5;
        }
        
        .results-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .results-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .results-table input[type="text"],
        .results-table input[type="number"] {
            width: 100%;
        }
        
        .checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .reference-range {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            flex-direction: row-reverse;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-save {
            background: #28a745;
            color: white;
        }
        
        .btn-save:hover {
            background: #218838;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .alert-info {
            background: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            background: #f0f0f0;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column-reverse;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="header">
            <a href="reports_list.php" class="back-link">← Back to Reports</a>
            <h1>Edit Medical Report</h1>
            <p>Modify report details and test results</p>
        </div>
        
        <div class="form-container">
            <div class="alert alert-info">
                ℹ️ You can edit report details and test results. Save your changes when complete.
            </div>
            
            <form id="editForm" method="POST" action="save_report.php">
                <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                
                <!-- Patient Information Section -->
                <div class="form-section">
                    <h3 class="section-title">Patient Information</h3>
                    
                    <div class="patient-info">
                        <strong>Name:</strong> <span><?php echo htmlspecialchars($report['patient_name']); ?></span>
                        <br>
                        <strong>MRN:</strong> <span><?php echo htmlspecialchars($report['mrn']); ?></span>
                        <br>
                        <strong>Test Type:</strong> <span><?php echo htmlspecialchars($report['test_description']); ?> (<?php echo htmlspecialchars($report['category_code']); ?>)</span>
                    </div>
                </div>
                
                <!-- Report Details Section -->
                <div class="form-section">
                    <h3 class="section-title">Report Details</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="request_date">Request Date & Time</label>
                            <input type="datetime-local" 
                                   id="request_date" 
                                   name="request_date" 
                                   value="<?php echo getDefaultDateTime(9, 0, 10, 0); ?>">
                            <small style="color: #999; margin-top: 4px;">Default: 9:00 AM - 10:00 AM</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="performed_date">Performed Date & Time</label>
                            <input type="datetime-local" 
                                   id="performed_date" 
                                   name="performed_date" 
                                   value="<?php echo getDefaultDateTime(11, 25, 12, 30); ?>">
                            <small style="color: #999; margin-top: 4px;">Default: 11:25 AM - 12:30 PM</small>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="published_date">Published Date & Time</label>
                            <input type="datetime-local" 
                                   id="published_date" 
                                   name="published_date" 
                                   value="<?php echo getDefaultDateTime(12, 40, 13, 10); ?>">
                            <small style="color: #999; margin-top: 4px;">Default: 12:40 PM - 1:10 PM</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="pending" <?php echo $report['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="in_progress" <?php echo $report['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="completed" <?php echo $report['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" 
                                   id="department" 
                                   name="department" 
                                   value="<?php echo htmlspecialchars($report['department'] ?? ''); ?>"
                                   placeholder="Enter department">
                        </div>
                        
                        <div class="form-group">
                            <label for="ward">Ward</label>
                            <input type="text" 
                                   id="ward" 
                                   name="ward" 
                                   value="<?php echo htmlspecialchars($report['ward'] ?? ''); ?>"
                                   placeholder="Enter ward">
                        </div>
                    </div>
                    
                    <div class="form-row full">
                        <div class="form-group">
                            <label for="verified_by">Verified By</label>
                            <input type="text" 
                                   id="verified_by" 
                                   name="verified_by" 
                                   value="<?php echo htmlspecialchars($report['verified_by'] ?? ''); ?>"
                                   placeholder="Enter name of person who verified the report">
                        </div>
                    </div>
                    
                    <div class="form-row full">
                        <div class="form-group">
                            <label for="printed_date">Printed Date & Time</label>
                            <input type="datetime-local" 
                                   id="printed_date" 
                                   name="printed_date" 
                                   value="<?php echo getEditableDateTime($report['printed_date'] ?? ''); ?>">
                            <small style="color: #999; margin-top: 4px;">Auto-generated range: 1:20 PM - 2:30 PM (or set custom time)</small>
                        </div>
                    </div>
                </div>
                
                <!-- Test Results Section -->
                <div class="form-section">
                    <h3 class="section-title">Test Results</h3>
                    
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Result Value</th>
                                <th>Unit</th>
                                <th>Reference Range</th>
                                <th>Abnormal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($result['test_name']); ?></strong>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="result_values[<?php echo $result['parameter_id']; ?>]" 
                                               value="<?php echo htmlspecialchars($result['result_value']); ?>"
                                               placeholder="Enter result">
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($result['unit']); ?>
                                    </td>
                                    <td>
                                        <div class="reference-range">
                                            <?php 
                                            if ($result['min_value'] && $result['max_value']) {
                                                echo htmlspecialchars($result['min_value']) . ' - ' . htmlspecialchars($result['max_value']);
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" 
                                                   id="abnormal_<?php echo $result['parameter_id']; ?>"
                                                   name="abnormal_flags[<?php echo $result['parameter_id']; ?>]" 
                                                   value="1"
                                                   <?php echo $result['is_abnormal'] ? 'checked' : ''; ?>>
                                            <label for="abnormal_<?php echo $result['parameter_id']; ?>" style="margin: 0;">Mark as abnormal</label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Button Group -->
                <div class="button-group">
                    <button type="submit" class="btn btn-save">💾 Save Changes</button>
                    <a href="reports_list.php" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('editForm').addEventListener('submit', function(e) {
            // Basic validation
            const requestDate = document.getElementById('request_date').value;
            const performedDate = document.getElementById('performed_date').value;
            const publishedDate = document.getElementById('published_date').value;
            
            // If dates are provided, validate order
            if (requestDate && performedDate && new Date(publishedDate) < new Date(requestDate)) {
                alert('Published date cannot be before request date');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>
