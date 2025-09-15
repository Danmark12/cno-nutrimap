<?php
// barangay_data.php
session_start();
require '../db/config.php'; // adjust path if needed

// ✅ Create/Update Barangay Data file when a report is approved
// This should normally be handled automatically when CNO approves in your approval logic.
// But for display, we just fetch the latest barangay-year combinations.

$sql = "SELECT b.barangay, b.year, r.title, r.report_date, r.id as report_id
        FROM barangay_bns_reports b
        JOIN reports r ON b.report_id = r.id
        WHERE r.status = 'Approved'
        GROUP BY b.barangay, b.year
        ORDER BY b.year DESC, b.barangay ASC";

$stmt = $pdo->query($sql);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Barangay Data</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin:0;
      font-family: Arial, Helvetica, sans-serif;
      background:#eaeaea;
    }
    .layout {
      display:flex;
      flex-direction:column;
      height:100vh;
    }
    .toolbar {
      background:#fff;
      padding:10px;
      display:flex;
      align-items:center;
      border-bottom:1px solid #ccc;
    }
    .toolbar input[type="text"] {
      padding:6px 10px;
      border:1px solid #ccc;
      border-radius:4px;
      width:220px;
    }
    .toolbar-right {
      margin-left:auto;
      display:flex;
      align-items:center;
      gap:10px;
    }
    .toolbar-right label {
      font-size:14px;
      color:#333;
    }
    .toolbar-right select {
      padding:6px;
      border:1px solid #ccc;
      border-radius:4px;
    }
    .add-btn {
      background:#009688;
      color:#fff;
      padding:8px 14px;
      border:none;
      border-radius:4px;
      font-size:14px;
      cursor:pointer;
    }
    .add-btn:hover {
      background:#00796b;
    }
    .content {
      flex:1;
      padding:20px;
    }
    .card {
      background:#fff;
      padding:15px;
      border-radius:4px;
      box-shadow:0 2px 5px rgba(0,0,0,0.15);
    }
    .card h3 {
      margin:0 0 15px 0;
      font-size:18px;
    }
    .report-row {
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:10px;
      border:1px solid #ddd;
      border-radius:4px;
      background:#fafafa;
      margin-bottom:10px;
    }
    .report-row span {
      font-size:14px;
      color:#333;
    }
    .report-row a {
      color:#009688;
      font-weight:bold;
      text-decoration:none;
    }
    .report-row a:hover {
      text-decoration:underline;
    }
  </style>
</head>
<body>
  <div class="layout">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Toolbar -->
    <div class="toolbar">
      <input type="text" placeholder="Search">
      <div class="toolbar-right">
        <label for="sort">Sort by:</label>
        <select id="sort">
          <option value="new">Newest</option>
          <option value="old">Oldest</option>
          <option value="az">A → Z</option>
        </select>
        <button class="add-btn" onclick="location.href='add_report.php'">
          <i class="fa fa-plus"></i> Add Report
        </button>
      </div>
    </div>

    <!-- Content -->
    <main class="content">
      <div class="card">
        <h3>Barangay Data</h3>
        <?php if (count($reports) > 0): ?>
          <?php foreach ($reports as $report): ?>
            <div class="report-row">
              <span><?= htmlspecialchars($report['barangay']) ?> barangay situational analysis report</span>
              <span><?= date('n-d-Y', strtotime($report['report_date'])) ?></span>
              <a href="export_report.php?id=<?= $report['report_id'] ?>">Export</a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:#888; text-align:center;">No approved reports available</p>
        <?php endif; ?>
      </div>
    </main>
  </div>
</body>
</html>
