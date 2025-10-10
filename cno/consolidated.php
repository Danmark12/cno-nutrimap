<?php
session_start();
require '../db/config.php';

// ✅ Ensure table for consolidated reports exists
$pdo->exec("
  CREATE TABLE IF NOT EXISTS consolidated_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year YEAR NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )
");

// ✅ Function: regenerate consolidated file for a given year
function regenerateConsolidatedFile($pdo, $year) {
    $fileName = "consolidated_health_nutrition_{$year}.json";

    // ✅ Updated query — exclude reports archived by CNO
    $reportsStmt = $pdo->prepare("
        SELECT r.id, r.report_date, r.report_time, r.status,
               u.first_name, u.last_name, u.barangay, b.title, b.year, b.*
        FROM reports r
        JOIN users u ON r.user_id = u.id
        JOIN bns_reports b ON b.report_id = r.id
        WHERE r.status = 'Approved'
          AND b.year = ?
          AND r.id NOT IN (
              SELECT report_id FROM report_archives
              WHERE user_type = 'CNO' AND is_archived = 1
          )
    ");
    $reportsStmt->execute([$year]);
    $reports = $reportsStmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ If no approved (and unarchived) reports, delete consolidated entry and file
    if (empty($reports)) {
        // Delete file from exports folder if exists
        $checkStmt = $pdo->prepare("SELECT file_name FROM consolidated_reports WHERE year = ?");
        $checkStmt->execute([$year]);
        $oldFile = $checkStmt->fetchColumn();
        if ($oldFile && file_exists("../exports/$oldFile")) {
            unlink("../exports/$oldFile");
        }

        // Delete from DB table
        $delStmt = $pdo->prepare("DELETE FROM consolidated_reports WHERE year = ?");
        $delStmt->execute([$year]);
        return;
    }

    // ✅ Ensure exports folder exists
    if (!is_dir("../exports")) {
        mkdir("../exports", 0777, true);
    }

    // ✅ Save consolidated JSON
    file_put_contents("../exports/$fileName", json_encode($reports, JSON_PRETTY_PRINT));

    // ✅ Insert or update consolidated_reports table
    $checkStmt = $pdo->prepare("SELECT id FROM consolidated_reports WHERE year = ?");
    $checkStmt->execute([$year]);
    $exists = $checkStmt->fetchColumn();

    if ($exists) {
        $update = $pdo->prepare("UPDATE consolidated_reports SET file_name = ? WHERE year = ?");
        $update->execute([$fileName, $year]);
    } else {
        $insert = $pdo->prepare("INSERT INTO consolidated_reports (year, file_name) VALUES (?, ?)");
        $insert->execute([$year, $fileName]);
    }
}

// ✅ Find all distinct years in bns_reports
$yearsStmt = $pdo->query("
    SELECT DISTINCT year FROM bns_reports
");
$years = $yearsStmt->fetchAll(PDO::FETCH_COLUMN);

// ✅ Regenerate consolidated files for each year
foreach ($years as $yr) {
    regenerateConsolidatedFile($pdo, $yr);
}

// ✅ Fetch all consolidated files for listing
$stmt = $pdo->query("SELECT * FROM consolidated_reports ORDER BY year DESC");
$consolidatedFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Consolidated Data</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; flex-direction:column; height:100vh; }
    .content { flex:1; padding:20px; }
    h2 { font-size:20px; font-weight:bold; margin:0; }
    .toolbar-card { background:#fff; border-radius:12px; padding:15px 20px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; }
    .toolbar-left { display:flex; align-items:center; gap:15px; }
    .toolbar-left input[type="text"] { padding:6px 10px; border:1px solid #ccc; border-radius:6px; min-width:200px; }
    .toolbar-right { display:flex; align-items:center; gap:8px; }
    .toolbar-right select { padding:6px 10px; border:1px solid #ccc; border-radius:6px; background:white; cursor:pointer; }
    .file-card { background:#fff; border-radius:12px; padding:15px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:10px; }
    .file-card span { font-size:14px; color:#333; }
    .export-btn { color:#009688; font-weight:bold; text-decoration:none; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>
    <div class="content">
      <div class="toolbar-card">
        <div class="toolbar-left">
          <h2>Consolidated Data</h2>
          <input type="text" placeholder="Search">
        </div>
        <div class="toolbar-right">
          <label for="sort">Sort by:</label>
          <select id="sort">
            <option value="new">Newest to Oldest</option>
            <option value="old">Oldest to Newest</option>
          </select>
        </div>
      </div>

      <?php if (!empty($consolidatedFiles)): ?>
        <?php foreach ($consolidatedFiles as $file): ?>
          <div class="file-card">
            <span>
              <a href="view_consolidated.php?year=<?= htmlspecialchars($file['year']) ?>" 
                 style="text-decoration:none; color:#333; font-weight:bold;">
                Consolidated Health and Nutrition Data (<?= htmlspecialchars($file['year']) ?>)
              </a>
            </span>
            <div>
              <span>
                <?= isset($file['updated_at']) ? date("m-d-Y", strtotime($file['updated_at'])) : date("m-d-Y") ?>
              </span>
              &nbsp; | &nbsp;
              <a class="export-btn" href="../exports/<?= htmlspecialchars($file['file_name']) ?>" download>Export</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No consolidated files available. No approved reports found.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
