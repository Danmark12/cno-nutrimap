<?php
session_start();
require '../db/config.php';
require '../otp/mailer.php'; // ✅ include your mailer for email notifications

// --- Handle Approve / Decline actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['report_id'])) {
    $reportId = (int)$_POST['report_id'];
    $action = $_POST['action'];

    if (in_array($action, ['Approved', 'Rejected'])) {

        // ✅ Update report status
        $update = $pdo->prepare("UPDATE reports SET status = :status WHERE id = :id");
        $update->execute([
            ':status' => $action,
            ':id' => $reportId
        ]);

        // ✅ Log activity
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id']; // ✅ this is the CNO admin (sender)

            // ✅ Get admin name
            $adminStmt = $pdo->prepare("SELECT CONCAT(first_name, ' ', last_name) AS fullname FROM users WHERE id = ?");
            $adminStmt->execute([$userId]);
            $adminName = $adminStmt->fetchColumn() ?: "CNO Admin";

            // ✅ Record activity log
            $logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, created_at) VALUES (?, ?, NOW())");
            $logStmt->execute([$userId, "$action report ID: $reportId"]);

            // ✅ Get report owner (BNS user)
            $ownerStmt = $pdo->prepare("SELECT user_id FROM reports WHERE id = ?");
            $ownerStmt->execute([$reportId]);
            $reportOwnerId = $ownerStmt->fetchColumn();

            // ✅ Get report title from bns_reports
            $titleStmt = $pdo->prepare("SELECT title FROM bns_reports WHERE report_id = ?");
            $titleStmt->execute([$reportId]);
            $reportTitle = $titleStmt->fetchColumn();
            if (!$reportTitle) {
                $reportTitle = "Untitled Report";
            }

            if ($reportOwnerId) {
                // ✅ Notification type
                $notifType = ($action === 'Approved') ? 'report_approved' : 'report_rejected';

                // ✅ Insert notification for BNS user (in-app)
                $notifMessage = "Your report \"$reportTitle\" has been <b style='color:black;'>$action</b> by <b>$adminName</b>.";
                $notifLink = "view_report.php?id=" . $reportId;

                $notifStmt = $pdo->prepare("
                    INSERT INTO notifications 
                        (user_id, sender_id, receiver_type, type, related_id, message, link, is_read, created_at)
                    VALUES 
                        (:user_id, :sender_id, 'BNS', :type, :related_id, :message, :link, 0, NOW())
                ");
                $notifStmt->execute([
                    ':user_id'    => $reportOwnerId, // ✅ receiver (BNS)
                    ':sender_id'  => $userId,        // ✅ sender (CNO admin)
                    ':type'       => $notifType,
                    ':related_id' => $reportId,
                    ':message'    => $notifMessage,
                    ':link'       => $notifLink
                ]);

                // ✅ Fetch BNS user's info (email and name)
                $bnsStmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
                $bnsStmt->execute([$reportOwnerId]);
                $bns = $bnsStmt->fetch(PDO::FETCH_ASSOC);

                if ($bns && !empty($bns['email'])) {
                    // ✅ Send Email Notification — now includes $adminName (performedBy)
                    $sent = sendBnsReportNotification(
                        $bns['email'],
                        $bns['first_name'] . ' ' . $bns['last_name'],
                        $reportTitle,
                        $action, // Approved or Rejected
                        $reportId,
                        $adminName // ✅ New parameter: performedBy
                    );

                    // ✅ Log result for debugging
                    if ($sent) {
                        error_log("✅ Email sent successfully to {$bns['email']} for report ID $reportId ($action).");
                    } else {
                        error_log("❌ Failed to send email to {$bns['email']} for report ID $reportId ($action). Check mailer.php logs.");
                    }
                } else {
                    error_log("⚠️ No email found for BNS user ID $reportOwnerId (Report ID $reportId).");
                }
            } else {
                error_log("⚠️ No report owner found for report ID $reportId.");
            }
        }

    } elseif ($action === 'View') {
        header("Location: view_report.php?id=" . $reportId);
        exit;
    }

    // ✅ Redirect back to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . http_build_query($_GET));
    exit;
}
?>
