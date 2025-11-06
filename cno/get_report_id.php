<?php
require '../db/config.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
$barangay = isset($_GET['barangay']) ? trim($_GET['barangay']) : '';

if (!$year || !$barangay) {
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT r.id AS report_id
    FROM reports r
    JOIN bns_reports b ON b.report_id = r.id
    WHERE b.year = ? AND b.barangay = ? AND r.status = 'Approved'
    ORDER BY r.id DESC LIMIT 1
");
$stmt->execute([$year, $barangay]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode(['report_id' => $row['report_id']]);
} else {
    echo json_encode(['report_id' => null]);
}
