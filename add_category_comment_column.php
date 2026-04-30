<?php
/**
 * Database Migration: Add category_comment column to test_categories table
 * Run this file once to add the missing column
 */

require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "Starting migration...\n";
    
    // Check if column already exists
    $stmt = $conn->query("PRAGMA table_info(test_categories)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $columnExists = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'category_comment') {
            $columnExists = true;
            break;
        }
    }
    
    if ($columnExists) {
        echo "✓ Column 'category_comment' already exists in test_categories table.\n";
    } else {
        // Add the column
        $conn->exec("ALTER TABLE test_categories ADD COLUMN category_comment TEXT");
        echo "✓ Successfully added 'category_comment' column to test_categories table.\n";
    }
    
    echo "\nMigration completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
