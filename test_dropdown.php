<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Get Serology category (should have dropdown tests)
$stmt = $conn->query("SELECT * FROM test_categories WHERE code = 'SCREENING'");
$category = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h1>Testing Dropdown Detection - Serology Tests</h1>";
echo "<p>Category: " . htmlspecialchars($category['name']) . " - " . htmlspecialchars($category['description']) . "</p>";

// Get test parameters
$stmt = $conn->prepare("SELECT * FROM test_parameters WHERE category_id = :id ORDER BY display_order");
$stmt->execute(['id' => $category['id']]);
$parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Test Name</th><th>Min Value</th><th>Max Value</th><th>Dropdown?</th><th>Options</th></tr>";

foreach ($parameters as $param) {
    $isDropdown = false;
    $dropdownOptions = [];
    
    if (stripos($param['min_value'], 'Negative') !== false || 
        stripos($param['max_value'], 'Negative') !== false) {
        $isDropdown = true;
        $dropdownOptions = ['Negative', 'Positive'];
    }
    
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($param['test_name']) . "</strong></td>";
    echo "<td>" . htmlspecialchars($param['min_value']) . "</td>";
    echo "<td>" . htmlspecialchars($param['max_value']) . "</td>";
    echo "<td>" . ($isDropdown ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "</td>";
    echo "<td>" . implode(', ', $dropdownOptions) . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr><h2>Test Form</h2>";
echo "<form>";
foreach ($parameters as $param) {
    $isDropdown = false;
    $dropdownOptions = [];
    
    if (stripos($param['min_value'], 'Negative') !== false || 
        stripos($param['max_value'], 'Negative') !== false) {
        $isDropdown = true;
        $dropdownOptions = ['Negative', 'Positive'];
    }
    
    echo "<p><label><strong>" . htmlspecialchars($param['test_name']) . ":</strong> ";
    if ($isDropdown) {
        echo "<select name='param_" . $param['id'] . "' style='padding:8px; font-size:14px;'>";
        echo "<option value=''>Select</option>";
        foreach ($dropdownOptions as $option) {
            echo "<option value='" . htmlspecialchars($option) . "'>" . htmlspecialchars($option) . "</option>";
        }
        echo "</select>";
    } else {
        echo "<input type='text' name='param_" . $param['id'] . "' placeholder='Enter result' style='padding:8px; font-size:14px;'>";
    }
    echo "</label></p>";
}
echo "</form>";
?>
