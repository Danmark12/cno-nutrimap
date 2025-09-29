<?php
// barangay_data.php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// --- Fetch approved reports (grouped by title + year) ---
$stmt = $pdo->prepare("
    SELECT b.title, b.year, COUNT(r.id) as total_reports, MAX(r.report_date) as latest_date
    FROM reports r
    JOIN bns_reports b ON b.report_id = r.id
    WHERE r.status = 'Approved'
      AND r.user_id = :user_id
      AND b.year = 2025
    GROUP BY b.title, b.year
    ORDER BY latest_date DESC
");
$stmt->execute([':user_id' => $userId]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Barangay Reports</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; height:100vh; flex-direction:column; }
    .body-layout { flex:1; display:flex; }
    .content { flex:1; padding:15px; display:flex; flex-direction:column; }

    /* ✅ Toolbar */
    .toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:15px; }
    .toolbar-left input { padding:6px 8px; border:1px solid #ccc; border-radius:4px; width:220px; }
    .toolbar-right { display:flex; align-items:center; gap:10px; }
    .toolbar-right label { font-size:14px; color:#333; margin-right:4px; }
    .toolbar-right select { padding:6px; border:1px solid #ccc; border-radius:4px; }
    .add-btn { background:#009688; color:#fff; text-decoration:none; padding:8px 14px; border-radius:4px; font-size:14px; display:flex; align-items:center; gap:6px; }
    .add-btn:hover { background:#00796b; }

    /* ✅ File List */
    h3.section-title { margin:0 0 10px 0; font-size:18px; }
    .file-list { display:flex; flex-direction:column; gap:10px; }
    .file-card { background:#fff; border:1px solid #ccc; border-radius:6px; padding:15px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 1px 3px rgba(0,0,0,0.1); }
    .file-title { font-size:16px; color:#333; font-weight:600; }
    .file-meta { font-size:13px; color:#555; }
    .file-actions { display:flex; align-items:center; gap:15px; font-size:14px; }
    .file-link { color:#007bff; text-decoration:none; font-weight:500; }
    .file-link:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <main class="content">
        <!-- ✅ Toolbar -->
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

        <!-- ✅ File List -->
        <h3 class="section-title">Barangay Data (2025)</h3>
        <div class="file-list">
          <?php if ($files): ?>
            <?php foreach ($files as $f): ?>
              <div class="file-card">
                <div>
                  <div class="file-title"><?= htmlspecialchars($f['title']) ?></div>
                  <div class="file-meta"><?= $f['total_reports'] ?> reports • Latest: <?= date("M j, Y", strtotime($f['latest_date'])) ?></div>
                </div>
                <div class="file-actions">
                  <a class="file-link" href="report/barangay_data.php?title=<?= urlencode($f['title']) ?>&year=<?= $f['year'] ?>">View</a>
                  <a class="file-link" href="export_file.php?title=<?= urlencode($f['title']) ?>&year=<?= $f['year'] ?>">Export</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:#888;">No approved reports found for 2025</p>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
