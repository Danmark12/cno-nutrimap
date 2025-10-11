<?php
session_start();
require '../../db/config.php';

// ✅ Activity log
function logActivity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; // 'CNO'

// 🔹 Fetch all archived reports for this user
$fetch = $pdo->prepare("
    SELECT DISTINCT report_id FROM report_archives 
    WHERE user_id = :uid AND user_type = :utype AND is_archived = 1 AND (is_deleted = 0 OR is_deleted IS NULL)
");
$fetch->execute([':uid'=>$user_id, ':utype'=>$user_type]);
$reportIds = $fetch->fetchAll(PDO::FETCH_COLUMN);

if (empty($reportIds)) {
    header("Location: ../archive.php?msg=No archived reports to delete");
    exit();
}

try {
    $pdo->beginTransaction();

    $pdo->prepare("
        UPDATE report_archives
        SET is_deleted=1, is_archived=0, deleted_at=NOW()
        WHERE user_id=:uid AND user_type=:utype AND is_archived=1
    ")->execute([':uid'=>$user_id, ':utype'=>$user_type]);

    $chk = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN user_type='BNS' AND is_deleted=1 THEN 1 ELSE 0 END) AS bns_deleted,
            SUM(CASE WHEN user_type='CNO' AND is_deleted=1 THEN 1 ELSE 0 END) AS cno_deleted
        FROM report_archives WHERE report_id=:rid
    ");
    $delBns = $pdo->prepare("DELETE FROM bns_reports WHERE report_id=:rid");
    $delReports = $pdo->prepare("DELETE FROM reports WHERE id=:rid");
    $delArchives = $pdo->prepare("DELETE FROM report_archives WHERE report_id=:rid");

    foreach ($reportIds as $rid) {
        $chk->execute([':rid'=>$rid]);
        $both = $chk->fetch(PDO::FETCH_ASSOC);
        if ($both['bns_deleted'] > 0 && $both['cno_deleted'] > 0) {
            $delBns->execute([':rid'=>$rid]);
            $delReports->execute([':rid'=>$rid]);
            $delArchives->execute([':rid'=>$rid]);
            logActivity($pdo, $user_id, "Permanently deleted report (ID: $rid) after both users deleted (Delete All)");
        }
    }

    $pdo->commit();
    logActivity($pdo, $user_id, "CNO Delete All executed successfully");
    header("Location: ../archive.php?msg=All archived reports deleted successfully");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    logActivity($pdo, $user_id, "Error in Delete All: ".$e->getMessage());
    header("Location: ../archive.php?msg=Error deleting all reports");
    exit();
}
?>
