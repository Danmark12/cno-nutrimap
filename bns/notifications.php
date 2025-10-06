<?php
session_start();
require '../db/config.php';

$userId   = $_SESSION['user_id'] ?? null;
$userType = $_SESSION['user_type'] ?? '';

if (!$userId || $userType !== 'BNS') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all notifications for this BNS user (Approved / Rejected only)
$stmt = $pdo->prepare("
    SELECT 
        n.id,
        n.related_id AS report_id,
        n.link,
        n.message,
        n.is_read,
        n.created_at,
        br.title AS report_title,
        r.status,
        u.username,
        u.profile_pic
    FROM notifications n
    JOIN reports r ON n.related_id = r.id
    LEFT JOIN bns_reports br ON br.report_id = r.id
    LEFT JOIN users u ON u.id = n.sender_id
    WHERE n.user_id = :user_id
      AND n.receiver_type = 'BNS'
      AND (n.type = 'report_approved' OR n.type = 'report_rejected')
    ORDER BY n.created_at DESC
");
$stmt->execute([':user_id' => $userId]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Split notifications
$now = time();
$newNotifs = [];
$earlierNotifs = [];
$yesterdayNotifs = [];

foreach ($notifications as $n) {
    $notifTime = strtotime($n['created_at']);
    $diff = $now - $notifTime;

    if ($diff <= 1800 && !$n['is_read']) { // last 30 mins & unread
        $newNotifs[] = $n;
    } elseif ($diff <= 86400) { // within 24 hours
        $earlierNotifs[] = $n;
    } else { // >1 day
        $yesterdayNotifs[] = $n;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Notifications - BNS NutriMap</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
.container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.header { padding: 15px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; font-size: 18px; font-weight: bold; }
.header a { font-size: 14px; text-decoration: none; color: #009688; }
.notif-section { padding: 10px 20px; font-weight: bold; color: #555; background: #f9f9f9; margin-top: 10px; }
.notif-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; border-bottom: 1px solid #eee; text-decoration: none; color: #333; position: relative; }
.notif-item:hover { background: #f0f0f0; }
.notif-left { display: flex; align-items: center; flex: 1; }
.notif-item img { width: 40px; height: 40px; border-radius: 50%; margin-right: 12px; }
.notif-item .text { font-size: 14px; }
.notif-item .text strong { font-weight: 600; }
.notif-item .time { font-size: 12px; color: #888; margin-top: 4px; }
.notif-item.unread .text { font-weight: 600; background-color: #f9f9f9; padding: 4px 6px; border-radius: 4px; }
.notif-menu { cursor: pointer; font-size: 16px; color: #666; margin-left: 10px; position: relative; }
.notif-menu-card {
    position: absolute;
    top: 22px;
    right: 0;
    background: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    display: none;
    cursor: pointer;
    z-index: 1000;
    min-width: 140px;
}
.notif-menu-card div:hover { background: #f0f0f0; }
</style>
</head>
<body>
<div class="layout">
<?php include 'header.php'; ?>

<div class="container">
    <div class="header">
        <span>Notifications</span>
        <a href="notifications.php">See All</a>
    </div>

    <?php
    function renderNotifs($notifList) {
        foreach ($notifList as $n): ?>
            <div class="notif-item notif-item-wrapper <?= $n['is_read'] ? '' : 'unread' ?>" id="notif-<?= $n['id'] ?>">
                <div class="notif-left">
                    <a href="<?= htmlspecialchars($n['link'] ?? 'read_notification.php?id='.$n['id'].'&report='.$n['report_id']) ?>">
                        <img src="../uploads/<?= htmlspecialchars($n['profile_pic'] ?? 'default.png') ?>" alt="user">
                    </a>
                    <div class="text">
                        Your report <strong><?= htmlspecialchars($n['report_title'] ?? 'Untitled') ?></strong> 
                        was <strong><?= htmlspecialchars($n['status']) ?></strong>
                                                by <strong><?= htmlspecialchars($n['username'] ?? 'Unknown User') ?></strong>.
                        <div class="time"><?= date("M d, H:i", strtotime($n['created_at'])) ?></div>
                    </div>
                </div>
                <div class="notif-menu" onclick="toggleMenu(event, <?= $n['id'] ?>)">&#x22EE;</div>
                <div class="notif-menu-card" id="menu-card-<?= $n['id'] ?>">
                    <div onclick="deleteNotif(<?= $n['id'] ?>)">Delete Notification</div>
                </div>
            </div>
        <?php endforeach;
    }

    if (!empty($newNotifs)) { echo '<div class="notif-section">New</div>'; renderNotifs($newNotifs); }
    if (!empty($earlierNotifs)) { echo '<div class="notif-section">Earlier</div>'; renderNotifs($earlierNotifs); }
    if (!empty($yesterdayNotifs)) { echo '<div class="notif-section">Yesterday</div>'; renderNotifs($yesterdayNotifs); }
    ?>
</div>

<script>
function toggleMenu(event, id) {
    event.stopPropagation();
    closeAllMenus();
    const card = document.getElementById('menu-card-' + id);
    card.style.display = card.style.display === 'block' ? 'none' : 'block';
}

function closeAllMenus() {
    document.querySelectorAll('.notif-menu-card').forEach(card => card.style.display = 'none');
}

document.addEventListener('click', closeAllMenus);

function deleteNotif(id) {
    if (!confirm("Delete this notification?")) return;
    fetch('delete_notification.php?id=' + id)
        .then(res => res.text())
        .then(data => {
            const notif = document.getElementById('notif-' + id);
            if (notif) notif.remove();
        });
}
</script>
</body>
</html>
