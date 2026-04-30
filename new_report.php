<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

$category_id = $_GET['category'] ?? 1;

// Get category details including category comment
$stmt = $conn->prepare("SELECT * FROM test_categories WHERE id = :id");
$stmt->execute(['id' => $category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Get category comment for auto-fill
$category_comment = $category['category_comment'] ?? '';

// Get test parameters
$stmt = $conn->prepare("SELECT * FROM test_parameters WHERE category_id = :id ORDER BY display_order");
$stmt->execute(['id' => $category_id]);
$parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conn->beginTransaction();
        
        // Insert or get patient
        $mrn = $_POST['mrn'];
        $stmt = $conn->prepare("SELECT id FROM patients WHERE mrn = :mrn");
        $stmt->execute(['mrn' => $mrn]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$patient) {
            $stmt = $conn->prepare("INSERT INTO patients (mrn, name, contact, age, gender, father_husband_name) 
                                   VALUES (:mrn, :name, :contact, :age, :gender, :fh_name)");
            $stmt->execute([
                'mrn' => $mrn,
                'name' => $_POST['patient_name'],
                'contact' => $_POST['contact'],
                'age' => $_POST['age'],
                'gender' => $_POST['gender'],
                'fh_name' => $_POST['fh_name']
            ]);
            $patient_id = $conn->lastInsertId();
        } else {
            $patient_id = $patient['id'];
        }
        
        // Insert report
        $stmt = $conn->prepare("INSERT INTO reports (patient_id, category_id, request_date, performed_date, published_date, department, verified_by, comments, status, created_by) 
                               VALUES (:patient_id, :category_id, :request_date, :performed_date, :published_date, :department, :verified_by, :comments, 'completed', :created_by)");
        $stmt->execute([
            'patient_id' => $patient_id,
            'category_id' => $category_id,
            'request_date' => $_POST['request_date'],
            'performed_date' => $_POST['performed_date'],
            'published_date' => $_POST['published_date'],
            'department' => $_POST['department'],
            'verified_by' => $_POST['verified_by'],
            'comments' => $_POST['comments'] ?? '',
            'created_by' => $_SESSION['user_id']
        ]);
        $report_id = $conn->lastInsertId();
        
        // Insert results
        foreach ($parameters as $param) {
            $result_value = $_POST['param_' . $param['id']] ?? '';
            
            // Check if abnormal
            $is_abnormal = 0;
            if ($result_value !== '') {
                $numeric_value = floatval($result_value);
                if ($param['min_value'] !== '' && $numeric_value < floatval($param['min_value'])) {
                    $is_abnormal = 1;
                }
                if ($param['max_value'] !== '' && strpos($param['max_value'], '<') === false && $numeric_value > floatval($param['max_value'])) {
                    $is_abnormal = 1;
                }
            }
            
            $stmt = $conn->prepare("INSERT INTO report_results (report_id, parameter_id, result_value, is_abnormal) 
                                   VALUES (:report_id, :parameter_id, :result_value, :is_abnormal)");
            $stmt->execute([
                'report_id' => $report_id,
                'parameter_id' => $param['id'],
                'result_value' => $result_value,
                'is_abnormal' => $is_abnormal
            ]);
        }
        
        $conn->commit();
        
        // Redirect to PDF generation
        header('Location: generate_pdf.php?id=' . $report_id);
        exit;
        
    } catch (Exception $e) {
        $conn->rollBack();
        $error = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Report - <?php echo htmlspecialchars($category['description']); ?></title>
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
            font-size: 24px;
            font-weight: bold;
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
        
        .btn-light {
            background: white;
            color: #667eea;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-success {
            background: #26de81;
            color: white;
            padding: 15px 40px;
            font-size: 16px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }
        
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .test-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .test-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .test-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .test-table tr:hover {
            background: #f8f9fa;
        }
        
        .test-table input.text-input,
        .test-table select.dropdown-input {
            width: 100%;
            padding: 8px;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .test-table select.dropdown-input {
            background: white;
            cursor: pointer;
            font-weight: 500;
        }
        
        .test-table select.dropdown-input:focus,
        .test-table input.text-input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .submit-section {
            text-align: center;
            margin-top: 30px;
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Patient Information -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">👤 Patient Information</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>MRN *</label>
                        <input type="text" id="mrn" name="mrn" required autocomplete="off" onkeyup="searchPatient(this.value)">
                        <div id="patientSuggestions" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; width: calc(100% - 20px); z-index: 1000; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
                    </div>
                    <div class="form-group">
                        <label>Patient Name *</label>
                        <input type="text" id="patient_name" name="patient_name" required>
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" id="contact" name="contact">
                    </div>
                    <div class="form-group">
                        <label>Age *</label>
                        <input type="text" id="age" name="age" placeholder="e.g., 28Y 8M 16D" required>
                    </div>
                    <div class="form-group">
                        <label>Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Father/Husband Name</label>
                        <input type="text" id="fh_name" name="fh_name">
                    </div>
                </div>
            </div>
            
            <!-- Test Information -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">🧪 Test Information - <?php echo htmlspecialchars($category['description']); ?></h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" value="OPD">
                    </div>
                    <div class="form-group">
                        <label>Request Date *</label>
                        <input type="datetime-local" name="request_date" required value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Performed Date *</label>
                        <input type="datetime-local" name="performed_date" required value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Published Date *</label>
                        <input type="datetime-local" name="published_date" required value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Verified By</label>
                        <input type="text" name="verified_by" value="Dr Mehreen Naseer">
                    </div>
                </div>
            </div>
            
            <!-- Test Parameters -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">📊 Test Parameters</h2>
                </div>
                <table class="test-table">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Reference Range</th>
                            <th>Unit</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parameters as $param): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($param['test_name']); ?></strong></td>
                                <td>
                                    <?php 
                                    $range = '';
                                    if ($param['min_value'] !== '') $range .= $param['min_value'];
                                    if ($param['min_value'] !== '' && $param['max_value'] !== '') $range .= ' - ';
                                    if ($param['max_value'] !== '') $range .= $param['max_value'];
                                    echo $range ?: '-';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($param['unit']); ?></td>
                                <td>
                                    <?php
                                    // Check if this is a Positive/Negative or Detected/Not Detected test
                                    $isDropdown = false;
                                    $dropdownOptions = [];
                                    
                                    if (stripos($param['min_value'], 'Negative') !== false || 
                                        stripos($param['max_value'], 'Negative') !== false) {
                                        $isDropdown = true;
                                        $dropdownOptions = ['Negative', 'Positive'];
                                    } elseif (stripos($param['min_value'], 'Not Detected') !== false || 
                                              stripos($param['max_value'], 'Not Detected') !== false) {
                                        $isDropdown = true;
                                        $dropdownOptions = ['Not Detected', 'Detected'];
                                    } elseif (stripos($param['min_value'], 'Clear') !== false && 
                                              stripos($param['test_name'], 'Appearance') !== false) {
                                        $isDropdown = true;
                                        $dropdownOptions = ['Clear', 'Turbid', 'Hazy', 'Cloudy'];
                                    } elseif (stripos($param['test_name'], 'Color') !== false && 
                                              stripos($param['min_value'], 'Yellow') !== false) {
                                        $isDropdown = true;
                                        $dropdownOptions = ['Pale Yellow', 'Yellow', 'Dark Yellow', 'Amber', 'Red', 'Brown'];
                                    } elseif (stripos($param['min_value'], 'Normal') !== false || 
                                              stripos($param['max_value'], 'Normal') !== false) {
                                        $isDropdown = true;
                                        $dropdownOptions = ['Normal', 'Abnormal'];
                                    } elseif (stripos($param['min_value'], 'Nil') !== false || 
                                              stripos($param['max_value'], 'Nil') !== false) {
                                        $isDropdown = true;
                                        $dropdownOptions = ['Nil', 'Few', 'Moderate', 'Many'];
                                    } elseif (stripos($param['min_value'], 'Few') !== false || 
                                              stripos($param['max_value'], 'Few') !== false) {
                                        $isDropdown = true;
                                        $dropdownOptions = ['Nil', 'Few', 'Moderate', 'Many'];
                                    }
                                    
                                    if ($isDropdown): ?>
                                        <select name="param_<?php echo $param['id']; ?>" class="dropdown-input">
                                            <option value="">Select</option>
                                            <?php foreach ($dropdownOptions as $option): ?>
                                                <option value="<?php echo htmlspecialchars($option); ?>">
                                                    <?php echo htmlspecialchars($option); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" name="param_<?php echo $param['id']; ?>" placeholder="Enter result" class="text-input">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Comments Section - Only show if category comment exists -->
            <?php if (!empty($category_comment)): ?>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">💬 Comments</h2>
                </div>
                <div style="padding: 20px;">
                    <textarea name="comments" rows="3" placeholder="e.g., The test is performed on semi automated GENTIER 96 PCR analyzer" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px; font-family: inherit;"><?php echo htmlspecialchars($category_comment); ?></textarea>
                </div>
            </div>
            <?php else: ?>
            <!-- Hidden comment field when no category comment -->
            <input type="hidden" name="comments" value="">
            <?php endif; ?>
            
            <div class="submit-section">
                <button type="submit" class="btn btn-success">📄 Generate PDF Report</button>
            </div>
        </form>
    </div>
    
    <script>
    let searchTimeout;
    
    function searchPatient(mrn) {
        clearTimeout(searchTimeout);
        
        const suggestionsDiv = document.getElementById('patientSuggestions');
        
        if (mrn.length < 2) {
            suggestionsDiv.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch('search_patient.php?mrn=' + encodeURIComponent(mrn))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.patients.length > 0) {
                        let html = '';
                        data.patients.forEach(patient => {
                            html += `
                                <div onclick="fillPatientData(${JSON.stringify(patient).replace(/"/g, '&quot;')})" 
                                     style="padding: 10px; cursor: pointer; border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                                    <div style="font-weight: 600; color: #333;">${patient.mrn} - ${patient.name}</div>
                                    <div style="font-size: 12px; color: #666;">${patient.age} | ${patient.gender} | ${patient.contact || 'No contact'}</div>
                                </div>
                            `;
                        });
                        suggestionsDiv.innerHTML = html;
                        suggestionsDiv.style.display = 'block';
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    suggestionsDiv.style.display = 'none';
                });
        }, 300);
    }
    
    function fillPatientData(patient) {
        document.getElementById('mrn').value = patient.mrn;
        document.getElementById('patient_name').value = patient.name;
        document.getElementById('contact').value = patient.contact || '';
        document.getElementById('age').value = patient.age;
        document.getElementById('gender').value = patient.gender;
        document.getElementById('fh_name').value = patient.father_husband_name || '';
        
        document.getElementById('patientSuggestions').style.display = 'none';
    }
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(event) {
        const suggestionsDiv = document.getElementById('patientSuggestions');
        const mrnInput = document.getElementById('mrn');
        
        if (event.target !== mrnInput && event.target !== suggestionsDiv) {
            suggestionsDiv.style.display = 'none';
        }
    });
    
    // Add hover effect
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.textContent = `
            #patientSuggestions > div:hover {
                background-color: #f5f5f5 !important;
            }
        `;
        document.head.appendChild(style);
    });
    </script>
</body>
</html>
