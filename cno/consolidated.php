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
  
    if (empty($reports)) {
        $checkStmt = $pdo->prepare("SELECT file_name FROM consolidated_reports WHERE year = ?");
        $checkStmt->execute([$year]);
        $oldFile = $checkStmt->fetchColumn();
        if ($oldFile && file_exists("../exports/$oldFile")) {
            unlink("../exports/$oldFile");
        }

        $delStmt = $pdo->prepare("DELETE FROM consolidated_reports WHERE year = ?");
        $delStmt->execute([$year]);
        return;
    }

    if (!is_dir("../exports")) {
        mkdir("../exports", 0777, true);
    }

    file_put_contents("../exports/$fileName", json_encode($reports, JSON_PRETTY_PRINT));

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

// ✅ Get all years
$yearsStmt = $pdo->query("SELECT DISTINCT year FROM bns_reports ORDER BY year DESC");
$years = $yearsStmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($years as $yr) {
    regenerateConsolidatedFile($pdo, $yr);
}

// ✅ Barangay list
$barangays = [
  'Amoros','Bolisong','Cogon','Himaya','Hinigdaan','Kalabaylabay',
  'Molugan','Pedro S. Baculio','Poblacion','Quibonbon','Sambulawan',
  'San Francisco de Asis','Sinaloc','Taytay','Ulaliman'
];

// ✅ Filters
$currentYear = date('Y');
$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear;
$selectedBarangay = isset($_GET['barangay']) ? $_GET['barangay'] : 'All';

