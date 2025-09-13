<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['pending_user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $user_id = $_SESSION['pending_user_id'];

    $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $code = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($code && $code['otp_code'] === $otp && strtotime($code['expires_at']) > time()) {
        // ✅ OTP verified
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_type'] = $_SESSION['pending_user_type'];
        $_SESSION['first_name'] = $_SESSION['pending_first_name'];

        unset($_SESSION['pending_user_id'], $_SESSION['pending_user_type'], $_SESSION['pending_first_name']);

        // 🔹 Removed last_login update since column doesn’t exist
        // $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user_id]);

        // Redirect based on role
        if ($_SESSION['user_type'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: bns/dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid or expired OTP!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
</head>
<body style="margin:0; font-family: Arial, sans-serif; background:#fff; display:flex; justify-content:center; align-items:center; height:100vh;">
  <div style="background:#f9f9f9; padding:30px; border-radius:10px; width:300px; box-shadow:0 0 10px rgba(0,0,0,0.1); text-align:center;">
    <h2 style="margin-bottom:15px;">Enter OTP</h2>
    <?php if (isset($_SESSION['otp_message'])): ?>
      <p style="color:green;"><?php echo $_SESSION['otp_message']; ?></p>
      <?php unset($_SESSION['otp_message']); ?>
    <?php endif; ?>
    <?php if ($error): ?>
      <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="otp" maxlength="6" placeholder="Enter OTP"
             style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px; text-align:center; font-size:16px;" required>
      <button type="submit" style="width:100%; padding:10px; background:#28a745; color:white; border:none; border-radius:5px; font-size:15px; cursor:pointer;">
        Verify
      </button>
    </form>
  </div>
</body>
</html>
