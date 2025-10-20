<?php
// activate_user.php
session_start();
require '../../db/config.php';

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$userId = (int) $_GET['id'];

// Update status to Active
$stmt = $pdo->prepare("UPDATE users SET status = 'Active' WHERE id = :id");
$stmt->execute([':id' => $userId]);

// Redirect back
header("Location: ../users.php");
exit();
?>
