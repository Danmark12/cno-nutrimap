<?php
require_once __DIR__ . '/../db/config.php';
session_start();

$userId = $_SESSION['user_id'] ?? null;
$notifId = $_GET['id'] ?? null;

if ($userId && $notifId) {
    // Mark as read
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $notifId, 'user_id' => $userId]);

    // Redirect to report page
    $stmt2 = $pdo->prepare("SELECT report_id FROM notifications WHERE id = :id");
    $stmt2->execute(['id' => $notifId]);
    $report = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($report) {
        header("Location: report/view_report.php?id=" . $report['report_id']);
        exit();
    }
}

header("Location: notifications.php");
exit();
