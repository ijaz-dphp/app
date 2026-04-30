<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Get all active parent test categories, sorted alphabetically
$stmt = $conn->query("SELECT * FROM test_categories WHERE is_active = 1 AND parent_category_id IS NULL ORDER BY code ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                'fh_name' => $_POST['fh_name'] ?? ''
            ]);
            $patient_id = $conn->lastInsertId();
        } else {
            $patient_id = $patient['id'];
            // Update patient info
            $stmt = $conn->prepare("UPDATE patients SET name = :name, contact = :contact, age = :age, gender = :gender, father_husband_name = :fh_name WHERE id = :id");
            $stmt->execute([
                'name' => $_POST['patient_name'],
                'contact' => $_POST['contact'],
                'age' => $_POST['age'],
                'gender' => $_POST['gender'],
                'fh_name' => $_POST['fh_name'] ?? '',
                'id' => $patient_id
            ]);
        }
        
        $selected_categories = $_POST['selected_categories'] ?? [];
        $report_ids = [];
        
        // Create a report for each selected category
        foreach ($selected_categories as $category_id) {
            // Get category info including category_comment
            $stmt = $conn->prepare("SELECT category_comment FROM test_categories WHERE id = :id");
            $stmt->execute(['id' => $category_id]);
            $category_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $category_comment = $category_data['category_comment'] ?? '';
            
            // Get test parameters for this category
            $stmt = $conn->prepare("SELECT * FROM test_parameters WHERE category_id = :id ORDER BY display_order");
            $stmt->execute(['id' => $category_id]);
            $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Insert report with category-level comment
            $stmt = $conn->prepare("INSERT INTO reports (patient_id, category_id, request_date, performed_date, published_date, department, verified_by, comments, status, created_by) 
                                   VALUES (:patient_id, :category_id, :request_date, :performed_date, :published_date, :department, :verified_by, :comments, 'completed', :created_by)");
            $stmt->execute([
                'patient_id' => $patient_id,
                'category_id' => $category_id,
                'request_date' => $_POST['request_date'],
                'performed_date' => $_POST['performed_date'],
                'published_date' => $_POST['published_date'],
                'department' => $_POST['department'],
                'verified_by' => $_POST['verified_by'] ?? 'Dr Kiran Irshad - N/A',
                'comments' => $category_comment,  // Use category-level comment
                'created_by' => $_SESSION['user_id']
            ]);
            $report_id = $conn->lastInsertId();
            $report_ids[] = $report_id;
            
            // Insert results for this category
            foreach ($parameters as $param) {
                $result_value = $_POST['param_' . $param['id']] ?? '';
                
                if ($result_value === '') continue;
                
                // Check if abnormal
                $is_abnormal = 0;
                $numeric_value = floatval($result_value);
                if ($param['min_value'] !== '' && $numeric_value < floatval($param['min_value'])) {
                    $is_abnormal = 1;
                }
                if ($param['max_value'] !== '' && strpos($param['max_value'], '<') === false && $numeric_value > floatval($param['max_value'])) {
                    $is_abnormal = 1;
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
        }
        
        $conn->commit();
        
        // Redirect to combined PDF view
        header('Location: generate_pdf_multi.php?ids=' . implode(',', $report_ids));
        exit;
        
    } catch (Exception $e) {
        $conn->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Tests Report - BVH Medical Lab</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); overflow: hidden; }
        .header { background: linear-gradient(135deg, #2c5aa0 0%, #1e3a6f 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 28px; margin-bottom: 5px; }
        .header p { opacity: 0.9; }
        .content { padding: 30px; }
        .form-section { margin-bottom: 30px; background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #2c5aa0; }
        .form-section h2 { color: #2c5aa0; margin-bottom: 15px; font-size: 18px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-weight: 600; margin-bottom: 5px; color: #333; font-size: 14px; }
        .form-group input, .form-group select { padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #2c5aa0; box-shadow: 0 0 0 3px rgba(44,90,160,0.1); }
        
        .test-selection { margin-bottom: 30px; }
        .test-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; }
        .test-card { border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; transition: all 0.3s; }
        .test-card:hover { border-color: #2c5aa0; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .test-card.selected { border-color: #2c5aa0; background: #e3f2fd; }
        .test-card input[type="checkbox"] { margin-right: 10px; }
        .test-card label { cursor: pointer; font-weight: 600; color: #333; }
        
        .parameters-section { display: none; margin-top: 20px; padding: 20px; background: #fff; border: 2px solid #2c5aa0; border-radius: 8px; }
        .parameters-section.active { display: block; }
        .parameters-section h3 { color: #2c5aa0; margin-bottom: 15px; }
        .param-table { width: 100%; border-collapse: collapse; }
        .param-table th { background: #2c5aa0; color: white; padding: 10px; text-align: left; font-size: 13px; }
        .param-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .param-table input,
        .param-table select { 
            width: 100%; 
            padding: 6px; 
            border: 1px solid #ddd; 
            border-radius: 3px;
            font-size: 14px;
        }
        
        .param-table select {
            background: white;
            cursor: pointer;
            font-weight: 500;
        }
        
        .param-table select:focus,
        .param-table input:focus {
            border-color: #2c5aa0;
            outline: none;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }
        
        .button-group { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        .btn { padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: #2c5aa0; color: white; }
        .btn-primary:hover { background: #1e3a6f; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(44,90,160,0.3); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2c5aa0; text-decoration: none; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="header">
            <h1>🏥 Create Multiple Tests Report</h1>
            <p>Bahawal Victoria Hospital - Medical Laboratory</p>
        </div>
        
        <div class="content">
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" id="multiTestForm">
                <!-- Patient Information -->
                <div class="form-section">
                    <h2>👤 Patient Information</h2>
                    <div class="form-row">
                        <div class="form-group" style="position: relative;">
                            <label>MRN *</label>
                            <input type="text" id="mrn" name="mrn" required autocomplete="off" onkeyup="searchPatient(this.value)">
                            <div id="patientSuggestions" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; width: 100%; z-index: 1000; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 2px;"></div>
                        </div>
                        <div class="form-group">
                            <label>Patient Name *</label>
                            <input type="text" id="patient_name" name="patient_name" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Number *</label>
                            <input type="text" id="contact" name="contact" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Age *</label>
                            <input type="text" id="age" name="age" placeholder="e.g., 28Y 8M 16D" required>
                        </div>
                        <div class="form-group">
                            <label>Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Father/Husband Name</label>
                            <input type="text" id="fh_name" name="fh_name">
                        </div>
                    </div>
                </div>
                
                <!-- Test Information -->
                <div class="form-section">
                    <h2>🔬 Test Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Department *</label>
                            <input type="text" name="department" value="IPD Ward : Gynae I" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Request Date *</label>
                            <input type="datetime-local" name="request_date" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Performed Date *</label>
                            <input type="datetime-local" name="performed_date" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Published Date *</label>
                            <input type="datetime-local" name="published_date" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Verified By</label>
                            <input type="text" name="verified_by" value="Dr Kiran Irshad - N/A">
                        </div>
                    </div>
                    <!-- Note: Comments are now automatically added from category-level settings -->
                </div>
                
                <!-- Test Selection -->
                <div class="form-section test-selection">
                    <h2>✅ Select Tests to Include</h2>
                    <div class="test-cards">
                        <?php foreach ($categories as $cat): ?>
                            <div class="test-card" onclick="toggleTest(<?php echo $cat['id']; ?>)">
                                <input type="checkbox" name="selected_categories[]" value="<?php echo $cat['id']; ?>" id="cat_<?php echo $cat['id']; ?>">
                                <label for="cat_<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['code']); ?><br>
                                    <small style="color: #666; font-weight: normal;"><?php echo htmlspecialchars($cat['name']); ?></small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Test Parameters -->
                <?php foreach ($categories as $cat): 
                    // Get parameters for this category
                    $stmt = $conn->prepare("SELECT * FROM test_parameters WHERE category_id = :id ORDER BY display_order");
                    $stmt->execute(['id' => $cat['id']]);
                    $params = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                    <div class="parameters-section" id="params_<?php echo $cat['id']; ?>">
                        <h3><?php echo htmlspecialchars($cat['name']); ?> - <?php echo htmlspecialchars($cat['description']); ?></h3>
                        <table class="param-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Test Parameter</th>
                                    <th style="width: 25%;">Reference Range</th>
                                    <th style="width: 15%;">Unit</th>
                                    <th style="width: 20%;">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($params as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['test_name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['min_value'] . ' - ' . $p['max_value']); ?></td>
                                        <td><?php echo htmlspecialchars($p['unit']); ?></td>
                                        <td>
                                            <?php
                                            // Check if this is a dropdown-type parameter
                                            $isDropdown = false;
                                            $dropdownOptions = [];
                                            
                                            if (stripos($p['min_value'], 'Negative') !== false || 
                                                stripos($p['max_value'], 'Negative') !== false) {
                                                $isDropdown = true;
                                                $dropdownOptions = ['Negative', 'Positive'];
                                            } elseif (stripos($p['min_value'], 'Not Detected') !== false || 
                                                      stripos($p['max_value'], 'Not Detected') !== false) {
                                                $isDropdown = true;
                                                $dropdownOptions = ['Not Detected', 'Detected'];
                                            } elseif (stripos($p['min_value'], 'Clear') !== false && 
                                                      stripos($p['test_name'], 'Appearance') !== false) {
                                                $isDropdown = true;
                                                $dropdownOptions = ['Clear', 'Turbid', 'Hazy', 'Cloudy'];
                                            } elseif (stripos($p['test_name'], 'Color') !== false && 
                                                      stripos($p['min_value'], 'Yellow') !== false) {
                                                $isDropdown = true;
                                                $dropdownOptions = ['Pale Yellow', 'Yellow', 'Dark Yellow', 'Amber', 'Red', 'Brown'];
                                            } elseif (stripos($p['min_value'], 'Normal') !== false || 
                                                      stripos($p['max_value'], 'Normal') !== false) {
                                                $isDropdown = true;
                                                $dropdownOptions = ['Normal', 'Abnormal'];
                                            } elseif (stripos($p['min_value'], 'Nil') !== false || 
                                                      stripos($p['max_value'], 'Nil') !== false) {
                                                $isDropdown = true;
                                                $dropdownOptions = ['Nil', 'Few', 'Moderate', 'Many'];
                                            } elseif (stripos($p['min_value'], 'Few') !== false || 
                                                      stripos($p['max_value'], 'Few') !== false) {
                                                $isDropdown = true;
                                                $dropdownOptions = ['Nil', 'Few', 'Moderate', 'Many'];
                                            }
                                            
                                            if ($isDropdown): ?>
                                                <select name="param_<?php echo $p['id']; ?>">
                                                    <option value="">Select</option>
                                                    <?php foreach ($dropdownOptions as $option): ?>
                                                        <option value="<?php echo htmlspecialchars($option); ?>">
                                                            <?php echo htmlspecialchars($option); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php else: ?>
                                                <input type="text" name="param_<?php echo $p['id']; ?>" placeholder="Enter value">
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Combined Report</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function toggleTest(categoryId) {
            const checkbox = document.getElementById('cat_' + categoryId);
            const card = checkbox.closest('.test-card');
            const paramsSection = document.getElementById('params_' + categoryId);
            
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                card.classList.add('selected');
                paramsSection.classList.add('active');
            } else {
                card.classList.remove('selected');
                paramsSection.classList.remove('active');
            }
        }
        
        // Prevent form submission if no tests selected
        document.getElementById('multiTestForm').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name="selected_categories[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one test to include in the report!');
            }
        });
        
        // MRN Auto-search functionality
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
                                         style="padding: 10px; cursor: pointer; border-bottom: 1px solid #f0f0f0;">
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
            
            if (event.target !== mrnInput && !suggestionsDiv.contains(event.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
        
        // Add hover effect to suggestions
        const style = document.createElement('style');
        style.textContent = `
            #patientSuggestions > div:hover {
                background-color: #f5f5f5 !important;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
