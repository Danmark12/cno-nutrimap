<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

// ✅ Function for sending OTP (kept exactly as you had it)
function sendOTP($toEmail, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'danmarkpetalcurin@gmail.com';   // 👉 your Gmail
        $mail->Password   = 'qdal zfxu fsej bqqf';           // 👉 Gmail App Password (NOT your Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('danmarkpetalcurin@gmail.com', 'CNO NutriMap');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Your One-Time Password (OTP) for CNO NutriMap";
        $mail->Body    = "
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
            Your One-Time Password (OTP) for CNO NutriMap login is: $otp\n\n
            This OTP is valid for 5 minutes.\n\n
            Do not share this code with anyone.\n\n
            Best regards,\n
            The CNO NutriMap Team
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // ❌ Log the error if needed: error_log("Mailer Error (OTP): " . $e->getMessage());
        return false;
    }
}

// ✅ Generic function for sending any notification
function sendEmailNotification($toEmail, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'danmarkpetalcurin@gmail.com';
        $mail->Password   = 'qdal zfxu fsej bqqf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('danmarkpetalcurin@gmail.com', 'CNO NutriMap');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
        return true;
    } catch (Exception $e) {
        // ❌ Log the error if needed: error_log("Mailer Error (Notification): " . $e->getMessage());
        return false;
    }
}

// ✅ Specific helper for Report Update Notifications
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
