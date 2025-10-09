<?php
session_start();
require '../../db/config.php';

// ✅ Activity log function
function logActivity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    die("Unauthorized access.");
}

$userId   = $_SESSION['user_id'];
$userType = $_SESSION['user_type']; // 'BNS' or 'CNO'

// ✅ Validate report ID
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

// ✅ Check if the report is archived for this specific user
$stmt = $pdo->prepare("
    SELECT * FROM report_archives 
    WHERE report_id = ? AND user_id = ? AND user_type = ? 
    AND is_archived = 1 AND (is_deleted = 0 OR is_deleted IS NULL)
");
$stmt->execute([$reportId, $userId, $userType]);
$archive = $stmt->fetch(PDO::FETCH_ASSOC);

if ($archive) {
    // 🔹 Restore only for this user
    $update = $pdo->prepare("
        UPDATE report_archives 
        SET is_archived = 0, is_deleted = 0, archived_at = NULL, deleted_at = NULL
        WHERE report_id = ? AND user_id = ? AND user_type = ?
    ");
    $update->execute([$reportId, $userId, $userType]);

    // ✅ Log the activity
    logActivity($pdo, $userId, "Restored archived report ID: $reportId (user-specific restore)");

} elseif ($report['status'] === 'Archived') {
    // 🔹 Fallback: restore globally if no per-user archive record exists
    $prevStatus = $report['prev_status'] ?: 'Pending'; // Default to Pending if NULL
    $updateStmt = $pdo->prepare("UPDATE reports SET status = ?, prev_status = NULL WHERE id = ?");
    $updateStmt->execute([$prevStatus, $reportId]);

    // ✅ Log activity
    logActivity($pdo, $userId, "Restored report ID: $reportId to $prevStatus (global restore)");

} else {
    die("Report is not archived and cannot be restored.");
}

// ✅ Redirect back to archive list
header("Location: ../archive.php?msg=Report restored successfully");
exit();
?>
