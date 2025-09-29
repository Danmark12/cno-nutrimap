<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$barangay = $_SESSION['barangay']; // barangay auto-filled
$year = $_POST['year'] ?? null;

// ✅ Insert report
$stmt = $pdo->prepare("INSERT INTO barangay_bns_reports (barangay, year) VALUES (?, ?)");
$stmt->execute([$barangay, $year]);

// ✅ Log activity
$logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, created_at) VALUES (?, ?, NOW())");
$logStmt->execute([$user_id, "Added BNS report for Barangay: $barangay, Year: $year"]);

echo "Report saved for Barangay: " . htmlspecialchars($barangay);
?>

