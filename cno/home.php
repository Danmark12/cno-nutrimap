<?php
session_start();
require '../db/config.php'; // adjust path if needed

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Current user
$userId = $_SESSION['user_id'] ?? null;

// ✅ Total users (for admin dashboard)
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// ✅ Total barangays
$totalBarangays = $pdo->query("SELECT COUNT(DISTINCT barangay) FROM users")->fetchColumn();

// ✅ Total reports for this user
$totalReportsStmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE user_id = ?");
$totalReportsStmt->execute([$userId]);
$totalReports = $totalReportsStmt->fetchColumn();

// ✅ Approved reports
$approvedReportsStmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE user_id = ? AND status = 'Approved'");
$approvedReportsStmt->execute([$userId]);
$approvedReports = $approvedReportsStmt->fetchColumn();

// ✅ Pending reports
$pendingReportsStmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE user_id = ? AND status = 'Pending'");
$pendingReportsStmt->execute([$userId]);
$pendingReports = $pendingReportsStmt->fetchColumn();

// ✅ Pending reports list
$pendingReportsListStmt = $pdo->prepare("SELECT * FROM reports WHERE user_id = ? AND status = 'Pending' ORDER BY report_date DESC LIMIT 6");
$pendingReportsListStmt->execute([$userId]);
$pendingReportsList = $pendingReportsListStmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Approved reports list (sidebar)
$approvedReportsListStmt = $pdo->prepare("SELECT * FROM reports WHERE user_id = ? AND status = 'Approved' ORDER BY report_date DESC LIMIT 5");
$approvedReportsListStmt->execute([$userId]);
$approvedReportsList = $approvedReportsListStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .layout { display:flex; flex-direction:column; height:100vh; }
    .body-layout { display:flex; flex:1; }

    /* Sidebar */
    .sidebar {
      width:260px; background:#fff; border-right:1px solid #ddd;
      display:flex; flex-direction:column; padding:15px;
    }
    .sidebar input {
      width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; margin-bottom:20px;
    }
    .sidebar ul { list-style:none; padding:0; margin:0; }
    .sidebar li { padding:6px 0; font-size:14px; color:#333; cursor:pointer; }
    .sidebar li:hover { color:#009688; }
    .showmore { margin-top:auto; font-size:13px; color:#009688; cursor:pointer; }

    /* Main Content */
    .content { flex:1; padding:20px; display:flex; flex-direction:column; }
    h2 { font-size:20px; font-weight:bold; margin-bottom:20px; }

    /* Cards */
    .cards { display:grid; grid-template-columns: repeat(4, 1fr); gap:15px; margin-bottom:20px; }
    .card {
      background:#fff; border-radius:12px; padding:20px;
      display:flex; flex-direction:column; justify-content:center;
      box-shadow:0 2px 8px rgba(0,0,0,0.1);
    }
    .card .label { font-size:14px; color:#555; }
    .card .value { font-size:26px; font-weight:bold; color:#222; }

    /* Panel */
    .panel {
      background:#fff; border-radius:12px; padding:20px;
      box-shadow:0 2px 8px rgba(0,0,0,0.1);
      display:flex; flex-direction:column;
    }
    .panel-header {
      display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;
    }
    .panel-header h3 { margin:0; font-size:16px; }
    .view-all {
      background:#009688; color:white; border:none; border-radius:8px; padding:6px 12px;
      cursor:pointer; font-size:13px;
    }

    /* Table */
    table { width:100%; border-collapse:collapse; font-size:14px; }
    th, td { padding:10px; text-align:left; border-bottom:1px solid #eee; }
    th { font-weight:bold; color:#333; }
    td.status {
      font-weight:bold; padding:4px 8px; border-radius:8px; text-align:center;
    }
    .status.Pending { background:#fff3cd; color:#856404; }
    .status.Approved { background:#d4edda; color:#155724; }
    .status.Rejected { background:#f8d7da; color:#721c24; }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <!-- Sidebar -->
      <aside class="sidebar">
        <input type="text" placeholder="Find a report...">
        <ul>
          <?php foreach($approvedReportsList as $report): ?>
            <li>Report #<?= $report['id'] ?> (<?= htmlspecialchars($report['status']) ?>)</li>
          <?php endforeach; ?>
        </ul>
        <div class="showmore" onclick="window.location.href='report_history.php'">Show more</div>
      </aside>

      <!-- Main Content -->
      <main class="content">
        <h2>Dashboard</h2>
        <div class="cards">
          <div class="card">
            <div class="label">Total Users:</div>
            <div class="value"><?= $totalUsers ?></div>
          </div>
          <div class="card">
            <div class="label">Total Reports:</div>
            <div class="value"><?= $totalReports ?></div>
          </div>
          <div class="card">
            <div class="label">Approved Reports:</div>
            <div class="value"><?= $approvedReports ?></div>
          </div>
          <div class="card">
            <div class="label">Total Barangays:</div>
            <div class="value"><?= $totalBarangays ?></div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">
            <h3>Pending Reports</h3>
            <button class="view-all" onclick="window.location.href='reports.php'">View All</button>
          </div>
          <table>
            <thead>
              <tr>
                <th>Report ID</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($pendingReportsList)): ?>
                <?php foreach($pendingReportsList as $report): ?>
                  <tr>
                    <td><?= $report['id'] ?></td>
                    <td class="status <?= $report['status'] ?>"><?= $report['status'] ?></td>
                    <td><?= $report['report_date'] ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="3" style="text-align:center;color:#999;">No pending reports</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
