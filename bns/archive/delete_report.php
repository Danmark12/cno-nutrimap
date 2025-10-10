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

// 🔹 Check if archive record exists for this user
$check = $pdo->prepare("
    SELECT * FROM report_archives
    WHERE report_id = :rid AND user_id = :uid AND user_type = :utype
");
$check->execute([
    'rid' => $reportId,
    'uid' => $user_id,
    'utype' => $user_type
]);
$archive = $check->fetch();

if ($archive) {
    // 🔹 Mark as deleted for this user only
    $update = $pdo->prepare("
        UPDATE report_archives
        SET is_deleted = 1, deleted_at = NOW()
        WHERE report_id = :rid AND user_id = :uid AND user_type = :utype
    ");
    $update->execute([
        'rid' => $reportId,
        'uid' => $user_id,
        'utype' => $user_type
    ]);
} else {
    // 🔹 Create a new record marked as deleted
    $insert = $pdo->prepare("
        INSERT INTO report_archives (report_id, user_id, user_type, is_archived, is_deleted, deleted_at)
        VALUES (:rid, :uid, :utype, 1, 1, NOW())
    ");
    $insert->execute([
        'rid' => $reportId,
        'uid' => $user_id,
        'utype' => $user_type
    ]);
}

// ✅ Log the delete activity
logActivity($pdo, $user_id, "Deleted report (ID: $reportId) from archive");

// 🧩 NEW LOGIC: Delete the report from database only if no one (BNS/CNO) has a copy anymore
$checkRemaining = $pdo->prepare("
    SELECT COUNT(*) AS remaining 
    FROM report_archives 
    WHERE report_id = :rid AND is_deleted = 0
");
$checkRemaining->execute(['rid' => $reportId]);
$remaining = $checkRemaining->fetchColumn();

// 🧩 If no active (non-deleted) copy remains, delete from all tables
if ($remaining == 0) {
    // Delete from bns_reports if exists
    $pdo->prepare("DELETE FROM bns_reports WHERE report_id = :rid")->execute(['rid' => $reportId]);

    // Delete from main reports table
    $pdo->prepare("DELETE FROM reports WHERE id = :rid")->execute(['rid' => $reportId]);

    // Delete related archives
    $pdo->prepare("DELETE FROM report_archives WHERE report_id = :rid")->execute(['rid' => $reportId]);

    // Log that the report was permanently deleted
    logActivity($pdo, $user_id, "Permanently deleted report (ID: $reportId) — no remaining copies from BNS or CNO");
}

// ✅ Redirect back to archive page
header("Location: ../archive.php?msg=deleted");
exit();
?>
