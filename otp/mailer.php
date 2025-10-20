<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Ensure PHPMailer autoload path is correct
require_once __DIR__ . '/../vendor/autoload.php';

/* ---------------------------------------------------------------------------
   ✅ Function for sending OTP
   --------------------------------------------------------------------------- */
function sendOTP($toEmail, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'danmarkpetalcurin@gmail.com';   // 👉 your Gmail
        $mail->Password   = 'qdal zfxu fsej bqqf';           // 👉 Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email headers
        $mail->setFrom('danmarkpetalcurin@gmail.com', 'CNO NutriMap');
        $mail->addAddress($toEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Your One-Time Password (OTP) for CNO NutriMap";
        $mail->Body = "
            Hello,<br><br>
            Your One-Time Password (OTP) for CNO NutriMap login is:
            <strong>" . htmlspecialchars($otp) . "</strong><br><br>
            This OTP is valid for 5 minutes.<br><br>
            Do not share this code with anyone.<br><br>
            Best regards,<br>
            The CNO NutriMap Team
        ";
        $mail->AltBody = "
            Hello,\n\n
            Your OTP for CNO NutriMap login is: $otp\n\n
            Valid for 5 minutes.\n\n
            Do not share this code with anyone.\n\n
            - CNO NutriMap Team
        ";

        $mail->send();
        error_log("✅ OTP Email sent to $toEmail");
        return true;
    } catch (Exception $e) {
        error_log("❌ OTP Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

/* ---------------------------------------------------------------------------
   ✅ Generic Email Sender
   --------------------------------------------------------------------------- */
function sendEmailNotification($toEmail, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'danmarkpetalcurin@gmail.com';
        $mail->Password   = 'qdal zfxu fsej bqqf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('danmarkpetalcurin@gmail.com', 'CNO NutriMap');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
        error_log("✅ Generic Email sent to $toEmail");
        return true;
    } catch (Exception $e) {
        error_log("❌ Generic Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

/* ---------------------------------------------------------------------------
   ✅ Existing CNO Report Notification
   --------------------------------------------------------------------------- */
function sendReportUpdateNotification($toEmail, $reportTitle, $status, $reportId) {
    $subject = "Report Update Notification - CNO NutriMap";
    $link = "http://localhost/nutrimap/bns/view_report.php?id=" . urlencode($reportId);

    $message = "
        Hello,<br><br>
        Your report titled <strong>" . htmlspecialchars($reportTitle) . "</strong> 
        has been updated and is now set to status: <strong>" . htmlspecialchars($status) . "</strong>.<br><br>
        You can view your report here: <a href='$link'>View Report</a><br><br>
        Best regards,<br>
        The CNO NutriMap Team
    ";

    return sendEmailNotification($toEmail, $subject, $message);
}

/* ---------------------------------------------------------------------------
   ✅ NEW: Email Notification for BNS (Approved / Rejected)
   --------------------------------------------------------------------------- */
function sendBnsReportNotification($toEmail, $bnsName, $reportTitle, $status, $reportId, $performedBy) {
    $mail = new PHPMailer(true);
    try {
        // Debugging (optional)
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP Debug [$level]: $str");
        };

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'danmarkpetalcurin@gmail.com';
        $mail->Password   = 'qdal zfxu fsej bqqf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('danmarkpetalcurin@gmail.com', 'CNO NutriMap');
        $mail->addAddress($toEmail, $bnsName);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "Report $status Notification - CNO NutriMap";
        $link = "http://localhost/nutrimap/bns/view_report.php?id=" . urlencode($reportId);

        // ✅ Modified part — shows who approved/rejected (Admin or CNO name)
        $mail->Body = "
            Hello <b>" . htmlspecialchars($bnsName) . "</b>,<br><br>
            Your report titled <strong>" . htmlspecialchars($reportTitle) . "</strong> 
            has been <strong style='color:black;'>" . htmlspecialchars($status) . "</strong> by <b>" . htmlspecialchars($performedBy) . "</b>.<br><br>
            You can view your report here: <a href='$link'>View Report</a>.<br><br>
            Thank you for your submission.<br><br>
            Best regards,<br>
            <b>The CNO NutriMap Team</b>
        ";

        // ✅ AltBody version (plain text)
        $mail->AltBody = "
            Hello $bnsName,
            Your report titled '$reportTitle' has been $status by $performedBy.
            You can view it at: $link
        ";

        $mail->send();
        error_log("✅ BNS Email successfully sent to: $toEmail");
        return true;
    } catch (Exception $e) {
        error_log("❌ BNS Notification Email Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
