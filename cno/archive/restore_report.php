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

// ✅ Fetch report info including prev_status
$stmt = $pdo->prepare("SELECT status, prev_status FROM reports WHERE id = ?");
$stmt->execute([$reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    die("Report not found.");
}

// 🔹 Restore only if report is archived
if ($report['status'] === 'Archived') {
    $prevStatus = $report['prev_status'] ?: 'Pending'; // Default to Pending if prev_status is NULL

    // Restore the report
    $updateStmt = $pdo->prepare("UPDATE reports SET status = ?, prev_status = NULL WHERE id = ?");
    $updateStmt->execute([$prevStatus, $reportId]);

    // ✅ Log activity
    if (isset($_SESSION['user_id'])) {
        logActivity($pdo, $_SESSION['user_id'], "Restored report ID: $reportId to $prevStatus");
    }

    // 🔹 Redirect back
    header("Location: ../archive.php?msg=Report restored successfully");
    exit();
} else {
    die("Report is not archived and cannot be restored.");
}
