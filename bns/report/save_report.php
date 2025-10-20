<?php
session_start();
require '../../db/config.php';
require_once '../../otp/mailer.php'; // ✅ include mailer

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = (int)$_POST['report_id'];

    // 🔹 Fetch the existing report
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE id = :id");
    $stmt->execute(['id' => $reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        die("Report not found.");
    }

    // 🔹 Only allow editing if Pending or Rejected
    if (!in_array($report['status'], ['Pending', 'Rejected'])) {
        die("This report cannot be edited.");
    }

    // 🔹 Collect all BNS inputs
    $fields = [
        'title' => $_POST['title'] ?? null,
        'barangay' => $_POST['barangay'] ?? null,
        'year' => $_POST['year'] ?? null,
    ];

    // --- Single indicators
    $singleIndicators = [
        'ind1','ind2','ind3','ind4a','ind4b','ind5','ind6','ind7a','ind8','ind9','ind10','ind11',
        'ind12','ind13','ind14','ind16','ind17','ind18','ind19','ind21','ind22','ind23','ind24','ind25',
        'ind31','ind32','ind33','ind34','ind35a','ind35b','ind36'
    ];
    foreach ($singleIndicators as $ind) {
        $fields[$ind] = $_POST[$ind] ?? null;
    }

    // --- Multi-part indicators
    for ($i = 1; $i <= 9; $i++) {
        $fields["ind7b{$i}_no"] = $_POST["ind7b{$i}_no"] ?? null;
        $fields["ind7b{$i}_pct"] = $_POST["ind7b{$i}_pct"] ?? null;
    }

    $fields['ind15a_public'] = $_POST['ind15a_public'] ?? null;
    $fields['ind15a_private'] = $_POST['ind15a_private'] ?? null;
    $fields['ind15b_public'] = $_POST['ind15b_public'] ?? null;
    $fields['ind15b_private'] = $_POST['ind15b_private'] ?? null;

    foreach (['a','b','c','d','e'] as $letter) {
        $fields["ind20{$letter}_no"] = $_POST["ind20{$letter}_no"] ?? null;
        $fields["ind20{$letter}_pct"] = $_POST["ind20{$letter}_pct"] ?? null;
    }

    foreach (['26','27'] as $num) {
        foreach (['a','b','c','d'] as $letter) {
            $fields["ind{$num}{$letter}_no"] = $_POST["ind{$num}{$letter}_no"] ?? null;
            $fields["ind{$num}{$letter}_pct"] = $_POST["ind{$num}{$letter}_pct"] ?? null;
        }
    }
    foreach (['28','29','30'] as $num) {
        foreach (['a','b','c','d','e'] as $letter) {
            $fields["ind{$num}{$letter}_no"] = $_POST["ind{$num}{$letter}_no"] ?? null;
            $fields["ind{$num}{$letter}_pct"] = $_POST["ind{$num}{$letter}_pct"] ?? null;
        }
    }

    // 🔹 Update bns_reports
    $updateFields = [];
    foreach ($fields as $k => $v) {
        $updateFields[] = "$k = :$k";
    }
    $updateFieldsStr = implode(", ", $updateFields);

    $sql = "UPDATE bns_reports SET $updateFieldsStr WHERE report_id = :report_id";
    $stmt = $pdo->prepare($sql);
    $fields['report_id'] = $reportId;
    $stmt->execute($fields);

    // 🔹 Update report status to Pending
    $stmt = $pdo->prepare("UPDATE reports SET status = 'Pending', prev_status = NULL WHERE id = :id");
    $stmt->execute(['id' => $reportId]); // ✅ prev_status=NULL triggers CNO notification

    // 🔹 Log the activity
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, details, created_at)
        VALUES (:user_id, :action, :details, NOW())
    ");
    $logStmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':action'  => 'Report Updated / Saved Changes',
        ':details' => "Report ID {$reportId} was edited and reset to Pending"
    ]);

    // 📧 Send email notification (to CNO or Admin) with report title and sender
    $stmt = $pdo->prepare("
        SELECT u.email, u.first_name, u.last_name
        FROM users u
        WHERE u.user_type = 'CNO'
        LIMIT 1
    ");
    $stmt->execute();
    $cnoUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cnoUser && !empty($cnoUser['email'])) {
        $to = $cnoUser['email'];
        $subject = "Report Updated - Pending Review";

        $reportTitle = htmlspecialchars($fields['title']);
        
        // Get sender name from users table
        $userStmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = :id LIMIT 1");
        $userStmt->execute(['id' => $_SESSION['user_id']]);
        $sender = $userStmt->fetch(PDO::FETCH_ASSOC);
        $senderName = $sender ? htmlspecialchars($sender['first_name'] . ' ' . $sender['last_name']) : 'Unknown';

        $message = "
            Hello,<br><br>
            A report titled <strong>$reportTitle</strong> has been updated by <strong>$senderName</strong> 
            and is now pending your review.<br><br>
            <strong>Date:</strong> " . date('Y-m-d') . "<br><br>
            Please review it in the system.
        ";

        sendEmailNotification($to, $subject, $message);
    } else {
        error_log("DEBUG: No CNO user found or email is empty");
    }

    // 🔹 Redirect
    header("Location: ../reports.php?id=$reportId&msg=updated");
    exit();
}
?>
