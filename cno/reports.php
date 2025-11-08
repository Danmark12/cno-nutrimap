<?php
session_start();
require '../db/config.php';
require '../otp/mailer.php'; // ✅ Include PHPMailer functions

// --- Handle Approve / Decline actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['report_id'])) {
    $reportId = (int)$_POST['report_id'];
    $action = $_POST['action'];

    if (in_array($action, ['Approved', 'Rejected'])) {
        // ✅ Update report status
        $update = $pdo->prepare("UPDATE reports SET status = :status WHERE id = :id");
        $update->execute([
            ':status' => $action,
            ':id' => $reportId
        ]);

        // ✅ Log activity
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id']; // ✅ this is the CNO admin (sender)

            // Get admin name
            $adminStmt = $pdo->prepare("SELECT CONCAT(first_name, ' ', last_name) AS fullname FROM users WHERE id = ?");
            $adminStmt->execute([$userId]);
            $adminName = $adminStmt->fetchColumn() ?: "CNO Admin";

            $logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, created_at) VALUES (?, ?, NOW())");
            $logStmt->execute([$userId, "$action report ID: $reportId"]);

            // ✅ Get report owner (BNS user)
            $ownerStmt = $pdo->prepare("SELECT user_id FROM reports WHERE id = ?");
            $ownerStmt->execute([$reportId]);
            $reportOwnerId = $ownerStmt->fetchColumn();

            // ✅ Get report title from bns_reports
            $titleStmt = $pdo->prepare("SELECT title FROM bns_reports WHERE report_id = ?");
            $titleStmt->execute([$reportId]);
            $reportTitle = $titleStmt->fetchColumn();
            if (!$reportTitle) {
                $reportTitle = "Untitled Report";
            }

            if ($reportOwnerId) {
                // ✅ Notification type
                $notifType = ($action === 'Approved') ? 'report_approved' : 'report_rejected';

                // ✅ Insert notification for BNS user (in-app)
                $notifMessage = "Your report \"$reportTitle\" has been <b style='color:black;'>$action</b> by <b>$adminName</b>.";
                $notifLink = "view_report.php?id=" . $reportId;

                $notifStmt = $pdo->prepare("
                    INSERT INTO notifications 
                        (user_id, sender_id, receiver_type, type, related_id, message, link, is_read, created_at)
                    VALUES  
                        (:user_id, :sender_id, 'BNS', :type, :related_id, :message, :link, 0, NOW())
                ");
                $notifStmt->execute([
                    ':user_id'    => $reportOwnerId, // ✅ receiver (BNS)
                    ':sender_id'  => $userId,        // ✅ sender (CNO admin)
                    ':type'       => $notifType,
                    ':related_id' => $reportId,
                    ':message'    => $notifMessage,
                    ':link'       => $notifLink
                ]);

                // ✅ Fetch BNS user's info (email and name)
                $bnsStmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
                $bnsStmt->execute([$reportOwnerId]);
                $bns = $bnsStmt->fetch(PDO::FETCH_ASSOC);

                if ($bns && !empty($bns['email'])) {
                    // ✅ Send email using PHPMailer from mailer.php
                    $sent = sendBnsReportNotification(
                        $bns['email'],
                        $bns['first_name'] . ' ' . $bns['last_name'],
                        $reportTitle,
                        $action, // Approved or Rejected
                        $reportId,
                        $adminName // ✅ Added admin name (performedBy)
                    );

                    if ($sent) {
                        error_log("✅ Email sent successfully to {$bns['email']} for report ID $reportId ($action).");
                    } else {
                        error_log("❌ Failed to send email to {$bns['email']} for report ID $reportId ($action). Check mailer.php logs.");
                    }
                } else {
                    error_log("⚠️ No email found for BNS user ID $reportOwnerId (Report ID $reportId).");
                }
            }
        }

    } elseif ($action === 'View') {
        header("Location: view_report_1.php?id=" . $reportId);
        exit;
    }

    // redirect back to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . http_build_query($_GET));
    exit;
}

// --- Pagination setup ---
$limit = 10; // reports per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- Filters ---
$search   = isset($_GET['search']) ? trim($_GET['search']) : '';
$barangay = isset($_GET['barangay']) ? $_GET['barangay'] : 'All';
$sort     = isset($_GET['sort']) ? $_GET['sort'] : 'new';
$tab      = isset($_GET['tab']) ? $_GET['tab'] : 'Pending'; // Pending or Rejected ✅ changed here

