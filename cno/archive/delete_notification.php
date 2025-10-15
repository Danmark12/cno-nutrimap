<?php
session_start();
require '../../db/config.php';

// ✅ Ensure user is logged in and authorized (CNO only)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'CNO') {
    echo 'unauthorized';
    exit;
}

// ✅ Handle POST request for deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $notifId = intval($_POST['id']);
    $userId  = $_SESSION['user_id'];

    try {
        // ✅ Double-check that the notification exists for this user
        $check = $pdo->prepare("SELECT id FROM notifications WHERE id = ? AND user_id = ?");
        $check->execute([$notifId, $userId]);

        if ($check->rowCount() === 0) {
            echo 'error'; // not found or not owned by this user
            exit;
        }

        // ✅ Delete notification if valid
        $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
        $stmt->execute([$notifId, $userId]);

        if ($stmt->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'error';
        }

    } catch (PDOException $e) {
        error_log('Delete Notification Error: ' . $e->getMessage());
        echo 'error';
    }

} else {
    echo 'invalid_request';
}
?>
