<?php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Reports</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin:0;
      font-family: Arial, Helvetica, sans-serif;
      background:#f5f5f5;
    }
    .layout {
      display:flex;
      height:100vh;
      flex-direction:column;
    }
    .body-layout {
      flex:1;
      display:flex;
    }
    .content {
      flex:1;
      padding:15px;
      display:flex;
      flex-direction:column;
    }
    .toolbar {
      display:flex;
      align-items:center;
      justify-content:space-between;
      margin-bottom:10px;
    }
    .toolbar-left input {
      padding:6px 8px;
      border:1px solid #ccc;
      border-radius:4px;
      width:220px;
    }
    .toolbar-right {
      display:flex;
      align-items:center;
      gap:10px;
    }
    .toolbar-right label {
      font-size:14px;
      color:#333;
      margin-right:4px;
    }
    .toolbar-right select {
      padding:6px;
      border:1px solid #ccc;
      border-radius:4px;
    }
    .add-btn {
      background:#009688;
      color:#fff;
      text-decoration:none;
      padding:8px 14px;
      border-radius:4px;
      font-size:14px;
      display:flex;
      align-items:center;
      gap:6px;
    }
    .add-btn:hover {
      background:#00796b;
    }

    .report-panel {
      background:#fff;
      border:1px solid #ccc;
      border-radius:4px;
      flex:1;
      display:flex;
      flex-direction:column;
    }
    .report-header {
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:10px;
      background:#eee;
      border-bottom:1px solid #ccc;
    }
    .report-header h3 {
      margin:0;
    }
    /* Pagination top */
    .pagination {
      display:flex;
      align-items:center;
      gap:6px;
    }
    .pagination button {
      border:1px solid #ccc;
      background:#fff;
      padding:5px 10px;
      cursor:pointer;
      border-radius:4px;
      font-size:14px;
    }
    .pagination button.active {
      background:#009688;
      color:#fff;
      border:none;
    }
    table {
      width:100%;
      border-collapse:collapse;
      font-size:14px;
    }
    th, td {
      text-align:left;
      padding:10px;
      border-bottom:1px solid #eee;
    }
    th {
      background:#f5f5f5;
      font-weight:bold;
    }
    .status {
      padding:3px 8px;
      border-radius:10px;
      font-size:12px;
      color:#fff;
    }
    .status.pending { background:#009688; }
    .actions button {
      border:none;
      padding:5px 10px;
      border-radius:4px;
      cursor:pointer;
      font-size:12px;
      margin-right:4px;
      color:#fff;
    }
    .actions .view { background:#007bff; }
    .actions .edit { background:#28a745; }
    .actions .delete { background:#dc3545; }
  </style>
</head>
<body>
  <div class="layout">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <!-- No sidebar -->
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
            <!-- Add Report button with link -->
            <a class="add-btn" href="add_report.php"><i class="fa fa-plus"></i> Add Report</a>
          </div>
        </div>

        <div class="report-panel">
          <div class="report-header">
            <h3>Report</h3>
            <div class="pagination">
              <button>Prev</button>
              <button class="active">1</button>
              <button>Next</button>
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
              <!-- Empty table when no reports -->
              <tr><td colspan="6" style="text-align:center; color:#888;">No reports available</td></tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>
</body>
</html>
