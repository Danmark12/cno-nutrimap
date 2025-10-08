<?php
// cno_reports.php
session_start();
require '../db/config.php';

// ✅ Require login & check CNO role
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'CNO') {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Fetch pending reports
$stmt = $pdo->query("
    SELECT r.id, r.title, r.report_date, r.report_time, u.first_name, u.last_name, u.barangay 
    FROM reports r
    JOIN users u ON r.user_id = u.id
    WHERE r.status = 'Pending'
    ORDER BY r.report_date DESC, r.report_time DESC
");
$pendingReports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CNO - Pending Reports</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- ✅ use same CSS -->
    <style>
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .card-header {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
        }
        .btn-approve { background: #28a745; }
        .btn-reject { background: #dc3545; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidemenu.php'; ?>

    <div class="main-content">
        <h2>📑 Pending Reports</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="background:#d4edda;color:#155724;padding:10px;margin:10px 0;border-radius:5px;">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (count($pendingReports) > 0): ?>
            <?php foreach ($pendingReports as $report): ?>
                <div class="card">
                    <div class="card-header"><?= htmlspecialchars($report['title']) ?></div>
                    <p><b>Submitted by:</b> <?= htmlspecialchars($report['first_name'] . " " . $report['last_name']) ?> (<?= $report['barangay'] ?>)</p>
                    <p><b>Date:</b> <?= $report['report_date'] ?> at <?= $report['report_time'] ?></p>
                    
                    <a href="approve_report.php?id=<?= $report['id'] ?>" class="btn btn-approve">✅ Approve</a>
                    <a href="reject_report.php?id=<?= $report['id'] ?>" class="btn btn-reject">❌ Reject</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No pending reports at the moment ✅</p>
        <?php endif; ?>
    </div>
</body>
</html>
