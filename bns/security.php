<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch login history for current user
$stmt = $pdo->prepare("SELECT browser, ip_address, login_time FROM login_history WHERE user_id = ? ORDER BY login_time DESC");
$stmt->execute([$user_id]);
$logins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Check if user already changed password
$stmt = $pdo->prepare("SELECT password_changed FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$password_changed = $user['password_changed'] ?? 0;
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Security</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; height:100vh; flex-direction:column; }
    .body-layout { flex:1; display:flex; }

    /* Sidebar */
    .sidebar {
      width:220px; background:#fff; padding:20px;
      box-shadow: 2px 0 8px rgba(0,0,0,0.1);
      display:flex; flex-direction:column; gap:15px;
    }
    .sidebar h3 { margin:0 0 10px; font-size:18px; }
    .sidebar a {
      display:flex; align-items:center; gap:10px;
      text-decoration:none; color:#000; font-size:15px;
      padding:8px; border-radius:4px;
      transition:background 0.2s;
    }
    .sidebar a.active, .sidebar a:hover {
      background:#00AEEF; color:#fff;
    }

    /* Content */
    .content { flex:1; padding:15px; display:flex; flex-direction:column; }
    .card {
      background:#fff; padding:20px;
      border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.1);
      display:none;
    }
    .card.active { display:block; }
    .card h2 { margin:0 0 10px; font-size:18px; }

    /* Login History */
    .login-entry {
      padding:10px 0; border-bottom:1px solid #eee;
      display:flex; justify-content:space-between;
    }
    .login-entry:last-child { border-bottom:none; }
    .browser-info { font-weight:bold; }
    .time { font-size:13px; color:#777; }
    .dots { cursor:pointer; }

    /* Change Password Form */
    .form-text {
      font-size: 14px;
      color: #333;
      margin-bottom: 15px;
      line-height: 1.4;
    }
    .form-group { margin-bottom:12px; }
    input[type="password"] {
      width:100%; padding:10px;
      border:1px solid #ccc; border-radius:4px;
    }
    .btn-submit {
      background:#009688; color:#fff;
      border:none; padding:10px 15px;
      border-radius:4px; cursor:pointer;
      display:block; width:100%;
    }
    .btn-submit:disabled {
      background:#ccc; cursor:not-allowed;
    }
    .btn-submit:hover:not(:disabled) { background:#00796b; }
    .alert {
      padding:10px; border-radius:4px;
      margin-bottom:10px; font-size:14px;
    }
    .alert.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
    .alert.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <!-- Sidebar -->
      <div class="sidebar">
        <h3>Security</h3>
        <a href="#" class="menu-link active" data-target="view-logins"><i class="fa-solid fa-clock-rotate-left"></i> View login</a>
        <a href="#" class="menu-link" data-target="change-password"><i class="fa-solid fa-lock"></i> Change password</a>
      </div>

      <!-- Content -->
      <div class="content">
        <!-- View Logins Card -->
        <div class="card active" id="view-logins">
          <h2>View logins</h2>
          <p>These are the devices where your account is logged in.</p>
          <?php if (count($logins) > 0): ?>
            <?php foreach ($logins as $login): ?>
              <div class="login-entry">
                <div>
                  <div class="browser-info">
                    <?= htmlspecialchars(parse_url($login['browser'], PHP_URL_HOST) ?: $login['browser']) ?>
                  </div>
                  <div class="time"><?= date('F j, Y, g:i a', strtotime($login['login_time'])) ?> — <?= htmlspecialchars($login['ip_address']) ?></div>
                </div>
                <div class="dots"><i class="fa-solid fa-ellipsis-vertical"></i></div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No logins recorded yet.</p>
          <?php endif; ?>
        </div>

        <!-- Change Password Card -->
        <div class="card" id="change-password">
          <h2>Change Password</h2>
          <p class="form-text">
            You can change your password once. Your password must be at least 6 characters long 
            and should include a combination of numbers, letters, and special characters (!@$%).
          </p>

          <?php if ($password_changed): ?>
            <div class="alert error">You have already changed your password. This action is allowed only once.</div>
          <?php else: ?>
            <form method="post" action="change_password.php">
              <div class="form-group">
                <input type="password" name="current_password" placeholder="Enter your current password" required>
              </div>
              <div class="form-group">
                <input type="password" name="new_password" placeholder="New password" required>
              </div>
              <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Retype new password" required>
              </div>
              <button type="submit" class="btn-submit">Change Password</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.menu-link').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.card').forEach(c => c.classList.remove('active'));

        link.classList.add('active');
        document.getElementById(link.dataset.target).classList.add('active');
      });
    });
  </script>
</body>
</html>
