<?php
/**
 * Migration Script: Add created_by column to reports table
 * This tracks which user created each report
 */

require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check if column already exists
    $columns = $conn->query("PRAGMA table_info(reports)")->fetchAll(PDO::FETCH_ASSOC);
    $columnExists = false;
    
    foreach ($columns as $column) {
        if ($column['name'] === 'created_by') {
            $columnExists = true;
            break;
        }
    }
    
    if (!$columnExists) {
        // Add created_by column
        $conn->exec("ALTER TABLE reports ADD COLUMN created_by INTEGER");
        
        // Add foreign key index
        $conn->exec("CREATE INDEX IF NOT EXISTS idx_reports_created_by ON reports(created_by)");
        
        echo "✅ Successfully added 'created_by' column to reports table\n";
        echo "✅ Created index on created_by column\n";
    } else {
        echo "ℹ️ Column 'created_by' already exists in reports table\n";
    }
    
    echo "\n✅ Migration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
