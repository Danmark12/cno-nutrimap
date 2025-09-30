<?php
// export_report.php
session_start();
require '../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Get report id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid report ID.");
}
$reportId = (int) $_GET['id'];

// ✅ Load report
$stmt = $pdo->prepare("
    SELECT r.*, u.first_name, u.last_name, u.barangay
    FROM reports r
    JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
");
$stmt->execute([$reportId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    die("Report not found.");
}

// ✅ Load BNS data
$stmt = $pdo->prepare("SELECT * FROM bns_reports WHERE report_id = ?");
$stmt->execute([$reportId]);
$bns = $stmt->fetch(PDO::FETCH_ASSOC);
$has_bns = $bns ? true : false;

// ✅ Helper function
function val($arr, $key, $format = '') {
    if (!isset($arr[$key])) return '—';
    $v = $arr[$key];
    if ($format === 'int') return number_format((int)$v);
    if ($format === 'dec2') return number_format((float)$v, 2);
    if ($format === 'pct') return number_format((float)$v, 2) . '%';
    return htmlspecialchars($v);
}

// ✅ Build HTML inline (instead of include)
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Report <?= $reportId ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h1, h2, h3 { color: #009688; margin-bottom: 8px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
        th, td { border: 1px solid #444; padding: 6px; font-size: 12px; }
        th { background: #f0f0f0; text-align: left; }
        .meta { margin-bottom: 20px; }
        .meta div { margin: 4px 0; }
    </style>
</head>
<body>
    <h1>Barangay Nutrition Report</h1>
    <div class="meta">
        <div><strong>Report ID:</strong> <?= $reportId ?></div>
        <div><strong>Barangay:</strong> <?= htmlspecialchars($row['barangay']) ?></div>
        <div><strong>Created by:</strong> <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></div>
        <div><strong>Date:</strong> <?= htmlspecialchars($row['report_date'] ?? '') ?></div>
        <div><strong>Time:</strong> <?= htmlspecialchars($row['report_time'] ?? '') ?></div>
        <div><strong>Status:</strong> <?= htmlspecialchars($row['status'] ?? '') ?></div>
    </div>

    <?php if ($has_bns): ?>
    <h2>BNS Report Details</h2>
    <table>
        <tr>
            <th>Title</th>
            <td><?= val($bns, 'title') ?></td>
        </tr>
        <tr>
            <th>Year</th>
            <td><?= val($bns, 'year') ?></td>
        </tr>
        <tr>
            <th>Total Population</th>
            <td><?= val($bns, 'total_population', 'int') ?></td>
        </tr>
        <tr>
            <th>Male</th>
            <td><?= val($bns, 'male', 'int') ?></td>
        </tr>
        <tr>
            <th>Female</th>
            <td><?= val($bns, 'female', 'int') ?></td>
        </tr>
        <!-- add more rows based on your bns_reports columns -->
    </table>
    <?php else: ?>
    <p>No BNS data found for this report.</p>
    <?php endif; ?>

    <footer>
        <p style="margin-top:40px; font-size:11px; color:#555;">
            Generated on <?= date("F d, Y h:i A") ?>
        </p>
    </footer>
</body>
</html>
<?php
$html = ob_get_clean();

// ✅ Load Dompdf (check both paths)
$dompdfPath1 = __DIR__ . '/../dompdf/autoload.inc.php';
$dompdfPath2 = __DIR__ . '/../vendor/autoload.php';

if (file_exists($dompdfPath1)) {
    require_once $dompdfPath1;
} elseif (file_exists($dompdfPath2)) {
    require_once $dompdfPath2;
} else {
    die("❌ Dompdf not found. Please install it in /dompdf or via Composer.");
}

// ✅ Generate PDF
$dompdf = new \Dompdf\Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("report_$reportId.pdf", ["Attachment" => true]);
exit;
