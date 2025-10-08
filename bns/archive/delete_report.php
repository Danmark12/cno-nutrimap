<?php
session_start();
require '../../db/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$reportId = (int) $_GET['id'];

// 🔹 Permanently delete the report from reports table
$delStmt = $pdo->prepare("DELETE FROM reports WHERE id = ?");
$delStmt->execute([$reportId]);

// 🔹 Optional: delete related bns_reports data if exists
$delBns = $pdo->prepare("DELETE FROM bns_reports WHERE report_id = ?");
$delBns->execute([$reportId]);

// 🔹 Redirect back to archive page
header("Location: ../archive.php?msg=deleted");
exit();
