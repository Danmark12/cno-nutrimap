<?php
session_start();
require '../../db/config.php';

function logActivity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// 🔹 Mark all archived reports as deleted for this user
$pdo->prepare("
    UPDATE report_archives 
    SET is_deleted=1, deleted_at=NOW() 
    WHERE user_id=? AND user_type=? AND is_archived=1
")->execute([$user_id, $user_type]);

// 🔹 Check which reports now deleted by both BNS and CNO
$both = $pdo->query("
    SELECT report_id
    FROM report_archives
    GROUP BY report_id
    HAVING SUM(CASE WHEN user_type='BNS' AND is_deleted=1 THEN 1 ELSE 0 END)>0
       AND SUM(CASE WHEN user_type='CNO' AND is_deleted=1 THEN 1 ELSE 0 END)>0
")->fetchAll(PDO::FETCH_COLUMN);

// 🔹 Delete permanently if both deleted
if ($both) {
    $in = str_repeat('?,', count($both)-1) . '?';
    $pdo->prepare("DELETE FROM bns_reports WHERE report_id IN ($in)")->execute($both);
    $pdo->prepare("DELETE FROM reports WHERE id IN ($in)")->execute($both);
    $pdo->prepare("DELETE FROM report_archives WHERE report_id IN ($in)")->execute($both);
    logActivity($pdo, $user_id, "Permanently deleted all reports both users removed");
} else {
    logActivity($pdo, $user_id, "Deleted all reports (waiting for other user)");
}

header("Location: ../archive.php?msg=deleted_all");
exit();
?>
