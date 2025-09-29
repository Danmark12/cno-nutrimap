<?php
session_start();
require '../../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$reportId = $_POST['report_id'] ?? 0;
if (!is_numeric($reportId) || $reportId <= 0) {
    die("Invalid report ID");
}

$userId = $_SESSION['user_id'];

// ✅ Activity log function
function logActivity($pdo, $user_id, $action, $details = null) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}

try {
    $pdo->beginTransaction();

    // 1️⃣ Fetch old report and bns_report
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE id = :id");
    $stmt->execute(['id' => $reportId]);
    $oldReport = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$oldReport) throw new Exception("Report not found");

    $stmt = $pdo->prepare("SELECT * FROM bns_reports WHERE report_id = :report_id");
    $stmt->execute(['report_id' => $reportId]);
    $oldBns = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$oldBns) throw new Exception("BNS data not found");

    // 2️⃣ Create new pending report
    $stmt = $pdo->prepare("
        INSERT INTO reports (user_id, report_time, report_date, status) 
        VALUES (:user_id, :report_time, :report_date, 'Pending')
    ");
    $stmt->execute([
        'user_id' => $userId,
        'report_time' => date('H:i:s'),
        'report_date' => date('Y-m-d')
    ]);
    $newReportId = $pdo->lastInsertId();

    // 3️⃣ Prepare new BNS data from form
    $bnsFields = $oldBns; // start with old data

    // Overwrite with submitted values
    foreach ($_POST as $key => $value) {
        if ($key !== 'report_id' && $key !== 'title_display') { // skip report_id
            $bnsFields[$key] = $value;
        }
    }
    $bnsFields['report_id'] = $newReportId;
    $bnsFields['title'] = $_POST['title'] ?? ($oldBns['title'] ?? '');

    // Remove old id to allow auto-increment
    unset($bnsFields['id']);

    // 4️⃣ Insert new BNS report
    $columns = implode(',', array_keys($bnsFields));
    $placeholders = ':' . implode(',:', array_keys($bnsFields));
    $stmt = $pdo->prepare("INSERT INTO bns_reports ($columns) VALUES ($placeholders)");
    $stmt->execute($bnsFields);

    $pdo->commit();

    // ✅ Log activity
    logActivity(
        $pdo,
        $userId,
        "Updated report (cloned as Pending)",
        "Old Report ID: $reportId → New Report ID: $newReportId"
    );

    header("Location: ../reports.php?id=$newReportId&msg=Report updated as Pending");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error: " . $e->getMessage());
}
