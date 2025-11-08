<?php
session_start();
require '../db/config.php'; 

// ✅ Require login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type']; // 'BNS' or 'CNO'

// --- Pagination setup ---
$limit = 10; // reports per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- Search term ---
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// --- Handle archive action ---
if (isset($_GET['archive_id']) && is_numeric($_GET['archive_id'])) {
    $reportId = (int)$_GET['archive_id'];

    // 🔹 Check if the record exists in report_archives
    $check = $pdo->prepare("
        SELECT * FROM report_archives 
        WHERE report_id = :rid AND user_id = :uid AND user_type = :utype
    ");
    $check->execute([
        'rid' => $reportId,
        'uid' => $userId,
        'utype' => $userType
    ]);
    $existing = $check->fetch();

    if ($existing) {
        // 🔹 Update existing record
        $update = $pdo->prepare("
            UPDATE report_archives 
            SET is_archived = 1, archived_at = NOW() 
            WHERE report_id = :rid AND user_id = :uid AND user_type = :utype
        ");
        $update->execute([
            'rid' => $reportId,
            'uid' => $userId,
            'utype' => $userType
        ]);
    } else {
        // 🔹 Insert new archive record
        $insert = $pdo->prepare("
            INSERT INTO report_archives (report_id, user_id, user_type, is_archived, archived_at) 
            VALUES (:rid, :uid, :utype, 1, NOW())
        ");
        $insert->execute([
            'rid' => $reportId,
            'uid' => $userId,
            'utype' => $userType
        ]);
    }

    // ✅ Redirect to same page (keep search term if any)
    $redirectUrl = $_SERVER['PHP_SELF'] . "?page=" . $page;
    if ($searchTerm !== '') {
        $redirectUrl .= "&search=" . urlencode($searchTerm);
    }
    header("Location: " . $redirectUrl);
    exit();
}

// --- Fetch reports (Approved + search by title) ---
$sqlWhere = "
    r.status = 'Approved'
    AND r.user_id = :uid
    AND r.id NOT IN (
        SELECT report_id FROM report_archives 
        WHERE user_id = :uid2 AND user_type = :utype AND is_archived = 1
    )
";

$params = [
    'uid' => $userId,
    'uid2' => $userId,
    'utype' => $userType
];

// Only filter by title if search term exists
if ($searchTerm !== '') {
    $sqlWhere .= " AND b.title LIKE :search";
    $params['search'] = "%$searchTerm%";
}

$stmt = $pdo->prepare("
    SELECT r.*, u.username, b.title
    FROM reports r
    JOIN users u ON r.user_id = u.id
    LEFT JOIN bns_reports b ON b.report_id = r.id
    WHERE $sqlWhere
    ORDER BY r.report_date DESC, r.report_time DESC
    LIMIT :limit OFFSET :offset
");

foreach ($params as $key => $val) {
    if ($key === 'uid' || $key === 'uid2') {
        $stmt->bindValue(":$key", $val, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(":$key", $val);
    }
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Count total reports for pagination ---
$totalStmt = $pdo->prepare("
    SELECT COUNT(*) FROM reports r
    LEFT JOIN bns_reports b ON b.report_id = r.id
    WHERE $sqlWhere
");
$totalStmt->execute($params);
$totalReports = $totalStmt->fetchColumn();
$totalPages = ceil($totalReports / $limit);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — My Approved Reports</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; height:100vh; flex-direction:column; }
    .body-layout { flex:1; display:flex; }
    .content { flex:1; padding:15px; display:flex; flex-direction:column; }
    .toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .toolbar-left input {
      padding:6px 8px; border:1px solid #ccc; border-radius:4px; width:220px;
    }
    .toolbar-right { display:flex; align-items:center; gap:10px; }
    .toolbar-right label { font-size:14px; color:#333; margin-right:4px; }
    .toolbar-right select {
      padding:6px; border:1px solid #ccc; border-radius:4px;
    }
    .add-btn {
      background:#009688; color:#fff; text-decoration:none;
      padding:8px 14px; border-radius:4px; font-size:14px;
      display:flex; align-items:center; gap:6px;
    }
    .add-btn:hover { background:#00796b; }

    .report-panel { background:#fff; border:1px solid #ccc; border-radius:4px; flex:1; display:flex; flex-direction:column; }
    .report-header {
      display:flex; justify-content:space-between; align-items:center;
      padding:10px; background:#eee; border-bottom:1px solid #ccc;
    }
    .report-header h3 { margin:0; }
    .pagination { display:flex; align-items:center; gap:4px; flex-wrap:wrap; }
    .pagination a {
      border:1px solid #ccc; background:#fff; padding:5px 10px;
      cursor:pointer; border-radius:4px; font-size:14px; text-decoration:none; color:#333;
    }
    .pagination a.active { background:#009688; color:#fff; border:none; }

    table { width:100%; border-collapse:collapse; font-size:14px; }
    th, td { text-align:left; padding:10px; border-bottom:1px solid #eee; }
    th { background:#f5f5f5; font-weight:bold; }
    .status { padding:3px 8px; border-radius:10px; font-size:12px; color:#fff; background:#009688; }
    .actions a {
      display:inline-block; text-decoration:none; padding:5px 10px; border-radius:4px;
      font-size:12px; margin-right:4px; color:#fff;
    }
    .actions .view { background:#007bff; }
    .actions .edit { background:#ffc107; color:#000; }
    .actions .archive { background:#6c757d; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <main class="content">
        <div class="toolbar">
          <div class="toolbar-left">
    <form method="get" action="" style="display:flex;">
        <input type="text" name="search" placeholder="Search Title" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit" style="display:none;"></button>
    </form>
          </div>
          <div class="toolbar-right">
            <label for="sort">Sort by:</label>
            <select id="sort">
              <option value="new">New → Old</option>
              <option value="az">A → Z</option>
            </select>
            <a class="add-btn" href="add_report.php"><i class="fa fa-plus"></i> Add Report</a>
          </div>
        </div>

        <div class="report-panel">
          <div class="report-header">
            <h3>My Approved Report History</h3>
            <div class="pagination">
              <a href="?page=<?= max(1,$page-1) ?>">Prev</a>
              <?php for ($i=1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i==$page ? 'active':'' ?>"><?= $i ?></a>
              <?php endfor; ?>
              <a href="?page=<?= min($totalPages,$page+1) ?>">Next</a>
            </div>
          </div>

          <table>
            <thead>
              <tr>
                <th>User</th>
                <th>Title</th>
                <th>Time</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($reports): ?>
                <?php foreach ($reports as $r): ?>
                  <tr>
                    <td><?= htmlspecialchars($r['username']) ?></td>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= date("h:i a", strtotime($r['report_time'])) ?></td>
                    <td><?= date("m/d/Y", strtotime($r['report_date'])) ?></td>
                    <td><span class="status"><?= htmlspecialchars($r['status']) ?></span></td>
                    <td class="actions">
                      <a href="view_report.php?id=<?= $r['id'] ?>" class="view"><i class="fa fa-eye"></i> View</a>
                      <a href="report/edit_approved.php?id=<?= $r['id'] ?>" class="edit"><i class="fa fa-pen"></i> Edit</a>
                      <a href="?archive_id=<?= $r['id'] ?>" class="archive" onclick="return confirm('Archive this approved report?');"><i class="fa fa-archive"></i> Archive</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" style="text-align:center; color:#888;">No approved reports available</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