// ✅ Fetch consolidated files
$query = "SELECT * FROM consolidated_reports";
$params = [];
if ($selectedYear !== 'All') {
    $query .= " WHERE year = ?";
    $params[] = $selectedYear;
}
$query .= " ORDER BY year DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$consolidatedFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Check approved
$checkApproved = $pdo->query("
    SELECT COUNT(*) FROM reports r
    JOIN users u ON r.user_id = u.id
    JOIN bns_reports b ON b.report_id = r.id
    WHERE r.status = 'Approved'
      AND r.id NOT IN (
          SELECT report_id FROM report_archives
          WHERE user_type = 'CNO' AND is_archived = 1
      )
");
$hasApproved = $checkApproved->fetchColumn();
if ($hasApproved == 0) $consolidatedFiles = [];
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
    .content { flex:1; padding:20px; position:relative; }
    h2 { font-size:20px; font-weight:bold; margin:0; }
    .toolbar-card { background:#fff; border-radius:12px; padding:15px 20px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; flex-wrap:wrap; gap:10px; }
    .toolbar-left { display:flex; align-items:center; gap:15px; flex-wrap:wrap; }
    .toolbar-left input[type="text"], select { padding:6px 10px; border:1px solid #ccc; border-radius:6px; min-width:150px; background:white; }
    .toolbar-right { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .file-card { background:#fff; border-radius:12px; padding:15px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:10px; }
    .file-card span { font-size:14px; color:#333; }
    .export-btn { color:#009688; font-weight:bold; text-decoration:none; cursor:pointer; }
    label { font-weight:normal; color:#333; font-size:14px; }

    /* ✅ Floating Barangay Selection Card */
    .barangay-card {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
      border-radius: 10px;
      padding: 20px;
      width: 350px;
      z-index: 9999;
      display: none;
    }
    .barangay-card h3 { margin-top:0; font-size:18px; text-align:center; }
    .barangay-list { max-height: 200px; overflow-y:auto; border:1px solid #ccc; padding:10px; border-radius:8px; margin-bottom:15px; }
    .barangay-list label { display:block; margin-bottom:5px; }
    .barangay-card button {
      padding: 8px 12px;
      border:none;
      border-radius:6px;
      cursor:pointer;
      font-weight:bold;
    }
    .btn-view { background:#2196f3; color:white; }
    .btn-export { background:#009688; color:white; }
    .btn-cancel { background:#f44336; color:white; }
    .card-actions { text-align:center; display:flex; justify-content:center; gap:10px; }
    .overlay {
      position:fixed;
      top:0; left:0;
      width:100%; height:100%;
      background:rgba(0,0,0,0.5);
      z-index:9998;
      display:none;
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>
    <div class="content">
      <div class="toolbar-card">
        <div class="toolbar-left">
          <h2>Consolidated Data</h2>
          <input type="text" id="searchInput" placeholder="Search">
        </div>

        <div class="toolbar-right">
          <label for="sort">Sort by:</label>
          <select id="sort" onchange="sortFiles(this.value)">
            <option value="new">Newest to Oldest</option>
            <option value="old">Oldest to Newest</option>
          </select>

          <form method="get" style="display:flex; align-items:center; gap:10px; margin-left:10px;">
            <label for="year">Year:</label>
            <select name="year" id="year" onchange="this.form.submit()">
              <option value="All" <?= ($selectedYear === 'All') ? 'selected' : '' ?>>All Years</option>
              <?php foreach ($years as $yr): ?>
                <option value="<?= htmlspecialchars($yr) ?>" <?= ($selectedYear == $yr) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($yr) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <label for="barangay">Barangay:</label>
            <select name="barangay" id="barangay" onchange="this.form.submit()">
              <option value="All" <?= ($selectedBarangay == 'All') ? 'selected' : '' ?>>All</option>
              <?php foreach ($barangays as $b): ?>
                <option value="<?= htmlspecialchars($b) ?>" <?= ($selectedBarangay == $b) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($b) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </form>
        </div>
      </div>

      <!-- ✅ File List -->
      <?php if (!empty($consolidatedFiles)): ?>
        <?php foreach ($consolidatedFiles as $file): ?>
          <div class="file-card" data-year="<?= htmlspecialchars($file['year']) ?>">
            <span>
              <a href="view_consolidated.php?year=<?= htmlspecialchars($file['year']) ?>&barangay=<?= urlencode($selectedBarangay) ?>"
                 style="text-decoration:none; color:#333; font-weight:bold;">
                Consolidated Health and Nutrition Data (<?= htmlspecialchars($file['year']) ?>)
                — <?= htmlspecialchars($selectedBarangay) ?>
              </a>
            </span>
            <div>
              <span><?= isset($file['updated_at']) ? date("m-d-Y", strtotime($file['updated_at'])) : date("m-d-Y") ?></span>
              &nbsp; | &nbsp;
              <a class="export-btn" onclick="openBarangayCard('<?= htmlspecialchars($file['file_name']) ?>', '<?= htmlspecialchars($file['year']) ?>')">Export</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No consolidated files available.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- ✅ Overlay and Barangay Selection Card -->
  <div class="overlay" id="overlay"></div>
  <div class="barangay-card" id="barangayCard">
    <h3>Select Barangays to Include</h3>
    <div>
      <label><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> Select All</label>
    </div>
    <div class="barangay-list" id="barangayList">
      <?php foreach ($barangays as $b): ?>
        <label><input type="checkbox" class="barangay-checkbox" value="<?= htmlspecialchars($b) ?>"> <?= htmlspecialchars($b) ?></label>
      <?php endforeach; ?>
    </div>
    <div class="card-actions">
      <button class="btn-view" onclick="viewSelected()">View</button>
      <button class="btn-export" onclick="exportSelected()">Export</button>
      <button class="btn-cancel" onclick="closeBarangayCard()">Cancel</button>
    </div>
  </div>

  <script>
    let selectedFile = '';
    let selectedYear = '';

    function openBarangayCard(file, year) {
      selectedFile = file;
      selectedYear = year;
      document.getElementById('overlay').style.display = 'block';
      document.getElementById('barangayCard').style.display = 'block';
    }

    function closeBarangayCard() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('barangayCard').style.display = 'none';
    }

    function toggleSelectAll() {
      const checkboxes = document.querySelectorAll('.barangay-checkbox');
      const checked = document.getElementById('selectAll').checked;
      checkboxes.forEach(cb => cb.checked = checked);
    }

    function getSelectedBarangays() {
      const selected = [];
      document.querySelectorAll('.barangay-checkbox:checked').forEach(cb => selected.push(cb.value));
      return selected;
    }

    function viewSelected() {
      const brgys = getSelectedBarangays();
      if (brgys.length === 0) { alert('Please select at least one barangay'); return; }
      // ✅ Show only selected barangays in view_consolidated
      window.location.href = 'view_consolidated.php?year=' + selectedYear + '&barangays=' + encodeURIComponent(brgys.join(','));
    }

    function exportSelected() {
      const brgys = getSelectedBarangays();
      if (brgys.length === 0) { alert('Please select at least one barangay'); return; }
      window.location.href = '../exports/' + selectedFile;
    }

    function sortFiles(order) {
      const container = document.querySelector('.content');
      const cards = Array.from(document.querySelectorAll('.file-card'));
      cards.sort((a, b) => {
        const yearA = parseInt(a.dataset.year);
        const yearB = parseInt(b.dataset.year);
        return order === 'new' ? yearB - yearA : yearA - yearB;
      });
      cards.forEach(c => container.appendChild(c));
    }
  </script>
</body>
</html>
