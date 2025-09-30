<?php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Handle archive action
if (isset($_GET['archive_id']) && is_numeric($_GET['archive_id'])) {
    $reportId = (int)$_GET['archive_id'];

    // ✅ Update status to Archived only if currently Approved
    $stmt = $pdo->prepare("UPDATE reports SET prev_status = status, status = 'Archived' WHERE id = ? AND status = 'Approved'");
    $stmt->execute([$reportId]);

    header("Location: barangay_data.php");
    exit();
}

// --- Filters ---
$search = $_GET['search'] ?? '';
$barangay_filter = $_GET['barangay'] ?? '';
$year_filter = $_GET['year'] ?? '';
$sort = $_GET['sort'] ?? 'date';

// --- Build query ---
$sql = "
    SELECT r.id, r.report_date, r.report_time, b.title, b.barangay, b.year
    FROM reports r
    INNER JOIN bns_reports b ON b.report_id = r.id
    WHERE r.status = 'Approved'
";

$params = [];

// search filter
if ($search) {
    $sql .= " AND b.title LIKE :search";
    $params[':search'] = "%$search%";
}

// barangay filter
if ($barangay_filter) {
    $sql .= " AND b.barangay = :barangay";
    $params[':barangay'] = $barangay_filter;
}

// year filter
if ($year_filter) {
    $sql .= " AND b.year = :year";
    $params[':year'] = $year_filter;
}

// sorting
if ($sort === 'name') {
    $sql .= " ORDER BY b.title ASC";
} else {
    $sql .= " ORDER BY b.year DESC, r.report_date DESC, r.report_time DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Barangay Files</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; color:#333; }
    .header { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; background:#fff; border-bottom:1px solid #ddd; }
    .header-left { display:flex; align-items:center; gap:10px; font-weight:bold; }
    .header-left span { color:#009688; }
    .header-center { flex:1; display:flex; justify-content:center; }
    .header-center input { width:250px; padding:6px 8px; border:1px solid #ccc; border-radius:4px; }
    .header-right { display:flex; align-items:center; }
    .header-right i { font-size:18px; cursor:pointer; }
    .container { padding:15px; }
    h2 { margin:0 0 10px 0; font-size:18px; }
    .toolbar { display:flex; align-items:center; gap:10px; margin-bottom:15px; flex-wrap:wrap; }
    .toolbar input, .toolbar select { padding:6px 8px; border:1px solid #ccc; border-radius:4px; }
    .barangay-section { margin-top:25px; }
    .barangay-title { font-weight:bold; margin:15px 0 8px; font-size:16px; color:#009688; }
    .year-title { margin:10px 0; font-weight:bold; color:#444; }
    .card { background:#fff; border-radius:4px; box-shadow:0 1px 3px rgba(0,0,0,0.1); padding:12px 16px; margin-bottom:8px;
            display:flex; justify-content:space-between; align-items:center; font-size:14px; }
    .card-title { font-weight:500; }
    .card-right { display:flex; align-items:center; gap:15px; }
    .export-link { color:#009688; font-weight:bold; text-decoration:none; }
    .export-link:hover { text-decoration:underline; }
    .btn-export { background:#009688; color:#fff; border:none; padding:6px 12px; border-radius:4px; cursor:pointer; }
    .btn-export:hover { background:#00796b; }
    .archive-link { color:#dc3545; font-weight:bold; text-decoration:none; }
    .archive-link:hover { text-decoration:underline; }
  </style>
</head>
<body>
<div class="layout">
<?php include 'header.php'; ?>

  <!-- Content -->
  <div class="container">
    <h2>Barangay Files</h2>

    <!-- Toolbar -->
    <form method="get" class="toolbar">
      <input type="text" name="search" placeholder="Search" value="<?= htmlspecialchars($search) ?>">
      <select name="barangay" onchange="this.form.submit()">
        <option value="">All Barangays</option>
        <?php
        $barangays = [
          'Amoros','Bolisong','Cogon','Himaya','Hinigdaan',
          'Kalabaylabay','Molugan','Pedro S. Baculio','Poblacion',
          'Quibonbon','Sambulawan','San Francisco de Asis',
          'Sinaloc','Taytay','Ulaliman'
        ];
        foreach ($barangays as $b) {
            $sel = ($barangay_filter == $b) ? "selected" : "";
            echo "<option value=\"$b\" $sel>$b</option>";
        }
        ?>
      </select>
      <select name="year" onchange="this.form.submit()">
        <option value="">All Years</option>
        <?php
        $years = $pdo->query("SELECT DISTINCT year FROM bns_reports ORDER BY year DESC")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($years as $y) {
            $sel = ($year_filter == $y) ? "selected" : "";
            echo "<option value=\"$y\" $sel>$y</option>";
        }
        ?>
      </select>
      <label for="sort">Sort by:</label>
      <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="date" <?= $sort=="date"?"selected":"" ?>>A - Z</option>
        <option value="name" <?= $sort=="name"?"selected":"" ?>>New - Old</option>
      </select>
      <button type="submit" style="display:none;"></button>
    </form>

    <!-- ✅ Export Button (only if barangay + year selected) -->
    <?php if ($barangay_filter && $year_filter): ?>
      <form method="post" action="export_barangay.php" style="margin-bottom:15px;">
        <input type="hidden" name="barangay" value="<?= htmlspecialchars($barangay_filter) ?>">
        <input type="hidden" name="year" value="<?= htmlspecialchars($year_filter) ?>">
        <button type="submit" class="btn-export"><i class="fas fa-file-pdf"></i> Export Barangay File</button>
      </form>
    <?php endif; ?>

    <!-- Cards -->
    <?php if ($reports): ?>
      <?php
        // ✅ Group by Barangay + Year automatically
        $grouped = [];
        foreach ($reports as $row) {
            $grouped[$row['barangay']][$row['year']][] = $row;
        }

        foreach ($grouped as $brgy => $years) {
            echo "<div class='barangay-section'>";
            echo "<div class='barangay-title'>" . htmlspecialchars($brgy) . "</div>";
            foreach ($years as $yr => $rows) {
                echo "<div class='year-title'>Year $yr</div>";
                foreach ($rows as $row) { ?>
                  <div class="card">
                    <div class="card-title">
                      <?= htmlspecialchars($row['title']) ?>
                    </div>
                    <div class="card-right">
                      <div><?= date("M d, Y", strtotime($row['report_date'])) ?></div>
                      <a href="view_barangay.php?id=<?= $row['id'] ?>" class="export-link">View</a>
                      <a href="barangay_data.php?archive_id=<?= $row['id'] ?>" class="archive-link" onclick="return confirm('Are you sure you want to archive this file?')">
                        <i class="fa fa-archive"></i> Archive
                      </a>
                    </div>
                  </div>
        <?php   }
            }
            echo "</div>";
        }
      ?>
    <?php else: ?>
      <p>No approved reports found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
