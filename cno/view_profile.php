<?php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Get target user ID from URL
$view_id = $_GET['id'] ?? null;

if (!$view_id || !is_numeric($view_id)) {
    die("Invalid user ID.");
}

// ✅ Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) || empty($confirm_password)) {
        $msg = "Please fill in both fields.";
    } elseif ($new_password !== $confirm_password) {
        $msg = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_stmt = $pdo->prepare("UPDATE users SET password_hash = ?, password_changed = 1 WHERE id = ?");
        $update_stmt->execute([$hashed_password, $view_id]);
        $msg = "Password successfully changed!";
    }
}

// ✅ Fetch user info
$stmt = $pdo->prepare("SELECT first_name, last_name, username, phone_number, email, address, barangay, user_type, profile_pic 
                       FROM users WHERE id = ?");
$stmt->execute([$view_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// ✅ Prepare variables safely
$full_name = htmlspecialchars($user['first_name'] . " " . $user['last_name']);
$username  = htmlspecialchars($user['username']);
$phone     = htmlspecialchars($user['phone_number']);
$email     = htmlspecialchars($user['email']);
$address   = htmlspecialchars($user['address']);
$barangay  = htmlspecialchars($user['barangay']);
$user_type = htmlspecialchars($user['user_type']);

// ✅ Profile picture (use default if null or empty)
$profile_pic = "../uploads/default_profile.png";
if (!empty($user['profile_pic']) && file_exists("../uploads/" . $user['profile_pic'])) {
    $profile_pic = "../uploads/" . htmlspecialchars($user['profile_pic']);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — View Profile</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f5f5f5; }
    .layout { display: flex; flex-direction: column; min-height: 100vh; }
    .page-title { font-size: 22px; font-weight: bold; margin: 20px 30px 10px; color: #333; }
    .body-layout { flex: 1; display: flex; justify-content: center; align-items: flex-start; padding: 20px 30px; gap: 20px; }

    /* ✅ PROFILE CARD */
    .profile-card {
      background: #fff;
      border-radius: 12px;
      padding: 30px 20px;
      flex: 0 0 350px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .profile-pic-wrapper {
      position: relative;
      width: 180px;
      height: 180px;
      margin-bottom: 15px;
      align-self: center;
    }
    .profile-pic-wrapper img {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #eee;
    }

    .profile-card h3 {
      margin: 10px 0 5px;
      font-size: 20px;
      font-weight: bold;
      color: #333;
      text-align: left;
    }
    .profile-card p.barangay {
      font-size: 15px;
      color: #666;
      display: flex;
      align-items: center;
      gap: 6px;
      margin: 0;
      text-align: left;
    }
    .profile-card p.role {
      font-size: 14px;
      color: #444;
      margin-top: 4px;
      background: #e0f2f1;
      padding: 4px 10px;
      border-radius: 6px;
      font-weight: bold;
    }

    /* ✅ INFO CARD */
    .info-card {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      flex: 1;
      min-width: 400px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .info-card h3 { 
      margin: 0 0 10px; 
      font-size: 18px; 
      font-weight: bold; 
      border-bottom: 1px solid #ddd; 
      padding-bottom: 8px; 
    }
    .info-group { display: flex; align-items: center; gap: 10px; }
    .info-group label { 
      min-width: 130px; 
      font-weight: bold; 
      font-size: 14px; 
      display: flex; 
      align-items: center; 
      gap: 6px; 
      color: #333; 
    }
    .info-group input { 
      flex: 1; 
      padding: 8px; 
      border: 1px solid #ccc; 
      border-radius: 4px; 
      background: #f9f9f9; 
      font-size: 14px; 
      color: #555; 
    }

    .btn-row {
      margin-top: 20px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    .btn-row a, .btn-row button {
      background: #009688;
      color: white;
      padding: 8px 15px;
      border-radius: 5px;
      text-decoration: none;
      border: none;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
      cursor: pointer;
      transition: background 0.2s ease-in-out;
    }
    .btn-row a:hover, .btn-row button:hover { background: #00796b; }

    .msg {
      color: green;
      font-size: 14px;
      font-weight: bold;
      margin-top: 5px;
    }
    .error { color: red; }

    /* ✅ MODAL STYLES */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background: #fff;
      padding: 20px 25px;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
      animation: fadeIn 0.3s;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: scale(0.9);}
      to {opacity: 1; transform: scale(1);}
    }
    .modal-content h3 {
      margin: 0 0 15px;
      font-size: 18px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 8px;
    }
    .modal-content input {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
    }
    .modal-content button {
      background: #009688;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }
    .modal-content button:hover { background: #00796b; }
    .close-btn {
      background: #d32f2f;
      margin-left: 10px;
    }
    .close-btn:hover { background: #b71c1c; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>
    <div class="page-title">User Profile</div>

    <div class="body-layout">
      <!-- ✅ PROFILE CARD -->
      <div class="profile-card">
        <div class="profile-pic-wrapper">
          <img src="<?php echo $profile_pic; ?>" alt="Profile Picture">
        </div>
        <h3><?php echo $username; ?></h3>
        <p class="barangay">
          <i class="fa-solid fa-location-dot"></i> Barangay: <?php echo $barangay; ?>
        </p>
        <p class="role"><?php echo $user_type; ?></p>
      </div>

      <!-- ✅ INFO CARD -->
      <div class="info-card">
        <h3>Basic Information</h3>
        <div class="info-group">
          <label><i class="fa-regular fa-user"></i> Full Name:</label>
          <input type="text" value="<?php echo $full_name; ?>" disabled>
        </div>
        <div class="info-group">
          <label><i class="fa-solid fa-phone"></i> Phone:</label>
          <input type="text" value="<?php echo $phone; ?>" disabled>
        </div>
        <div class="info-group">
          <label><i class="fa-regular fa-envelope"></i> Email:</label>
          <input type="email" value="<?php echo $email; ?>" disabled>
        </div>
        <div class="info-group">
          <label><i class="fa-solid fa-map-marker-alt"></i> Address:</label>
          <input type="text" value="<?php echo $address; ?>" disabled>
        </div>

        <?php if (isset($msg)): ?>
          <p class="msg <?php echo (strpos($msg, 'not') !== false || strpos($msg, 'fill') !== false) ? 'error' : ''; ?>"><?php echo $msg; ?></p>
        <?php endif; ?>

        <div class="btn-row">
          <a href="users.php"><i class="fa-solid fa-arrow-left"></i> Back to Users</a>
          <button type="button" onclick="openModal()"><i class="fa-solid fa-key"></i> Change Password</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ✅ MODAL -->
  <div id="passwordModal" class="modal">
    <div class="modal-content">
      <h3><i class="fa-solid fa-key"></i> Change Password</h3>
      <form method="POST">
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <div style="display:flex; justify-content:flex-end;">
          <button type="submit"><i class="fa-solid fa-check"></i> Update</button>
          <button type="button" class="close-btn" onclick="closeModal()"><i class="fa-solid fa-xmark"></i> Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById('passwordModal').style.display = 'flex';
    }
    function closeModal() {
      document.getElementById('passwordModal').style.display = 'none';
    }
    window.onclick = function(event) {
      const modal = document.getElementById('passwordModal');
      if (event.target === modal) closeModal();
    }
  </script>
</body>
</html>
