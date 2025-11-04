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
$userType = 'CNO';

// ✅ Total users (for admin dashboard)
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// ✅ Admin count
$adminCount = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type='Admin'")->fetchColumn();

// ✅ BNS count
$bnsCount = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type='BNS'")->fetchColumn();

// ✅ Total barangays (fixed to 15)
$totalBarangays = 15;

// ✅ Total reports (exclude archived or deleted by CNO)
$totalReportsStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM reports r
    WHERE NOT EXISTS (
        SELECT 1 
        FROM report_archives a
        WHERE a.report_id = r.id 
          AND a.user_type = 'CNO'
          AND (a.is_archived = 1 OR a.is_deleted = 1)
    )
");
$totalReportsStmt->execute();
$totalReports = $totalReportsStmt->fetchColumn();

// ✅ Approved reports
$approvedReportsStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM reports r
    WHERE r.status = 'Approved'
    AND NOT EXISTS (
        SELECT 1 
        FROM report_archives a
        WHERE a.report_id = r.id 
          AND a.user_type = 'CNO'
          AND (a.is_archived = 1 OR a.is_deleted = 1)
    )
");
$approvedReportsStmt->execute();
$approvedReports = $approvedReportsStmt->fetchColumn();

// ✅ Pending reports
$pendingReportsStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM reports r
    WHERE r.status = 'Pending'
    AND NOT EXISTS (
        SELECT 1 
        FROM report_archives a
        WHERE a.report_id = r.id 
          AND a.user_type = 'CNO'
          AND (a.is_archived = 1 OR a.is_deleted = 1)
    )
");
$pendingReportsStmt->execute();
$pendingReports = $pendingReportsStmt->fetchColumn();

