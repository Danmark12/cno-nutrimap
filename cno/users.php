<?php
// users.php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$currentUserId = $_SESSION['user_id'];
$currentUserType = $_SESSION['user_type'] ?? '';

// Default filters
$search = $_GET['search'] ?? '';
$roleFilter = $_GET['role'] ?? 'all';
$sort = $_GET['sort'] ?? 'date';

// ---------- ACTIVE USERS ----------
$queryActive = "SELECT * FROM users WHERE status = 'Active'";
$paramsActive = [];

// Search filter
if (!empty($search)) {
    $queryActive .= " AND (first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR barangay LIKE :search)";
    $paramsActive[':search'] = "%$search%";
}

// Role filter
if ($roleFilter !== 'all') {
    $queryActive .= " AND user_type = :role";
    $paramsActive[':role'] = $roleFilter;
}

// Sorting
if ($sort === 'name') {
    $queryActive .= " ORDER BY first_name ASC, last_name ASC";
} else {
    $queryActive .= " ORDER BY created_at DESC"; // newest to oldest
}

$activeUsersStmt = $pdo->prepare($queryActive);
$activeUsersStmt->execute($paramsActive);
$activeUsers = $activeUsersStmt->fetchAll(PDO::FETCH_ASSOC);

// ---------- INACTIVE USERS ----------
$queryInactive = "SELECT * FROM users WHERE status = 'Inactive'";
$paramsInactive = [];

// Search filter
if (!empty($search)) {
    $queryInactive .= " AND (first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR barangay LIKE :search)";
    $paramsInactive[':search'] = "%$search%";
}

// Role filter
if ($roleFilter !== 'all') {
    $queryInactive .= " AND user_type = :role";
    $paramsInactive[':role'] = $roleFilter;
}

// Sorting
if ($sort === 'name') {
    $queryInactive .= " ORDER BY first_name ASC, last_name ASC";
} else {
    $queryInactive .= " ORDER BY created_at DESC"; // newest to oldest
}

