<?php
session_start();
require '../db/config.php';
require 'mailer.php'; 

if (!isset($_SESSION['pending_user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$user_id = $_SESSION['pending_user_id'];

function logActivity($pdo, $user_id, $action, $details = null) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['resend'])) {
        $otp = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $stmt = $pdo->prepare("INSERT INTO otp_codes (user_id, otp_code, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $otp, $expires]);

        sendOTP($_SESSION['pending_user_email'], $otp);
        $_SESSION['otp_message'] = "A new OTP has been sent to your email.";
        logActivity($pdo, $user_id, "OTP Resent", "Resent OTP to email: " . $_SESSION['pending_user_email']);
    } else {
        $otp = trim($_POST['otp']);
        $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$user_id]);
        $code = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($code && $code['otp_code'] === $otp && strtotime($code['expires_at']) > time()) {
            $_SESSION['user_id']    = $user_id;
            $_SESSION['user_type']  = $_SESSION['pending_user_type'];
            $_SESSION['first_name'] = $_SESSION['pending_first_name'];
            $_SESSION['email']      = $_SESSION['pending_user_email'];
            $_SESSION['barangay']   = $_SESSION['pending_barangay']; 
            if (isset($_SESSION['pending_username'])) {
                $_SESSION['username'] = $_SESSION['pending_username'];
            }

            $session_id = session_id();
            $browser = $_SERVER['HTTP_USER_AGENT'];
            $ip = $_SERVER['REMOTE_ADDR'];

            $checkStmt = $pdo->prepare("SELECT id FROM login_history WHERE user_id = ? AND browser = ? AND ip_address = ? LIMIT 1");
            $checkStmt->execute([$user_id, $browser, $ip]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                $insertStmt = $pdo->prepare("INSERT INTO login_history (user_id, session_id, browser, ip_address) VALUES (?, ?, ?, ?)");
                $insertStmt->execute([$user_id, $session_id, $browser, $ip]);
            } else {
                $updateStmt = $pdo->prepare("UPDATE login_history SET session_id = ? WHERE id = ?");
                $updateStmt->execute([$session_id, $existing['id']]);
            }

            logActivity($pdo, $user_id, "User logged in via OTP", "Device: $browser, IP: $ip");

            unset($_SESSION['pending_user_id'], $_SESSION['pending_user_type'], $_SESSION['pending_first_name'], $_SESSION['pending_user_email'], $_SESSION['pending_barangay'], $_SESSION['pending_username']);

            if ($_SESSION['user_type'] === 'CNO') {
                header("Location: ../cno/home.php");
            } else {
                header("Location: ../bns/home.php");
            }
            exit;
        } else {
            $error = "Invalid or expired OTP!";
            logActivity($pdo, $user_id, "Failed OTP attempt", "Entered OTP: $otp");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP | CNO NutriMap</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #ffffff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .otp-container {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
      animation: fadeIn 0.8s ease forwards;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .otp-container h2 {
      color: #00796b;
      margin-bottom: 20px;
      font-size: 24px;
    }

    .otp-container p {
      margin-bottom: 15px;
      font-size: 14px;
    }

    .otp-input {
      width: 90%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1px solid #ccc;
      text-align: center;
      font-size: 16px;
      transition: 0.3s;
    }

    .otp-input:focus {
      border-color: #00796b;
      box-shadow: 0 0 8px rgba(0,121,107,0.3);
      outline: none;
    }

    .btn {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: none;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
      margin-bottom: 10px;
    }

    .btn-verify {
      background: #00796b;
      color: #fff;
    }

    .btn-verify:hover {
      background: #004d40;
    }

    .btn-resend {
      background: #0288d1;
      color: #fff;
    }

    .btn-resend:hover {
      background: #01579b;
    }

    .message {
      font-size: 14px;
      margin-bottom: 15px;
    }

    .message.error { color: #d32f2f; }
    .message.success { color: #388e3c; }

    @media (max-width: 400px) {
      .otp-container { width: 90%; padding: 30px 20px; }
    }
  </style>
</head>
<body>
  <div class="otp-container">
    <h2>Verify OTP</h2>

    <?php if (isset($_SESSION['otp_message'])): ?>
      <p class="message success"><?php echo $_SESSION['otp_message']; ?></p>
      <?php unset($_SESSION['otp_message']); ?>
    <?php endif; ?>

    <?php if ($error): ?>
      <p class="message error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="otp" maxlength="6" placeholder="Enter OTP" class="otp-input" required>
      <button type="submit" class="btn btn-verify">Verify</button>
    </form>

    <form method="POST">
      <button type="submit" name="resend" class="btn btn-resend">Resend OTP</button>
    </form>
  </div>
</body>
</html>
