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

$user_id   = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; // 'BNS' or 'CNO'
$reportId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($reportId <= 0) {
    die("Invalid request");
}

// 🔹 Check if archive record exists for this user/type
$check = $pdo->prepare("
    SELECT * FROM report_archives
    WHERE report_id = :rid AND user_id = :uid AND user_type = :utype
");
$check->execute([
    ':rid' => $reportId,
    ':uid' => $user_id,
    ':utype' => $user_type
]);
$archive = $check->fetch();

if ($archive) {
    // 🔹 Mark as deleted only for this user/type
    $update = $pdo->prepare("
        UPDATE report_archives
        SET is_deleted = 1, is_archived = 0, deleted_at = NOW()
        WHERE report_id = :rid AND user_id = :uid AND user_type = :utype
    ");
    $update->execute([
        ':rid' => $reportId,
        ':uid' => $user_id,
        ':utype' => $user_type
    ]);
} else {
    // 🔹 Create a new archive record for this user/type marked as deleted
    $insert = $pdo->prepare("
        INSERT INTO report_archives (report_id, user_id, user_type, is_archived, is_deleted, deleted_at)
        VALUES (:rid, :uid, :utype, 0, 1, NOW())
    ");
    $insert->execute([
        ':rid' => $reportId,
        ':uid' => $user_id,
        ':utype' => $user_type
    ]);
}

// ✅ Check if both BNS and CNO deleted the same report
$checkBoth = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN user_type = 'BNS' AND is_deleted = 1 THEN 1 ELSE 0 END) AS bns_deleted,
        SUM(CASE WHEN user_type = 'CNO' AND is_deleted = 1 THEN 1 ELSE 0 END) AS cno_deleted
    FROM report_archives
    WHERE report_id = :rid
");
$checkBoth->execute([':rid' => $reportId]);
$both = $checkBoth->fetch(PDO::FETCH_ASSOC);

// ✅ If both sides deleted, permanently remove the report
if ($both['bns_deleted'] > 0 && $both['cno_deleted'] > 0) {
    $pdo->prepare("DELETE FROM reports WHERE id = :rid")->execute([':rid' => $reportId]);
    $pdo->prepare("DELETE FROM bns_reports WHERE report_id = :rid")->execute([':rid' => $reportId]);
    $pdo->prepare("DELETE FROM report_archives WHERE report_id = :rid")->execute([':rid' => $reportId]);
    logActivity($pdo, $user_id, "Permanently deleted report (ID: $reportId) since both users deleted it");
} else {
    // ✅ Log the delete activity (for this specific user/type only)
    logActivity($pdo, $user_id, "Deleted report (ID: $reportId) from archive for user type $user_type");
}

// ✅ Redirect correctly based on user type
if ($user_type === 'CNO') {
    header("Location: ../archive.php?msg=Report deleted successfully");
    exit();
} else {
    header("Location: ../archive.php?msg=Report deleted");
    exit();
}
?>