// --- Base query (✅ show only submitted reports) ---
$where = ["r.status = :status", "r.is_submitted = 1"];
$params = [':status' => $tab];

if ($search !== '') {
    $where[] = "(u.first_name LIKE :search1 
                 OR u.last_name LIKE :search2 
                 OR b.title LIKE :search3)";
    $params[':search1'] = "%$search%";
    $params[':search2'] = "%$search%";
    $params[':search3'] = "%$search%";
}
if ($barangay !== 'All') {
    $where[] = "u.barangay = :barangay";
    $params[':barangay'] = $barangay;
}

$whereSql = "WHERE " . implode(" AND ", $where);

// --- Sorting ---
switch ($sort) {
    case 'az':
        $orderBy = "ORDER BY fullname ASC";
        break;
    case 'new':
    default:
        $orderBy = "ORDER BY r.report_date DESC, r.report_time DESC";
        break;
}

// --- Fetch reports ---
$sql = "
    SELECT r.id, r.report_time, r.report_date, r.status, r.is_submitted,
           CONCAT(u.first_name, ' ', u.last_name) AS fullname,
           u.profile_pic, u.barangay,
           b.title AS report_title
    FROM reports r
    JOIN users u ON r.user_id = u.id
    LEFT JOIN bns_reports b ON b.report_id = r.id
    $whereSql
    $orderBy
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Count total reports ---
$countSql = "
    SELECT COUNT(*)
    FROM reports r
    JOIN users u ON r.user_id = u.id
    LEFT JOIN bns_reports b ON b.report_id = r.id
    $whereSql
