<?php
session_start();
require '../../db/config.php';

// ✅ Activity log function
function logActivity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

// ✅ Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$reportId = (int) $_GET['id'];

// ✅ Check if report exists
$stmt = $pdo->prepare("SELECT * FROM reports WHERE id = ?");
$stmt->execute([$reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    die("Report not found.");
}

// 🔹 Delete associated BNS report first
$delBns = $pdo->prepare("DELETE FROM bns_reports WHERE report_id = ?");
$delBns->execute([$reportId]);

// 🔹 Delete the report itself
$delReport = $pdo->prepare("DELETE FROM reports WHERE id = ?");
$delReport->execute([$reportId]);

// ✅ Log the deletion activity
if (isset($_SESSION['user_id'])) {
    logActivity($pdo, $_SESSION['user_id'], "Permanently deleted report ID: $reportId");
}

// 🔹 Redirect back to archive page with a success message
header("Location: ../archive.php?msg=Report deleted permanently");
exit();
