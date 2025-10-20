<?php
session_start();
require '../../db/config.php';

// ✅ Activity log function
function logActivity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

// ✅ Require login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; // 'BNS' or 'CNO'
$reportId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($reportId <= 0) {
    die("Invalid request");
}

// ✅ Check if the report exists
$stmt = $pdo->prepare("SELECT * FROM reports WHERE id = ?");
$stmt->execute([$reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    die("Report not found.");
}

// 🔹 Delete related bns_reports
$pdo->prepare("DELETE FROM bns_reports WHERE report_id = ?")->execute([$reportId]);

// 🔹 Delete report archive record
$pdo->prepare("DELETE FROM report_archives WHERE report_id = ?")->execute([$reportId]);

// 🔹 Delete main report
$pdo->prepare("DELETE FROM reports WHERE id = ?")->execute([$reportId]);

// ✅ Log the permanent delete activity
logActivity($pdo, $user_id, "Permanently deleted report (ID: $reportId)");

// ✅ Redirect back to archive page
header("Location: ../archive.php?msg=Report permanently deleted");
exit();
?>
