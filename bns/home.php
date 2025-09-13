<?php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CNO NutriMap — Dashboard</title>
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
  /* Body layout */
  .body-layout {
    display:flex;
    flex:1;
  }
  /* Sidebar */
  .sidebar {
    width:250px;
    background:#f9f9f9;
    border-right:1px solid #ccc;
    padding:15px;
    display:flex;
    flex-direction:column;
  }
  .myreports-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
    font-weight:bold;
  }
  .new-btn {
    background:#009688;
    color:white;
    border:none;
    padding:4px 10px;
    border-radius:4px;
    cursor:pointer;
    font-size:13px;
    display:flex;
    align-items:center;
  }
  .new-btn i { margin-right:5px; }
  .sidebar .searchbox {
    margin-bottom:20px;
  }
  .sidebar .searchbox input {
    width:91%;
    padding:6px 10px;
  }
  .showmore {
    margin-top:auto;
    font-size:14px;
    color:#333;
    cursor:pointer;
  }
  /* Main content */
  .content {
    flex:1;
    padding:15px;
    display:flex;
    flex-direction:column;
  }
  .content h2 {
    margin:0 0 15px 0;
    font-size:18px;
  }
  /* Dashboard cards */
  .cards {
    display:flex;
    gap:15px;
    margin-bottom:20px;
  }
  .card {
    flex:1;
    color:white;
    padding:15px;
    border-radius:4px;
    cursor:pointer;
  }
  .card .title { font-size:14px; }
  .card .number { font-size:20px; font-weight:bold; }
  .card.total { background:#003d3c; }
  .card.approved { background:#006d6a; }
  .card.pending { background:#009688; }
  /* Pending reports */
  .panel {
    background:white;
    border:1px solid #ccc;
    border-radius:4px;
    padding:15px;
    flex:1;
    display:flex;
    flex-direction:column;
  }
  .panel-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
  }
  .panel-header h3 { margin:0; font-size:16px; }
  .view-all {
    background:white;
    border:1px solid #999;
    padding:4px 10px;
    font-size:14px;
    border-radius:4px;
    cursor:pointer;
  }
  table {
    width:100%;
    border-collapse:collapse;
    font-size:14px;
  }
  th {
    text-align:left;
    padding:8px;
    font-weight:bold;
    border-bottom:1px solid #ccc;
  }
  tbody tr { height:35px; border-bottom:1px solid #eee; }
  tbody td { padding:8px; color:#888; }
  </style>
</head>
<body>
  <div class="layout">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <div class="body-layout">
      <!-- Sidebar -->
      <aside class="sidebar">
        <div class="myreports-header">
          <span>My reports</span>
          <button class="new-btn" id="newBtn"><i class="fa fa-plus"></i> New</button>
        </div>
        <div class="searchbox">
          <input type="text" placeholder="Find a report...">
        </div>
        <div class="showmore" id="showMoreBtn">Show more:</div>
      </aside>

      <!-- Main -->
      <main class="content">
        <h2>Dashboard</h2>
        <div class="cards">
          <div class="card total" id="totalCard">
            <div class="title">Total Reports:</div>
            <div class="number">0</div>
          </div>
          <div class="card approved" id="approvedCard">
            <div class="title">Approved:</div>
            <div class="number">0</div>
          </div>
          <div class="card pending" id="pendingCard">
            <div class="title">Pending:</div>
            <div class="number">0</div>
          </div>
        </div>
        <div class="panel">
          <div class="panel-header">
            <h3>Pending Reports</h3>
            <button class="view-all" id="viewAllBtn">View All</button>
          </div>
          <table>
            <thead>
              <tr>
                <th>Name:</th>
                <th>Title:</th>
                <th>Barangay:</th>
                <th>Status:</th>
                <th>Time:</th>
                <th>Date:</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
              <tr><td colspan="6"></td></tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <!-- JS for button actions -->

</body>
</html>
