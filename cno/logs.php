<?php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'] ?? null;

// --- Pagination ---
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // prevent negative page
$offset = ($page - 1) * $limit;

// --- Filters ---
$search = $_GET['search'] ?? '';
$roleFilter = $_GET['role'] ?? 'All';
$sort = $_GET['sort'] ?? 'new';

// --- Query ---
$query = "
    SELECT al.id, al.action, al.details, al.created_at,
           u.first_name, u.last_name, u.user_type, u.barangay
    FROM activity_logs al
    JOIN users u ON al.user_id = u.id
    WHERE 1=1
";

$params = [];

// ✅ Updated search: only first_name, last_name, and action
if (!empty($search)) {
    $query .= " AND (u.first_name LIKE :search1 
                     OR u.last_name LIKE :search2 
                     OR al.action LIKE :search3)";
    $params[':search1'] = "%$search%";
    $params[':search2'] = "%$search%";
    $params[':search3'] = "%$search%";
}

if ($roleFilter !== 'All') {
    $query .= " AND u.user_type = :roleFilter";
    $params[':roleFilter'] = $roleFilter;
}

switch ($sort) {
    case 'az': $query .= " ORDER BY u.first_name ASC"; break;
    case 'old': $query .= " ORDER BY al.created_at ASC"; break;
    case 'new':
    default: $query .= " ORDER BY al.created_at DESC"; break;
}

// ✅ Inject LIMIT and OFFSET as integers directly
$query .= " LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);

// Bind search and role parameters only
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Count total
$countQuery = "
    SELECT COUNT(*) FROM activity_logs al
    JOIN users u ON al.user_id = u.id
    WHERE 1=1
";

$countParams = [];

// ✅ Updated count search: only first_name, last_name, and action
if (!empty($search)) {
    $countQuery .= " AND (u.first_name LIKE :search1 
                          OR u.last_name LIKE :search2 
                          OR al.action LIKE :search3)";
    $countParams[':search1'] = "%$search%";
    $countParams[':search2'] = "%$search%";
    $countParams[':search3'] = "%$search%";
}

if ($roleFilter !== 'All') {
    $countQuery .= " AND u.user_type = :roleFilter";
    $countParams[':roleFilter'] = $roleFilter;
}

$countStmt = $pdo->prepare($countQuery);
foreach ($countParams as $key => $val) {
    $countStmt->bindValue($key, $val);
}
$countStmt->execute();
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

// --- Pagination display range ---
$startPage = max(1, $page - 2);
$endPage = min($totalPages, $startPage + 4);
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Logs</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; flex-direction:column; height:100vh; }
    .body-layout { display:flex; flex:1; }
    .content { flex:1; padding:20px; display:flex; flex-direction:column; }
    .panel { background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; }
    .panel-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap:wrap; }
    .filters { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; }
    .search-bar input { width:250px; padding:6px 10px; border:1px solid #ccc; border-radius:6px; }
    .filter-options { display:flex; gap:10px; }
    .filter-options select { padding:6px; border:1px solid #ccc; border-radius:6px; }
    table { width:100%; border-collapse:collapse; font-size:14px; }
    th, td { padding:10px; border-bottom:1px solid #eee; }
    th { font-weight:bold; color:#333; }
    .pagination { display:flex; align-items:center; gap:5px; flex-wrap:wrap; }
    .pagination a {
      padding:6px 12px; border:1px solid #ccc; border-radius:6px;
      background:#fff; font-size:13px; text-decoration:none; color:#333;
    }
    .pagination .active { background:#009688; color:#fff; }
    .pagination a.disabled { color:#aaa; pointer-events:none; background:#f9f9f9; }
    .page-info { font-size:13px; color:#666; margin-left:10px; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <main class="content">

        <!-- Card 1: Search + Filters -->
        <div class="panel">
          <form method="get" class="filters">
<div class="search-bar">
  <input type="text" name="search" placeholder="Search by First or Last Name"
         value="<?= htmlspecialchars($search) ?>"
         onkeypress="if(event.key==='Enter'){this.form.submit();}">
</div>

            <div class="filter-options">
              <select name="role" onchange="this.form.submit()">
                <option value="All" <?= $roleFilter==='All'?'selected':'' ?>>All</option>
                <option value="BNS" <?= $roleFilter==='BNS'?'selected':'' ?>>BNS</option>
                <option value="CNO" <?= $roleFilter==='CNO'?'selected':'' ?>>CNO</option>
              </select>
              <select name="sort" onchange="this.form.submit()">
                <option value="new" <?= $sort==='new'?'selected':'' ?>>New to Old</option>
                <option value="old" <?= $sort==='old'?'selected':'' ?>>Old to New</option>
                <option value="az" <?= $sort==='az'?'selected':'' ?>>A–Z</option>
              </select>
            </div>
          </form>
        </div>

        <!-- Card 2: Logs Table -->
        <div class="panel">
          <div class="panel-header">
            <h3>Logs</h3>
            <div class="pagination">
              <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&role=<?= $roleFilter ?>&sort=<?= $sort ?>"
                 class="<?= $page<=1?'disabled':'' ?>">Prev</a>

              <?php for ($i=$startPage; $i<=$endPage; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= $roleFilter ?>&sort=<?= $sort ?>"
                   class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
              <?php endfor; ?>

              <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&role=<?= $roleFilter ?>&sort=<?= $sort ?>"
                 class="<?= $page>=$totalPages?'disabled':'' ?>">Next</a>

              <span class="page-info">
                Page <?= $page ?> of <?= $totalPages ?>
              </span>
            </div>
          </div>

          <table>
            <thead>
              <tr>
                <th>Date & Time</th>
                <th>User</th>
                <th>Role</th>
                <th>Barangay</th>
                <th>Action</th>
                <th>Details</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($logs) > 0): ?>
                <?php foreach ($logs as $log): ?>
                  <tr>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                    <td><?= htmlspecialchars($log['first_name'].' '.$log['last_name']) ?></td>
                    <td><?= htmlspecialchars($log['user_type']) ?></td>
                    <td><?= htmlspecialchars($log['barangay']) ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= htmlspecialchars($log['details']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No logs found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </main>
    </div>
  </div>
</body>
</html>
