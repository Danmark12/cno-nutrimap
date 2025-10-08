<?php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId   = $_SESSION['user_id'];
$userType = 'CNO'; // fixed type for admin

// ✅ Handle Bulk Actions BEFORE fetching reports
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🔹 Restore all archived reports for this CNO only
    if (isset($_POST['restore_all'])) {
        $restoreStmt = $pdo->prepare("
            UPDATE reports r
            JOIN report_archives a ON a.report_id = r.id
            SET r.status = r.prev_status, r.prev_status = NULL, a.is_archived = 0
            WHERE a.user_id = ? AND a.user_type = ? AND a.is_archived = 1
        ");
        $restoreStmt->execute([$userId, $userType]);

        header("Location: ".$_SERVER['PHP_SELF']."?msg=All reports restored");
        exit();
    }

    // 🔹 Soft delete all archived reports for this CNO only
    if (isset($_POST['delete_all'])) {
        $deleteStmt = $pdo->prepare("
            UPDATE report_archives 
            SET is_deleted = 1 
            WHERE user_id = ? AND user_type = ? AND is_archived = 1
        ");
        $deleteStmt->execute([$userId, $userType]);

        header("Location: ".$_SERVER['PHP_SELF']."?msg=All reports deleted");
        exit();
    }
}

// ✅ Fetch archived reports for this admin user only
$stmt = $pdo->prepare("
    SELECT r.id, r.report_date, r.report_time, r.prev_status,
           u.username, b.title, b.barangay, b.year, a.archived_at
    FROM reports r
    JOIN users u ON r.user_id = u.id
    LEFT JOIN bns_reports b ON b.report_id = r.id
    INNER JOIN report_archives a 
      ON a.report_id = r.id 
      AND a.user_id = :uid 
      AND a.user_type = :utype
      AND a.is_archived = 1
      AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
    ORDER BY a.archived_at DESC
");
$stmt->execute(['uid' => $userId, 'utype' => $userType]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Archive</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; height:100vh; flex-direction:column; }
    .body-layout { flex:1; display:flex; }
    .content { flex:1; padding:15px; display:flex; flex-direction:column; }

    .card { background:#fff; border:1px solid #ccc; border-radius:8px; padding:15px; margin-bottom:15px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    .toolbar { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .toolbar-left input { padding:8px 10px; border:1px solid #ccc; border-radius:6px; width:240px; }
    .toolbar-right select, .toolbar-right button { padding:8px; border:1px solid #ccc; border-radius:6px; margin-left:5px; cursor:pointer; }

    .archive-list { display:flex; flex-direction:column; gap:8px; }
    .archive-item {
      background:#fff; border:1px solid #ccc; border-radius:6px; padding:12px;
      display:flex; justify-content:space-between; align-items:center;
      position:relative; box-shadow:0 1px 3px rgba(0,0,0,0.08);
    }
    .archive-item:hover { background:#f0f8ff; cursor:pointer; }
    .archive-title { font-size:15px; font-weight:600; color:#333; }
    .archive-meta { font-size:13px; color:#555; margin-top:3px; }

    /* Three-dot menu */
    .menu-container { position:relative; }
    .menu-btn { background:none; border:none; cursor:pointer; font-size:18px; color:#555; }
    .menu-content {
      display:none; position:absolute; right:0; top:25px; background:#fff; border:1px solid #ccc;
      border-radius:4px; box-shadow:0 2px 6px rgba(0,0,0,0.15); z-index:10; min-width:160px;
    }
    .menu-content a { display:block; padding:8px 12px; font-size:14px; color:#333; text-decoration:none; }
    .menu-content a:hover { background:#f5f5f5; }
    .menu-container.active .menu-content { display:block; }

    .status { padding:3px 8px; border-radius:10px; font-size:12px; color:#fff; }
    .status.Pending { background:#ffc107; color:#000; }
    .status.Approved { background:#28a745; }
    .status.Rejected { background:#dc3545; }
    .status.Archived { background:#6c757d; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>
    <div class="body-layout">
      <div class="content">

        <!-- 🔹 Search + Sort + Bulk Actions -->
        <div class="card">
            <div style="display:flex; align-items:center; flex-wrap:wrap; gap:10px;">
                <h3 style="margin:0;">Archive</h3>
                <div class="toolbar-left" style="position:relative; margin-left:15px;">
                  <i class="fa fa-search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#888;"></i>
                  <input type="text" id="search" placeholder="Search reports..." style="padding-left:30px;">
                </div>
                <div class="toolbar-right" style="margin-left:auto;">
                  <label for="sort">Sort By:</label>
                  <select id="sort">
                    <option value="title">A → Z</option>
                    <option value="date">Newest → Oldest</option>
                  </select>

                  <!-- Bulk Actions -->
                  <form style="display:inline;" method="post" onsubmit="return confirm('Are you sure?');">
                    <button type="submit" name="restore_all"><i class="fa fa-undo"></i> Restore All</button>
                    <button type="submit" name="delete_all"><i class="fa fa-trash"></i> Delete All</button>
                  </form>
                </div>
            </div>
        </div>

        <!-- 🔹 Archived reports list -->
        <div class="archive-list" id="archiveList">
          <?php if ($reports): ?>
            <?php foreach ($reports as $r): ?>
              <div class="archive-item">
                <div>
                  <div class="archive-title"><?= htmlspecialchars($r['title'] ?? 'Untitled Report') ?></div>
                  <div class="archive-meta">
                    User: <?= htmlspecialchars($r['username']) ?> | 
                    Barangay: <?= htmlspecialchars($r['barangay'] ?? '-') ?> | 
                    Year: <?= htmlspecialchars($r['year'] ?? '-') ?> | 
                    <?= $r['report_date'] ? date("m-d-Y", strtotime($r['report_date'])) : '' ?> 
                    <?= $r['report_time'] ? date("h:i a", strtotime($r['report_time'])) : '' ?>
                  </div>
                </div>
                <div class="menu-container" onclick="event.stopPropagation();">
                  <button class="menu-btn"><i class="fa fa-ellipsis-v"></i></button>
                  <div class="menu-content">
                    <a href="view_barangay.php?id=<?= $r['id'] ?>" target="_blank"><i class="fa fa-eye"></i> View</a>
                    <a href="archive/restore_report.php?id=<?= $r['id'] ?>" onclick="return confirm('Restore this report?')"><i class="fa fa-undo"></i> Restore</a>
                    <a href="archive/delete_report.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete this report permanently?')"><i class="fa fa-trash"></i> Delete Permanently</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:#555;">No archived reports found.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

<script>
// Toggle menu
document.querySelectorAll('.menu-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    this.parentElement.classList.toggle('active');
  });
});
document.addEventListener('click', () => {
  document.querySelectorAll('.menu-container').forEach(c => c.classList.remove('active'));
});

// 🔹 Search filter
function filterArchive(searchTerm) {
  const q = searchTerm.toLowerCase();
  document.querySelectorAll('.archive-item').forEach(item => {
    const title = item.querySelector('.archive-title').textContent.toLowerCase();
    const meta = item.querySelector('.archive-meta').textContent.toLowerCase();
    item.style.display = title.includes(q) || meta.includes(q) ? '' : 'none';
  });
}
document.getElementById('search').addEventListener('input', function() {
  filterArchive(this.value);
});

// 🔹 Sort
document.getElementById('sort').addEventListener('change', function() {
  const value = this.value;
  const list = document.getElementById('archiveList');
  const items = Array.from(list.querySelectorAll('.archive-item'));
  items.sort((a, b) => {
    if (value === 'title') {
      return a.querySelector('.archive-title').textContent.localeCompare(
        b.querySelector('.archive-title').textContent
      );
    } else {
      const metaA = a.querySelector('.archive-meta').textContent;
      const metaB = b.querySelector('.archive-meta').textContent;
      const dateMatchA = metaA.match(/\d{2}-\d{2}-\d{4}/);
      const dateMatchB = metaB.match(/\d{2}-\d{2}-\d{4}/);
      const dateA = dateMatchA ? new Date(dateMatchA[0]) : new Date(0);
      const dateB = dateMatchB ? new Date(dateMatchB[0]) : new Date(0);
      return dateB - dateA;
    }
  });
  items.forEach(item => list.appendChild(item));
});
</script>
</body>
</html>
