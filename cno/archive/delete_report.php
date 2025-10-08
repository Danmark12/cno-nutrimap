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

// 🔹 Check if there's a record in `report_archives` for this user
$stmt = $pdo->prepare("
    SELECT * FROM report_archives 
    WHERE report_id = ? AND user_id = ? AND user_type = ? AND is_archived = 1
");
$stmt->execute([$reportId, $userId, $userType]);
$archive = $stmt->fetch(PDO::FETCH_ASSOC);

if ($archive) {
    // 🔹 Instead of deleting the report globally, mark this user's archive as deleted
    $update = $pdo->prepare("
        UPDATE report_archives 
        SET is_deleted = 1 
        WHERE report_id = ? AND user_id = ? AND user_type = ?
    ");
    $update->execute([$reportId, $userId, $userType]);

    // ✅ Log the deletion activity
    logActivity($pdo, $userId, "Deleted archived report ID: $reportId");
} else {
    // 🔹 If not found in report_archives, proceed with full deletion (fallback)
    $delBns = $pdo->prepare("DELETE FROM bns_reports WHERE report_id = ?");
    $delBns->execute([$reportId]);

    $delReport = $pdo->prepare("DELETE FROM reports WHERE id = ?");
    $delReport->execute([$reportId]);

    logActivity($pdo, $userId, "Permanently deleted report ID: $reportId (no archive record found)");
}

// 🔹 Redirect back to archive page with a success message
header("Location: ../archive.php?msg=Report deleted permanently");
exit();
?>
