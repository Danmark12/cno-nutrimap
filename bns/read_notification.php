<?php
session_start();
require '../db/config.php';

$userId = $_SESSION['user_id'] ?? null;
$notifId = $_GET['id'] ?? null;

if ($userId && $notifId) {
    // ✅ Fetch the notification, join to reports + bns_reports for title
    $stmt = $pdo->prepare("
        SELECT 
            n.related_id AS report_id,
            r.status,
            COALESCE(br.title, CONCAT('Report #', r.id)) AS title
        FROM notifications n
        JOIN reports r ON r.id = n.related_id
        LEFT JOIN bns_reports br ON br.report_id = r.id
        WHERE n.id = :id AND n.user_id = :user_id
        LIMIT 1
    ");
    $stmt->execute([
        ':id' => $notifId,
        ':user_id' => $userId
    ]);
    $notif = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($notif) {
        // ✅ Mark as read
        $update = $pdo->prepare("
            UPDATE notifications 
            SET is_read = 1 
            WHERE id = :id AND user_id = :user_id
        ");
        $update->execute([
            ':id' => $notifId,
            ':user_id' => $userId
        ]);

        // ✅ Log that notification was read
        $logStmt = $pdo->prepare("
            INSERT INTO activity_logs (user_id, action, details, created_at)
            VALUES (:user_id, :action, :details, NOW())
        ");
        $logStmt->execute([
            ':user_id' => $userId,
            ':action'  => 'Notification Read',
            ':details' => "Report '{$notif['title']}' marked as read"
        ]);

        // ✅ Redirect to report view page
        header("Location: view_report.php?id=" . urlencode($notif['report_id']));
        exit;
    } else {
        // ⚠️ Notification not found or mismatched
        header("Location: notifications.php?error=notfound");
        exit;
    }
}

// ⚠️ If invalid session or missing ID
header("Location: notifications.php?error=invalid");
exit;
