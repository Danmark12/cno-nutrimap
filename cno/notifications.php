<?php
session_start();
require '../db/config.php';

$userId   = $_SESSION['user_id'] ?? null;
$userType = $_SESSION['user_type'] ?? '';

if (!$userId || $userType !== 'CNO') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all notifications for CNO
$stmt = $pdo->prepare("
    SELECT r.id, 
           r.status,
           CONCAT(r.report_date, ' ', r.report_time) AS created_at,
           u.first_name, 
           u.last_name, 
           u.profile_pic,
           CASE r.status
               WHEN 'Pending' THEN 'submitted a report'
               WHEN 'Saved Changes' THEN 'saved changes'
               WHEN 'Updated' THEN 'updated the report'
               ELSE 'performed an action'
           END AS message,
           CASE WHEN r.prev_status IS NULL THEN 1 ELSE 0 END AS is_unread
    FROM reports r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.report_date DESC, r.report_time DESC
");
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Split notifications
$now = time();
$newNotifs = [];
$earlierNotifs = [];
$yesterdayNotifs = [];

foreach ($notifications as $n) {
    $notifTime = strtotime($n['created_at']);
    $diff = $now - $notifTime;

    if ($diff <= 1800 && $n['is_unread']) { // last 30 mins
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
<title>Notifications - CNO NutriMap</title>
<link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
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
.notif-item.unread .text { font-weight: 600; }
.notif-menu { cursor: pointer; font-size: 16px; color: #666; margin-left: 10px; position: relative; }
.notif-card { display: none; position: absolute; top: 28px; right: 0; background: #fff; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); z-index: 100; width: 150px; }
.notif-card div { padding: 10px; cursor: pointer; font-size: 14px; }
.notif-card div:hover { background: #f5f5f5; }
.notif-menu-card {
    position: absolute;
    top: 22px; /* position below the three-dot icon */
    right: 0;  /* align right with the icon */
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

    <?php if (!empty($newNotifs)): ?>
        <div class="notif-section">New</div>
        <?php foreach ($newNotifs as $n): ?>
            <div class="notif-item notif-item-wrapper unread" id="notif-<?php echo $n['id']; ?>">
                <div class="notif-left">
                    <a href="read_notification.php?id=<?php echo $n['id']; ?>">
                        <img src="../uploads/<?php echo $n['profile_pic'] ?? 'default.png'; ?>" alt="user">
                    </a>
                    <div class="text">
                        <strong><?php echo htmlspecialchars($n['first_name'].' '.$n['last_name']); ?></strong> <?php echo $n['message']; ?>
                        <div class="time"><?php echo date("M d, H:i", strtotime($n['created_at'])); ?></div>
                    </div>
                </div>
                <div class="notif-menu" onclick="toggleMenu(event, <?php echo $n['id']; ?>)">&#x22EE;</div>

                <!-- Three-dot card -->
                <div class="notif-menu-card" id="menu-card-<?php echo $n['id']; ?>">
                    <div onclick="deleteNotif(<?php echo $n['id']; ?>)">Delete Notification</div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Repeat for Earlier and Yesterday notifications -->
    <?php if (!empty($earlierNotifs)): ?>
        <div class="notif-section">Earlier</div>
        <?php foreach ($earlierNotifs as $n): ?>
            <div class="notif-item notif-item-wrapper <?php echo $n['is_unread'] ? 'unread' : ''; ?>" id="notif-<?php echo $n['id']; ?>">
                <div class="notif-left">
                    <a href="read_notification.php?id=<?php echo $n['id']; ?>">
                        <img src="../uploads/<?php echo $n['profile_pic'] ?? 'default.png'; ?>" alt="user">
                    </a>
                    <div class="text">
                        <strong><?php echo htmlspecialchars($n['first_name'].' '.$n['last_name']); ?></strong> <?php echo $n['message']; ?>
                        <div class="time"><?php echo date("M d, H:i", strtotime($n['created_at'])); ?></div>
                    </div>
                </div>
                <div class="notif-menu" onclick="toggleMenu(event, <?php echo $n['id']; ?>)">&#x22EE;</div>
                <div class="notif-menu-card" id="menu-card-<?php echo $n['id']; ?>">
                    <div onclick="deleteNotif(<?php echo $n['id']; ?>)">Delete Notification</div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($yesterdayNotifs)): ?>
        <div class="notif-section">Yesterday</div>
        <?php foreach ($yesterdayNotifs as $n): ?>
            <div class="notif-item notif-item-wrapper <?php echo $n['is_unread'] ? 'unread' : ''; ?>" id="notif-<?php echo $n['id']; ?>">
                <div class="notif-left">
                    <a href="read_notification.php?id=<?php echo $n['id']; ?>">
                        <img src="../uploads/<?php echo $n['profile_pic'] ?? 'default.png'; ?>" alt="user">
                    </a>
                    <div class="text">
                        <strong><?php echo htmlspecialchars($n['first_name'].' '.$n['last_name']); ?></strong> <?php echo $n['message']; ?>
                        <div class="time"><?php echo date("M d, H:i", strtotime($n['created_at'])); ?></div>
                    </div>
                </div>
                <div class="notif-menu" onclick="toggleMenu(event, <?php echo $n['id']; ?>)">&#x22EE;</div>
                <div class="notif-menu-card" id="menu-card-<?php echo $n['id']; ?>">
                    <div onclick="deleteNotif(<?php echo $n['id']; ?>)">Delete Notification</div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
// Toggle the three-dot menu
function toggleMenu(event, id) {
    event.stopPropagation();
    closeAllMenus();
    const card = document.getElementById('menu-card-' + id);
    card.style.display = card.style.display === 'block' ? 'none' : 'block';
}

// Close all open menus
function closeAllMenus() {
    document.querySelectorAll('.notif-menu-card').forEach(card => card.style.display = 'none');
}

document.addEventListener('click', closeAllMenus);

// Delete notification with AJAX and remove from page
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
