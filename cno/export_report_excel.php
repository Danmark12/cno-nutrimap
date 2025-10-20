<?php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Check if report ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid report ID.");
}

$reportId = (int) $_GET['id'];

// ✅ Fetch report metadata
$stmt = $pdo->prepare("
    SELECT r.*, u.first_name, u.last_name, u.barangay
    FROM reports r
    JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
");
$stmt->execute([$reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    die("Report not found.");
}

// ✅ Fetch BNS report details (all indicators)
$stmt = $pdo->prepare("SELECT * FROM bns_reports WHERE report_id = ?");
$stmt->execute([$reportId]);
$bnsReport = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bnsReport) {
    die("No detailed report found for this ID.");
}

// ✅ Prepare CSV filename
$filename = "report_" . $reportId . "_" . date("Y-m-d") . ".csv";

// ✅ Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// ✅ Open output stream
$output = fopen('php://output', 'w');

// ✅ Write report header
fputcsv($output, ["Report ID", "User", "Barangay", "Date", "Status"]);
fputcsv($output, [
    $report['id'],
    $report['first_name'] . " " . $report['last_name'],
    $report['barangay'],
    $report['report_date'],
    $report['status']
]);

fputcsv($output, []); // empty line

// ✅ Write BNS indicators
fputcsv($output, ["Barangay", "Year", "Title"]);
fputcsv($output, [$bnsReport['barangay'], $bnsReport['year'], $bnsReport['title']]);

fputcsv($output, []); // empty line

// ✅ Loop through indicators dynamically
$headers = [];
$values = [];
foreach ($bnsReport as $key => $value) {
    if (strpos($key, 'ind') === 0) { // only include indicators (ind1, ind2…)
        $headers[] = strtoupper($key);
        $values[] = $value;
    }
}
fputcsv($output, $headers);
fputcsv($output, $values);

fclose($output);
exit();
