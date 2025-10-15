<?php
session_start();
require '../db/config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$reportId = (int) $_GET['id'];

if ($reportId > 0) {
    // ✅ Mark report as read by setting prev_status to current status
    $stmt = $pdo->prepare("UPDATE reports SET prev_status = status WHERE id = ?");
    $stmt->execute([$reportId]);

    // ✅ Optionally log that user viewed notification
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, details, created_at)
        VALUES (:user_id, :action, :details, NOW())
    ");
    $logStmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':action'  => 'Notification Read',
        ':details' => "Report ID $reportId notification marked as read"
    ]);
}

// ✅ Redirect to report details page
header("Location: view_report.php?id=" . $reportId);
exit;