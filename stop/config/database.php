<?php
/**
 * Database Configuration
 * Using SQLite - No import needed, automatically creates database
 */

class Database {
    private $db_file = __DIR__ . '/../data/medical_lab.db';
    private $conn;

    public function __construct() {
        try {
            // Create data directory if not exists
            $data_dir = dirname($this->db_file);
            if (!file_exists($data_dir)) {
                mkdir($data_dir, 0777, true);
            }

            // Connect to SQLite database
            $this->conn = new PDO("sqlite:" . $this->db_file);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Initialize database tables
            $this->initDatabase();
            
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function initDatabase() {
        // Create tables if they don't exist
        
        // Patients table
        $this->conn->exec("CREATE TABLE IF NOT EXISTS patients (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            mrn VARCHAR(50) UNIQUE NOT NULL,
            name VARCHAR(100) NOT NULL,
            contact VARCHAR(20),
            age VARCHAR(20),
            gender VARCHAR(10),
            father_husband_name VARCHAR(100),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Test categories
        $this->conn->exec("CREATE TABLE IF NOT EXISTS test_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            code VARCHAR(50) UNIQUE NOT NULL,
            description TEXT
        )");

        // Test parameters
        $this->conn->exec("CREATE TABLE IF NOT EXISTS test_parameters (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            test_name VARCHAR(100) NOT NULL,
            min_value VARCHAR(20),
            max_value VARCHAR(20),
            unit VARCHAR(50),
            display_order INTEGER DEFAULT 0,
            FOREIGN KEY (category_id) REFERENCES test_categories(id)
        )");

        // Reports table
        $this->conn->exec("CREATE TABLE IF NOT EXISTS reports (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            patient_id INTEGER NOT NULL,
            category_id INTEGER NOT NULL,
            request_date DATETIME,
            performed_date DATETIME,
            published_date DATETIME,
            department VARCHAR(100),
            ward VARCHAR(100),
            verified_by VARCHAR(100),
            status VARCHAR(20) DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES patients(id),
            FOREIGN KEY (category_id) REFERENCES test_categories(id)
        )");

        // Report results
        $this->conn->exec("CREATE TABLE IF NOT EXISTS report_results (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            report_id INTEGER NOT NULL,
            parameter_id INTEGER NOT NULL,
            result_value VARCHAR(100),
            is_abnormal INTEGER DEFAULT 0,
            FOREIGN KEY (report_id) REFERENCES reports(id),
            FOREIGN KEY (parameter_id) REFERENCES test_parameters(id)
        )");

        // Users table
        $this->conn->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            role VARCHAR(20) DEFAULT 'staff',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Insert default data
        $this->insertDefaultData();
    }

    private function insertDefaultData() {
        // Check if data already exists
        $stmt = $this->conn->query("SELECT COUNT(*) FROM test_categories");
        if ($stmt->fetchColumn() > 0) {
            return; // Data already exists
        }

        // Insert default admin user (password: BVH@2026$Secure#Lab!PathologyAdmin)
        $this->conn->exec("INSERT INTO users (username, password, full_name, role) 
                          VALUES ('admin', '" . password_hash('BVH@2026$Secure#Lab!PathologyAdmin', PASSWORD_DEFAULT) . "', 'Administrator', 'admin')");

        // Insert test categories
        $categories = [
            ['Haematology', 'CBC', 'Complete Blood Count'],
            ['Routine Chemistry', 'LFT', 'Liver Function Test'],
            ['Routine Chemistry', 'LIPID', 'Lipid Profile'],
            ['Routine Chemistry', 'CRP', 'CRP - HS'],
            ['Serology', 'SCREENING', 'Screening Tests'],
            ['Molecular Biology', 'PCR', 'RT-PCR Tests'],
            ['Urine Analysis', 'URINE', 'Complete Urine Examination'],
            ['Vitamins', 'VITAMIN-D', 'Vitamin D Test'],
            ['Vitamins', 'VITAMIN-B12', 'Vitamin B12 Test'],
            ['Routine Chemistry', 'RFT', 'Renal Function Test'],
            ['Routine Chemistry', 'TFT', 'Thyroid Function Test'],
            ['Haematology', 'ESR', 'Erythrocyte Sedimentation Rate'],
            ['Routine Chemistry', 'HbA1c', 'Glycated Hemoglobin'],
            ['Hormones', 'HORMONES', 'Hormone Panel'],
            ['Electrolytes', 'ELECTROLYTES', 'Serum Electrolytes'],
            ['Tumor Markers', 'TUMOR', 'Tumor Markers']
        ];

        foreach ($categories as $cat) {
            $this->conn->exec("INSERT INTO test_categories (name, code, description) 
                              VALUES ('{$cat[0]}', '{$cat[1]}', '{$cat[2]}')");
        }

        // Insert CBC parameters
        $cbc_params = [
            ['WBC', '4.0', '11.0', '/mm³', 1],
            ['RBC', '3.6', '5.8', 'mm³', 2],
            ['HGB', '10.0', '15.0', 'g/dl', 3],
            ['HCT', '37.0', '47.0', '%', 4],
            ['MCV', '76.0', '96.0', 'fl', 5],
            ['MCHC', '30.0', '35.0', 'G/dl', 6],
            ['PLT', '150.0', '400.0', 'mm³', 7],
            ['%Neut', '', '', '%', 8],
            ['%LYMP', '20.0', '45.0', '%', 9],
            ['MCH', '27.0', '32.0', 'pg', 10],
            ['MXD%', '4.0', '10.0', '%', 11]
        ];

        foreach ($cbc_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (1, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }

        // Insert LFT parameters
        $lft_params = [
            ['ALT', '', '< 40.0', 'U/L', 1],
            ['Total Bilirubin', '', '< 1.0', 'mg/dl', 2]
        ];

        foreach ($lft_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (2, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }

        // Insert Lipid Profile parameters
        $lipid_params = [
            ['TRIGLYCERIDES', '', '< 150', 'mg/dl', 1],
            ['Total Cholesterol', '', '< 200', 'mg/dl', 2],
            ['HDL CHOLESTEROL', '> 35', '', 'mg/dl', 3],
            ['LDL', '', '< 150', 'mg/dl', 4],
            ['VLDL', '', '< 30', 'mg/dl', 5]
        ];

        foreach ($lipid_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (3, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }

        // Insert CRP parameter
        $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                          VALUES (4, 'HS-CRP', '', '< 5.0', 'mg/l', 1)");

        // Insert Serology parameters
        $serology_params = [
            ['Anti HIV', 'Negative', 'Negative', '', 1],
            ['HBsAg', 'Negative', 'Negative', '', 2],
            ['Anti HCV', 'Negative', 'Negative', '', 3]
        ];

        foreach ($serology_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (5, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
        
        // Insert PCR/RT-PCR parameters (category_id: 6)
        $pcr_params = [
            ['COVID-19 RT-PCR', 'Negative', 'Negative', '', 1],
            ['Hepatitis B PCR', 'Not Detected', 'Not Detected', 'IU/mL', 2],
            ['Hepatitis C PCR', 'Not Detected', 'Not Detected', 'IU/mL', 3],
            ['HIV RNA PCR', 'Not Detected', 'Not Detected', 'copies/mL', 4],
            ['TB PCR (MTB)', 'Not Detected', 'Not Detected', '', 5]
        ];
        
        foreach ($pcr_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (6, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
        
        // Insert Urine Analysis parameters (category_id: 7)
        $urine_params = [
            ['Color', 'Pale Yellow', 'Pale Yellow', '', 1],
            ['Appearance', 'Clear', 'Clear', '', 2],
            ['pH', '5.0', '8.0', '', 3],
            ['Specific Gravity', '1.005', '1.030', '', 4],
            ['Protein', 'Negative', 'Negative', 'mg/dL', 5],
            ['Glucose', 'Negative', 'Negative', 'mg/dL', 6],
            ['Ketones', 'Negative', 'Negative', '', 7],
            ['Blood', 'Negative', 'Negative', '', 8],
            ['Bilirubin', 'Negative', 'Negative', '', 9],
            ['Urobilinogen', 'Normal', 'Normal', '', 10],
            ['Nitrite', 'Negative', 'Negative', '', 11],
            ['Leukocyte Esterase', 'Negative', 'Negative', '', 12],
            ['RBCs', '0', '3', '/HPF', 13],
            ['WBCs', '0', '5', '/HPF', 14],
            ['Epithelial Cells', 'Few', 'Few', '/HPF', 15],
            ['Bacteria', 'Nil', 'Nil', '/HPF', 16],
            ['Crystals', 'Nil', 'Nil', '/HPF', 17],
            ['Casts', 'Nil', 'Nil', '/LPF', 18]
        ];
        
        foreach ($urine_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (7, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
        
        // Insert Vitamin D parameters (category_id: 8)
        $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                          VALUES (8, '25-OH Vitamin D', '30.0', '100.0', 'ng/mL', 1)");
        
        // Insert Vitamin B12 parameters (category_id: 9)
        $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                          VALUES (9, 'Vitamin B12', '200.0', '900.0', 'pg/mL', 1)");
        
        // Insert RFT (Renal Function Test) parameters (category_id: 10)
        $rft_params = [
            ['Blood Urea', '15.0', '40.0', 'mg/dL', 1],
            ['Serum Creatinine', '0.6', '1.2', 'mg/dL', 2],
            ['Uric Acid', '3.5', '7.2', 'mg/dL', 3],
            ['BUN', '7.0', '20.0', 'mg/dL', 4],
            ['eGFR', '> 60', '', 'mL/min/1.73m²', 5]
        ];
        
        foreach ($rft_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (10, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
        
        // Insert TFT (Thyroid Function Test) parameters (category_id: 11)
        $tft_params = [
            ['TSH', '0.5', '5.0', 'µIU/mL', 1],
            ['T3', '80.0', '200.0', 'ng/dL', 2],
            ['T4', '4.5', '12.0', 'µg/dL', 3],
            ['Free T3', '2.3', '4.2', 'pg/mL', 4],
            ['Free T4', '0.8', '1.8', 'ng/dL', 5]
        ];
        
        foreach ($tft_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (11, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
        
        // Insert ESR parameters (category_id: 12)
        $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                          VALUES (12, 'ESR', '0', '20', 'mm/1st hour', 1)");
        
        // Insert HbA1c parameters (category_id: 13)
        $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                          VALUES (13, 'HbA1c', '4.0', '5.6', '%', 1)");
        
        // Insert Hormone Panel parameters (category_id: 14)
        $hormone_params = [
            ['FSH', '1.5', '12.4', 'mIU/mL', 1],
            ['LH', '1.7', '8.6', 'mIU/mL', 2],
            ['Prolactin', '4.0', '15.0', 'ng/mL', 3],
            ['Testosterone', '300.0', '1000.0', 'ng/dL', 4],
            ['Estradiol', '15.0', '350.0', 'pg/mL', 5],
            ['Progesterone', '0.2', '25.0', 'ng/mL', 6],
            ['Cortisol', '5.0', '25.0', 'µg/dL', 7]
        ];
        
        foreach ($hormone_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (14, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
        
        // Insert Electrolytes parameters (category_id: 15)
        $electrolyte_params = [
            ['Sodium (Na+)', '135.0', '145.0', 'mEq/L', 1],
            ['Potassium (K+)', '3.5', '5.0', 'mEq/L', 2],
            ['Chloride (Cl-)', '98.0', '107.0', 'mEq/L', 3],
            ['Calcium (Ca)', '8.5', '10.5', 'mg/dL', 4],
            ['Magnesium (Mg)', '1.7', '2.2', 'mg/dL', 5],
            ['Phosphorus (P)', '2.5', '4.5', 'mg/dL', 6]
        ];
        
        foreach ($electrolyte_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (15, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
        
        // Insert Tumor Markers parameters (category_id: 16)
        $tumor_params = [
            ['CEA', '', '< 5.0', 'ng/mL', 1],
            ['CA 125', '', '< 35.0', 'U/mL', 2],
            ['CA 19-9', '', '< 37.0', 'U/mL', 3],
            ['PSA', '', '< 4.0', 'ng/mL', 4],
            ['AFP', '', '< 10.0', 'ng/mL', 5],
            ['CA 15-3', '', '< 30.0', 'U/mL', 6]
        ];
        
        foreach ($tumor_params as $param) {
            $this->conn->exec("INSERT INTO test_parameters (category_id, test_name, min_value, max_value, unit, display_order) 
                              VALUES (16, '{$param[0]}', '{$param[1]}', '{$param[2]}', '{$param[3]}', {$param[4]})");
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