// ✅ Pending reports list
$pendingReportsListStmt = $pdo->prepare("
  SELECT 
    r.id, r.status, r.report_date,
    u.first_name, u.last_name, u.barangay, u.profile_pic,
    b.title
  FROM reports r
  JOIN users u ON r.user_id = u.id
  JOIN bns_reports b ON b.report_id = r.id
  WHERE r.status = 'Pending'
  AND NOT EXISTS (
      SELECT 1 
      FROM report_archives a
      WHERE a.report_id = r.id 
        AND a.user_type = 'CNO'
        AND (a.is_archived = 1 OR a.is_deleted = 1)
  )
  ORDER BY r.report_date DESC
");
$pendingReportsListStmt->execute();
$pendingReportsList = $pendingReportsListStmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Approved reports for sidebar
$approvedReportsListStmt = $pdo->prepare("
  SELECT 
    r.id, r.status, r.report_date, b.title
  FROM reports r
  JOIN bns_reports b ON b.report_id = r.id
  WHERE r.status = 'Approved'
  AND NOT EXISTS (
      SELECT 1 
      FROM report_archives a
      WHERE a.report_id = r.id 
        AND a.user_type = 'CNO'
        AND (a.is_archived = 1 OR a.is_deleted = 1)
  )
  ORDER BY r.report_date DESC
  LIMIT 5
");
$approvedReportsListStmt->execute();
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
    body { margin: 0; font-family: "Segoe UI", Arial, sans-serif; background: #f2f4f6; }
    .layout { display: flex; flex-direction: column; height: 100vh; }
    .body-layout { display: flex; flex: 1; overflow: hidden; }

    /* Sidebar */
    .sidebar { width: 250px; background: #fff; border-right: 1px solid #ddd; display: flex; flex-direction: column; padding: 15px; }
    .sidebar input { width: 90%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 15px; font-size: 14px; }
    .sidebar h3 { font-size: 16px; margin-bottom: 10px; color: #009688; font-weight: 600; }
    .sidebar ul { list-style: none; padding: 0; margin: 0; }
    .sidebar li { font-size: 16px; color: #333; padding: 5px 0; cursor: pointer; }
    .sidebar li:hover { color: #009688; }
    .showmore { margin-top: auto; font-size: 16px; color: #009688; cursor: pointer; margin-bottom: 50px; }

    /* Main Content */
    .content { flex: 1; padding: 20px 30px; display: flex; flex-direction: column; overflow-y: auto; }
    h2 { font-size: 22px; font-weight: bold; margin-bottom: 20px; }

    /* Dashboard Cards */
    .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
    .card { border-radius: 12px; padding: 20px; color: white; display: flex; flex-direction: column; justify-content: flex-start; box-shadow: 0 2px 10px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
    .card i { font-size: 24px; margin-bottom: 10px; }
    .card h4 { font-size: 16px; font-weight: 600; margin: 0 0 10px; }
    .card p { font-size: 30px; font-weight: 700; margin-left: 150px; margin-top: 0px; margin-bottom: 0px; }
    .card.users { background: #003d3c; }
    .card.reports { background: #006d6a; }
    .card.barangay { background: #009688; }

    .card .sub-info { margin-top: 15px; display: flex; flex-direction: column; gap: 8px; align-items: flex-start; }
    .sub-info div { display: flex; align-items: center; gap: 8px; font-size: 14px; }

    .panel { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .panel-header h3 { font-size: 16px; font-weight: 600; margin: 0; }
    .view-all { background:white; border:1px solid #999; padding:4px 10px; font-size:14px; border-radius:4px; cursor:pointer; }

    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th, td { padding: 12px 10px; border-bottom: 1px solid #eee; text-align: left; }
    th { background: #f8f9fa; color: #333; font-weight: 600; }

    .report-row { display: flex; align-items: center; gap: 10px; }
    .report-row img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }

    .status-btn { border-radius: 8px; padding: 4px 10px; font-size: 12px; font-weight: 600; text-transform: capitalize; display: inline-block; }
    .status-btn.Pending { background: #fff3cd; color: #856404; }
    .status-btn.Approved { background: #d4edda; color: #155724; }
    .status-btn.Declined { background: #f8d7da; color: #721c24; }

    tr.clickable { cursor: pointer; transition: background 0.2s; }
    tr.clickable:hover { background: #f5f5f5; }

    /* Button Styling */
    .map-btn {
      background: #fff;
      color: #009688;
      border: none;
      padding: 6px 14px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }
    .map-btn:hover {
      background: rgba(255,255,255,0.9);
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <!-- Sidebar -->
      <aside class="sidebar">
        <h3>Reports</h3>
        <input type="text" placeholder="Find a report..." id="searchSidebar">

        <ul id="sidebarList">
          <?php if (!empty($approvedReportsList)): ?>
            <?php foreach ($approvedReportsList as $approved): ?>
              <li onclick="window.location.href='view_report.php?id=<?= $approved['id'] ?>'">
                <?= htmlspecialchars($approved['title']) ?>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li style="color:#999;">No approved reports</li>
          <?php endif; ?>
        </ul>

        <div class="showmore">Show more</div>
      </aside>

      <!-- Main Content -->
      <main class="content">
        <h2>Dashboard</h2>

        <div class="cards">
          <div class="card users" onclick="window.location.href='users.php'">
            <i class="fa-solid fa-users"></i>
            <h4>Total Users</h4>
            <p><?= $totalUsers ?></p>
            <div class="sub-info">
              <div><i class="fa-solid fa-user-tie"></i> Admin: <?= $adminCount ?></div>
              <div><i class="fa-solid fa-user-nurse"></i> BNS: <?= $bnsCount ?></div>
            </div>
          </div>

          <div class="card reports" onclick="window.location.href='reports.php'">
            <i class="fa-solid fa-file-alt"></i>
            <h4>Total Reports</h4>
            <p><?= $totalReports ?></p>
            <div class="sub-info">
              <div><i class="fa-solid fa-circle-check"></i> Approved: <?= $approvedReports ?></div>
              <div><i class="fa-solid fa-clock"></i> Pending: <?= $pendingReports ?></div>
            </div>
          </div>

          <div class="card barangay">
            <i class="fa-solid fa-map"></i>
            <h4>Total Barangays</h4>
            <p><?= $totalBarangays ?></p>
            <div class="sub-info">
              <button class="map-btn" onclick="window.location.href='mapping.php'">
                <i class="fa-solid fa-location-dot"></i> View Map
              </button>
            </div>
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
                <th>Name</th>
                <th>Title</th>
                <th>Barangay</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($pendingReportsList)): ?>
                <?php foreach($pendingReportsList as $report): ?>
                  <tr class="clickable" onclick="window.location.href='view_report.php?id=<?= $report['id'] ?>'">
                    <td>
                      <div class="report-row">
                        <img src="../uploads/<?= !empty($report['profile_pic']) ? htmlspecialchars($report['profile_pic']) : 'default.png'; ?>" alt="user">
                        <div class="name"><?= htmlspecialchars($report['first_name'].' '.$report['last_name']) ?></div>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($report['title'] ?? 'Untitled') ?></td>
                    <td><?= htmlspecialchars($report['barangay']) ?></td>
                    <td><span class="status-btn <?= htmlspecialchars($report['status']) ?>"><?= htmlspecialchars($report['status']) ?></span></td>
                    <td><?= date('m/d/Y', strtotime($report['report_date'])) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" style="text-align:center;color:#999;">No pending reports</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <!-- ✅ Search filter for Approved Reports only -->
  <script>
    document.getElementById("searchSidebar").addEventListener("keyup", function() {
      const searchValue = this.value.toLowerCase().trim();
      const items = document.querySelectorAll("#sidebarList li");

      items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchValue) || searchValue === "" ? "" : "none";
      });
    });
  </script>
</body>
</html>
