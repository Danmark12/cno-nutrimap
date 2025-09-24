<?php
session_start();
require '../../db/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$reportId = (int) $_GET['id'];

// 🔹 Update report status back to Pending (or Approved if needed)
$updateStmt = $pdo->prepare("UPDATE reports SET status = 'Pending' WHERE id = ?");
$updateStmt->execute([$reportId]);

// 🔹 Redirect back to main reports page
header("Location: ../archive.php?msg=restored");
exit();
