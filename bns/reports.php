<?php
session_start();
require '../db/config.php'; 

// Enable PDO exceptions for debugging
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type']; // BNS or CNO

// ✅ Activity log function
function logActivity($pdo, $user_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

// ✅ Handle archive action via AJAX
if (isset($_POST['archive_id']) && is_numeric($_POST['archive_id'])) {
    $reportId = (int)$_POST['archive_id'];

    // Check if archive record exists
    $check = $pdo->prepare("SELECT * FROM report_archives WHERE report_id = :rid AND user_id = :uid AND user_type = :utype");
    $check->execute([':rid'=>$reportId, ':uid'=>$userId, ':utype'=>$userType]);
    $archive = $check->fetch();

    if ($archive) {
        $update = $pdo->prepare("UPDATE report_archives SET is_archived=1, is_deleted=0, archived_at=NOW() WHERE report_id=:rid AND user_id=:uid AND user_type=:utype");
        $update->execute([':rid'=>$reportId, ':uid'=>$userId, ':utype'=>$userType]);
    } else {
        $insert = $pdo->prepare("INSERT INTO report_archives (report_id, user_id, user_type, is_archived, is_deleted, archived_at) VALUES (:rid, :uid, :utype, 1, 0, NOW())");
        $insert->execute([':rid'=>$reportId, ':uid'=>$userId, ':utype'=>$userType]);
    }

    logActivity($pdo, $userId, "Archived report (ID: $reportId) as $userType");

    // Return JSON response
    echo json_encode(['success'=>true]);
    exit();
}

// --- Pagination for active reports ---
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ✅ Fetch Pending + Rejected reports for this user
$stmt = $pdo->prepare("
    SELECT r.id, r.report_time, r.report_date, r.status,
           u.username,
           b.title AS report_title,
           b.barangay
    FROM reports r
    JOIN users u ON r.user_id = u.id
    LEFT JOIN bns_reports b ON b.report_id = r.id
    WHERE r.user_id = :user_id
      AND r.status IN ('Pending', 'Rejected')
    ORDER BY r.report_date DESC, r.report_time DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Count total reports
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE user_id=? AND status IN ('Pending','Rejected')");
$totalStmt->execute([$userId]);
$totalReports = $totalStmt->fetchColumn();
$totalPages = ceil($totalReports / $limit);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>CNO NutriMap — Reports</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
.layout { display:flex; height:100vh; flex-direction:column; }
.body-layout { flex:1; display:flex; }
.content { flex:1; padding:15px; display:flex; flex-direction:column; }
.toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
.toolbar-left input { padding:6px 8px; border:1px solid #ccc; border-radius:4px; width:220px; }
.toolbar-right { display:flex; align-items:center; gap:10px; }
.toolbar-right label { font-size:14px; color:#333; margin-right:4px; }
.toolbar-right select { padding:6px; border:1px solid #ccc; border-radius:4px; }
.add-btn { background:#009688; color:#fff; text-decoration:none; padding:8px 14px; border-radius:4px; font-size:14px; display:flex; align-items:center; gap:6px; }
.add-btn:hover { background:#00796b; }
.report-panel { background:#fff; border:1px solid #ccc; border-radius:4px; flex:1; display:flex; flex-direction:column; }
.report-header { display:flex; justify-content:space-between; align-items:center; padding:10px; background:#eee; border-bottom:1px solid #ccc; }
.report-header h3 { margin:0; }
.pagination { display:flex; align-items:center; gap:6px; }
.pagination a { border:1px solid #ccc; background:#fff; padding:5px 10px; cursor:pointer; border-radius:4px; font-size:14px; text-decoration:none; color:#333; }
.pagination a.active { background:#009688; color:#fff; border:none; }
table { width:100%; border-collapse:collapse; font-size:14px; }
th, td { text-align:left; padding:10px; border-bottom:1px solid #eee; }
th { background:#f5f5f5; font-weight:bold; }
.status { padding:3px 8px; border-radius:10px; font-size:12px; color:#fff; }
.status.Pending { background:#ffc107; color:#000; }
.status.Approved { background:#28a745; }
.status.Rejected { background:#dc3545; }
.status.Archived { background:#6c757d; }
.actions a { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:20px; font-size:13px; font-weight:500; text-decoration:none; color:#fff; transition:all 0.3s ease; }
.actions .view { background:#007bff; }
.actions .view:hover { background:#0056b3; }
.actions .edit { background:#28a745; }
.actions .edit:hover { background:#1e7e34; }
.actions .delete { background:#dc3545; }
.actions .delete:hover { background:#a71d2a; }
</style>
<script>
function archiveReport(reportId) {
  if (confirm('Are you sure you want to move this report to Archive?')) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "reports.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
      if(xhr.readyState === 4 && xhr.status === 200){
        const response = JSON.parse(xhr.responseText);
        if(response.success){
          // Remove row instantly
          const row = document.getElementById('report-' + reportId);
          if(row) row.remove();
          // Optionally redirect to archive page (comment out if not needed)
          // window.location.href = 'archive.php';
        } else {
          alert('Failed to archive report');
        }
      }
    };
    xhr.send("archive_id=" + reportId);
  }
}
</script>
</head>
<body>
<div class="layout">
<?php include 'header.php'; ?>

<div class="body-layout">
<main class="content">
<div class="toolbar">
  <div class="toolbar-left">
    <input type="text" placeholder="Search">
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
<h3>Reports</h3>
<div class="pagination">
  <a href="?page=<?= max(1, $page-1) ?>">Prev</a>
  <?php for ($i=1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>" class="<?= $i==$page ? 'active':'' ?>"><?= $i ?></a>
  <?php endfor; ?>
  <a href="?page=<?= min($totalPages, $page+1) ?>">Next</a>
</div>
</div>

<table>
<thead>
<tr>
  <th>User</th>
  <th>Title</th>
  <th>Barangay</th>
  <th>Time</th>
  <th>Date</th>
  <th>Status</th>
  <th>Actions</th>
</tr>
</thead>
<tbody>
<?php if ($reports): ?>
  <?php foreach ($reports as $r): ?>
    <tr id="report-<?= $r['id'] ?>">
      <td><?= htmlspecialchars($r['username']) ?></td>
      <td><?= htmlspecialchars($r['report_title'] ?? '-') ?></td>
      <td><?= htmlspecialchars($r['barangay'] ?? '-') ?></td>
      <td><?= date("h:i a", strtotime($r['report_time'])) ?></td>
      <td><?= date("m/d/Y", strtotime($r['report_date'])) ?></td>
      <td><span class="status <?= htmlspecialchars($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
      <td class="actions">
        <a href="view_report.php?id=<?= $r['id'] ?>" class="view"><i class="fa fa-eye"></i> View</a>
        <a href="report/edit_report.php?id=<?= $r['id'] ?>" class="edit"><i class="fa fa-edit"></i> Edit</a>
        <a href="#" class="delete" onclick="archiveReport(<?= $r['id'] ?>)"><i class="fa fa-archive"></i> Archive</a>
      </td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr><td colspan="7" style="text-align:center; color:#888;">No reports available</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</main>
</div>
</div>
</body>
</html>
