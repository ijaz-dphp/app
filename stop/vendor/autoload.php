<?php
// Simple autoloader for TCPDF
// Download TCPDF manually and place in vendor/tcpdf folder

$tcpdf_path = __DIR__ . '/tcpdf/tcpdf.php';

if (file_exists($tcpdf_path)) {
    require_once($tcpdf_path);
} else {
    // Use built-in simple PDF generation if TCPDF not available
    // We'll create a fallback HTML report
}
