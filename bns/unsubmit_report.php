<?php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'BNS') {
    http_response_code(403);
    exit("Unauthorized access");
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['report_id'])) {
    http_response_code(400);
    exit("Missing report ID");
}

$report_id = intval($_POST['report_id']);

try {
    // ✅ Update report as unsubmitted
    $stmt = $pdo->prepare("UPDATE reports SET is_submitted = 0 WHERE id = :id AND user_id = :uid");
    $stmt->execute([':id' => $report_id, ':uid' => $user_id]);

    // ✅ Log the action
    $log = $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, details, created_at)
        VALUES (:user_id, 'Report Unsubmitted', :details, NOW())
    ");
    $log->execute([
        ':user_id' => $user_id,
        ':details' => "Report ID $report_id unsubmitted by BNS user."
    ]);

    echo "success";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>
