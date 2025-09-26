<?php
session_start();
require '../db/config.php';

// ✅ Ensure table for consolidated reports exists
$pdo->exec("
  CREATE TABLE IF NOT EXISTS consolidated_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year YEAR NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )
");

// ✅ Auto-generate consolidated file when there are approved reports
$currentYear = date('Y');

// Check if consolidated file exists for this year
$stmt = $pdo->prepare("SELECT * FROM consolidated_reports WHERE year = ?");
$stmt->execute([$currentYear]);
$consolidated = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consolidated) {
    $fileName = "consolidated_health_nutrition_{$currentYear}.json";

    $reportsStmt = $pdo->prepare("
        SELECT r.id, r.report_date, r.report_time, r.status,
               u.first_name, u.last_name, u.barangay, b.title, b.year
        FROM reports r
        JOIN users u ON r.user_id = u.id
        JOIN bns_reports b ON b.report_id = r.id
        WHERE r.status = 'Approved' AND b.year = ?
    ");
    $reportsStmt->execute([$currentYear]);
    $reports = $reportsStmt->fetchAll(PDO::FETCH_ASSOC);

    file_put_contents("../exports/$fileName", json_encode($reports, JSON_PRETTY_PRINT));

    $insert = $pdo->prepare("INSERT INTO consolidated_reports (year, file_name) VALUES (?, ?)");
    $insert->execute([$currentYear, $fileName]);
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

    /* Toolbar Card */
    .toolbar-card {
      background:#fff;
      border-radius:12px;
      padding:15px 20px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      box-shadow:0 2px 8px rgba(0,0,0,0.1);
      margin-bottom:20px;
    }
    .toolbar-left {
      display:flex;
      align-items:center;
      gap:15px;
    }
    .toolbar-left input[type="text"] {
      padding:6px 10px;
      border:1px solid #ccc;
      border-radius:6px;
      min-width:200px;
    }
    .toolbar-right {
      display:flex;
      align-items:center;
      gap:8px;
    }
    .toolbar-right select {
      padding:6px 10px;
      border:1px solid #ccc;
      border-radius:6px;
      background:white;
      cursor:pointer;
    }

    /* File list */
    .file-card {
      background:#fff;
      border-radius:12px;
      padding:15px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      box-shadow:0 2px 8px rgba(0,0,0,0.1);
      margin-bottom:10px;
    }
    .file-card span { font-size:14px; color:#333; }
    .export-btn { color:#009688; font-weight:bold; text-decoration:none; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>
    
    <div class="content">
      <!-- Toolbar inside a card -->
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

      <!-- File Cards -->
      <?php foreach ($consolidatedFiles as $file): ?>
        <div class="file-card">
          <span>
            <a href="view_consolidated.php?year=<?= htmlspecialchars($file['year']) ?>" 
               style="text-decoration:none; color:#333; font-weight:bold;">
              Consolidated Health and Nutrition Data (<?= htmlspecialchars($file['year']) ?>)
            </a>
          </span>
          <div>
            <span><?= date("m-d-Y", strtotime($file['created_at'])) ?></span>
            &nbsp; | &nbsp;
            <a class="export-btn" href="../exports/<?= htmlspecialchars($file['file_name']) ?>" download>Export</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