$inactiveUsersStmt = $pdo->prepare($queryInactive);
$inactiveUsersStmt->execute($paramsInactive);
$inactiveUsers = $inactiveUsersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Manage Users</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; flex-direction:column; height:100vh; }
    .body-layout { display:flex; flex:1; }

    .content { flex:1; padding:20px; }

    h2 { font-size:20px; font-weight:bold; margin-bottom:15px; }

    /* Controls Row */
    .controls-row {
      display:flex; justify-content:space-between; align-items:center;
      margin-bottom:15px; gap:10px;
    }
    .card-controls {
      background:#fff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);
      padding:8px 12px; display:flex; align-items:center; gap:10px;
    }
    .card-controls input[type="text"] {
      border:none; outline:none; font-size:14px; width:200px;
    }
    .card-controls select {
      padding:6px 10px; border:1px solid #ccc; border-radius:6px;
    }
    .card-controls .btn {
      background:#009688; color:#fff; border:none; padding:7px 15px;
      border-radius:6px; cursor:pointer; font-size:14px;
    }

    /* Tabs */
    .tabs { display:flex; gap:15px; margin-bottom:10px; }
    .tabs button {
      border:none; background:none; padding:8px 10px; font-size:15px;
      cursor:pointer; border-bottom:2px solid transparent; color:#555;
    }
    .tabs button.active { border-bottom:2px solid #009688; color:#009688; font-weight:bold; }

    /* Card Table */
    .card {
      background:#fff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);
      padding:10px; width:100%; overflow-x:auto;
    }
    table {
      width:100%; border-collapse:collapse;
    }
    th, td {
      text-align:left; padding:12px 10px; font-size:14px; border-bottom:1px solid #eee;
    }
    th { font-weight:bold; color:#444; background:#f9f9f9; }

    td img {
      width:35px; height:35px; border-radius:50%; object-fit:cover; margin-right:8px;
      vertical-align:middle;
    }
    td .name { font-weight:bold; }

    /* Action Dropdown */
    .action-wrapper { position:relative; }
    .action-btn {
      border:none; background:none; font-size:18px; cursor:pointer;
    }
    .dropdown {
      display:none; position:absolute; right:0; top:25px;
      background:#fff; border:1px solid #ddd; border-radius:6px;
      box-shadow:0 2px 8px rgba(0,0,0,0.2);
      z-index:10; min-width:140px;
    }
    .dropdown a {
      display:block; padding:8px 12px; font-size:14px; color:#333; text-decoration:none;
    }
    .dropdown a:hover { background:#f5f5f5; }
  </style>
  <script>
    function toggleDropdown(id) {
      document.querySelectorAll('.dropdown').forEach(el => el.style.display = 'none');
      let dd = document.getElementById('dropdown-'+id);
      dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
    }
    window.onclick = function(e) {
      if (!e.target.matches('.action-btn')) {
        document.querySelectorAll('.dropdown').forEach(el => el.style.display = 'none');
      }
    }
    function switchTab(tabName) {
      document.getElementById('active-tab').style.display = (tabName === 'active') ? 'block' : 'none';
      document.getElementById('inactive-tab').style.display = (tabName === 'inactive') ? 'block' : 'none';
      document.getElementById('btn-active').classList.toggle('active', tabName==='active');
      document.getElementById('btn-inactive').classList.toggle('active', tabName==='inactive');
    }
  </script>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <main class="content">
        <h2>Manage Users</h2>

        <!-- Controls in Cards -->
        <form method="get" class="controls-row">
          <div class="card-controls">
            <i class="fa fa-search" style="color:#888;"></i>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search" onkeydown="if(event.key==='Enter'){this.form.submit();}">
          </div>
          <div class="card-controls">
            <select name="role" onchange="this.form.submit()">
              <option value="all" <?= $roleFilter==='all'?'selected':'' ?>>All</option>
              <option value="BNS" <?= $roleFilter==='BNS'?'selected':'' ?>>BNS</option>
              <option value="CNO" <?= $roleFilter==='CNO'?'selected':'' ?>>CNO</option>
            </select>
            <select name="sort" onchange="this.form.submit()">
              <option value="date" <?= $sort==='date'?'selected':'' ?>>Newest to Oldest</option>
              <option value="name" <?= $sort==='name'?'selected':'' ?>>A to Z</option>
            </select>
            <button type="button" class="btn" onclick="window.location.href='register.php'"><i class="fa fa-plus"></i> Add User</button>
          </div>
        </form>

        <!-- Tabs -->
        <div class="tabs">
          <button id="btn-active" class="active" onclick="switchTab('active')">Active</button>
          <button id="btn-inactive" onclick="switchTab('inactive')">Inactive</button>
        </div>

        <!-- Active Users -->
        <div id="active-tab" class="card">
          <table>
            <thead>
              <tr>
                <th>Name:</th>
                <th>Email:</th>
                <th>Barangay:</th>
                <th>Role:</th>
                <th>Date:</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($activeUsers)): ?>
                <tr><td colspan="6">No active users found.</td></tr>
              <?php else: ?>
                <?php foreach($activeUsers as $user): ?>
                  <tr>
                    <td>
                      <img src="<?= $user['profile_pic'] ? '../uploads/'.htmlspecialchars($user['profile_pic']) : '../assets/default.png' ?>">
                      <span class="name"><?= htmlspecialchars($user['first_name'].' '.$user['last_name']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['barangay']) ?></td>
                    <td><?= htmlspecialchars($user['user_type']) ?></td>
                    <td><?= date("n/j/Y", strtotime($user['created_at'])) ?></td>
                    <td class="action-wrapper">
                      <button class="action-btn" onclick="toggleDropdown(<?= $user['id'] ?>)">⋮</button>
                      <div class="dropdown" id="dropdown-<?= $user['id'] ?>">
                        <?php if ($currentUserType === 'CNO'): ?>
                          <a href="view_profile.php?id=<?= $user['id'] ?>"><i class="fa fa-user"></i> View Profile</a>
                        <?php else: ?>
                          <a href="view_profile.php?id=<?= $currentUserId ?>"><i class="fa fa-user"></i> View My Profile</a>
                        <?php endif; ?>
                        <a href="user/deactivate_user.php?id=<?= $user['id'] ?>"><i class="fa fa-ban"></i> Deactivate</a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Inactive Users -->
        <div id="inactive-tab" class="card" style="display:none;">
          <table>
            <thead>
              <tr>
                <th>Name:</th>
                <th>Email:</th>
                <th>Barangay:</th>
                <th>Role:</th>
                <th>Date:</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($inactiveUsers)): ?>
                <tr><td colspan="6">No inactive users found.</td></tr>
              <?php else: ?>
                <?php foreach($inactiveUsers as $user): ?>
                  <tr>
                    <td>
                      <img src="<?= $user['profile_pic'] ? '../uploads/'.htmlspecialchars($user['profile_pic']) : '../assets/default.png' ?>">
                      <span class="name"><?= htmlspecialchars($user['first_name'].' '.$user['last_name']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['barangay']) ?></td>
                    <td><?= htmlspecialchars($user['user_type']) ?></td>
                    <td><?= date("n/j/Y", strtotime($user['created_at'])) ?></td>
                    <td class="action-wrapper">
                      <button class="action-btn" onclick="toggleDropdown(<?= $user['id'] ?>)">⋮</button>
                      <div class="dropdown" id="dropdown-<?= $user['id'] ?>">
                        <?php if ($currentUserType === 'CNO'): ?>
                          <a href="view_profile.php?id=<?= $user['id'] ?>"><i class="fa fa-user"></i> View Profile</a>
                        <?php else: ?>
                          <a href="view_profile.php?id=<?= $currentUserId ?>"><i class="fa fa-user"></i> View My Profile</a>
                        <?php endif; ?>
                        <a href="user/activate_user.php?id=<?= $user['id'] ?>"><i class="fa fa-check"></i> Activate</a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </main>
    </div>
  </div>
</body>
</html>