";
$countStmt = $pdo->prepare($countSql);
foreach ($params as $key => $val) {
    $countStmt->bindValue($key, $val);
}
$countStmt->execute();
$totalReports = $countStmt->fetchColumn();
$totalPages = ceil($totalReports / $limit);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>CNO NutriMap — Reports</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body { margin:0; font-family: Arial, Helvetica, sans-serif; background:#f5f5f5; }
    .toolbar { display:flex; justify-content:space-between; align-items:center; padding:10px; background:#fff; border-bottom:1px solid #ddd; }
    .toolbar-left input { padding:6px 8px; border:1px solid #ccc; border-radius:4px; width:220px; }
    .toolbar-right { display:flex; gap:10px; align-items:center; }
    .toolbar-right select { padding:6px; border:1px solid #ccc; border-radius:4px; }

    .report-panel { background:#fff; margin:10px; border-radius:4px; border:1px solid #ddd; }
    .tabs { display:flex; border-bottom:1px solid #ddd; }
    .tabs a { flex:1; padding:10px; text-align:center; text-decoration:none; font-weight:bold; color:#333; background:#f9f9f9; }
    .tabs a.active { background:#fff; border-bottom:3px solid #007bff; color:#007bff; }

    table { width:100%; border-collapse:collapse; font-size:14px; }
    th, td { padding:12px; text-align:left; border-bottom:1px solid #eee; }
    th { background:#f9f9f9; }
    td img { width:32px; height:32px; border-radius:50%; margin-right:8px; vertical-align:middle; }
    .actions form { display:inline; }
    .actions button { border:none; padding:6px 12px; border-radius:4px; cursor:pointer; font-size:12px; margin-right:5px; color:#fff; }
    .actions .view { background:#17a2b8; }
    .actions .decline { background:#dc3545; }
    .actions .approve { background:#28a745; }

    .pagination { display:flex; justify-content:flex-end; padding:10px; gap:5px; }
    .pagination a {
        border:1px solid #ccc; background:#fff; padding:5px 10px; border-radius:4px; text-decoration:none; color:#333; font-size:14px;
    }
    .pagination a.active { background:#007bff; color:#fff; border-color:#007bff; }
    .pagination a.disabled { pointer-events:none; opacity:0.5; color:#999; border-color:#ddd; background:#f9f9f9; }
</style>
</head>
<body>
<div class="layout">
<?php include 'header.php'; ?>


<div class="toolbar">
<form method="get" style="display:flex; width:100%; justify-content:space-between; align-items:center;">
    <div class="toolbar-left">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search">
    <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
    </div>
    <div class="toolbar-right">
    <label>Barangay:</label>
    <select name="barangay" onchange="this.form.submit()">
        <option value="All" <?= $barangay=='All'?'selected':'' ?>>All</option>
        <?php
        $barangays = ['Amoros','Bolisong','Cogon','Himaya','Hinigdaan','Kalabaylabay','Molugan','Pedro S. Baculio','Poblacion','Quibonbon','Sambulawan','San Francisco de Asis','Sinaloc','Taytay','Ulaliman'];
        foreach ($barangays as $brgy) {
            $sel = $barangay==$brgy?'selected':''; 
            echo "<option value=\"$brgy\" $sel>$brgy</option>";
        }
        ?>
    </select>

    <label>Sort by:</label>
    <select name="sort" onchange="this.form.submit()">
        <option value="az" <?= $sort=='az'?'selected':'' ?>>A to Z</option>
        <option value="new" <?= $sort=='new'?'selected':'' ?>>New to Old</option>
    </select>
    </div>
</form>
</div>

<div class="report-panel">
<div class="tabs">
    <a href="?tab=Pending&search=<?=urlencode($search)?>&barangay=<?=urlencode($barangay)?>&sort=<?=$sort?>" class="<?= $tab=='Pending'?'active':'' ?>">Pending Reports</a>
    <a href="?tab=Rejected&search=<?=urlencode($search)?>&barangay=<?=urlencode($barangay)?>&sort=<?=$sort?>" class="<?= $tab=='Rejected'?'active':'' ?>">Rejected Reports</a>
</div>

<!-- ✅ Pagination block -->
<div class="pagination">
    <a href="?page=<?= max(1, $page-1) ?>&tab=<?=$tab?>&search=<?= urlencode($search) ?>&barangay=<?= urlencode($barangay) ?>&sort=<?= $sort ?>" class="<?= ($page <= 1) ? 'disabled' : '' ?>">Prev</a>
    <?php
    // ✅ Show only 5 page numbers at a time
    $start = max(1, $page - 2);
    $end = min($totalPages, $start + 4);
    for ($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?= $i ?>&tab=<?=$tab?>&search=<?= urlencode($search) ?>&barangay=<?= urlencode($barangay) ?>&sort=<?= $sort ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <a href="?page=<?= min($totalPages, $page+1) ?>&tab=<?=$tab?>&search=<?= urlencode($search) ?>&barangay=<?= urlencode($barangay) ?>&sort=<?= $sort ?>" class="<?= ($page >= $totalPages) ? 'disabled' : '' ?>">Next</a>
</div>


<table id="reportTable">
<thead>
<tr>
<th>User</th>
<th>Title</th>
<th>From</th>
<th>Time</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if ($reports): ?>
    <?php foreach ($reports as $row): ?>
    <tr id="report-<?= $row['id'] ?>">
        <td>
        <img src="../uploads/<?= htmlspecialchars($row['profile_pic'] ?: 'default.png') ?>" alt="Profile">
        <?= htmlspecialchars($row['fullname']) ?>
        </td>
        <td><?= htmlspecialchars($row['report_title'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['barangay']) ?></td>
        <td><?= htmlspecialchars($row['report_time']) ?></td>
        <td><?= htmlspecialchars($row['report_date']) ?></td>
        <td class="actions">
            <form method="post" style="display:inline;">
                <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="View" class="view">View</button>
            </form>
            <?php if ($tab == 'Pending'): ?>
            <form method="post" style="display:inline;">
                <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="Rejected" class="decline">Decline</button>
            </form>
            <form method="post" style="display:inline;">
                <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="Approved" class="approve">Approve</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6" style="text-align:center;">No reports found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>

<!-- ✅ Auto-refresh simulation: hides unsubmited reports instantly -->
<script>
setInterval(() => {
  fetch('check_unsubmitted.php')
    .then(res => res.json())
    .then(data => {
      if (Array.isArray(data.unsubmitted)) {
        data.unsubmitted.forEach(id => {
          const row = document.getElementById('report-' + id);
          if (row) row.remove();
        });
      }
    })
    .catch(err => console.error('Polling error:', err));
}, 4000);
</script>

</body>
</html>
