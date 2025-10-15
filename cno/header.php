<?php
// header.php
require '../db/config.php';

$userId   = $_SESSION['user_id'] ?? null;
$userType = $_SESSION['user_type'] ?? '';

$notifications = [];
$unreadCount   = 0;

if ($userId && $userType === 'CNO') {
    // Fetch reports including submitted, save_changes, updates
$stmt = $pdo->prepare("
    SELECT r.id, 
           r.status,
           CONCAT(r.report_date, ' ', r.report_time) AS created_at,
           u.first_name, 
           u.last_name,
           u.profile_pic,
           CASE
               WHEN r.status = 'Pending' AND r.prev_status IS NULL THEN 'submitted a report'
               WHEN r.status = 'Pending' AND r.prev_status IS NOT NULL THEN 'updated the report'
               WHEN r.status = 'Saved Changes' THEN 'saved changes'
               ELSE 'performed an action'
           END AS message,
           CASE WHEN r.prev_status IS NULL THEN 1 ELSE 0 END AS is_unread
    FROM reports r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.report_date DESC, r.report_time DESC
    LIMIT 20
");


    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count unread
    $unreadCount = array_reduce($notifications, function($carry, $n) {
        return $carry + ($n['is_unread'] ? 1 : 0);
    }, 0);

    // Split into "New" (<30 mins) and "Earlier" (>30 mins)
    $now = time();
    $newNotifs = [];
    $earlierNotifs = [];

    foreach ($notifications as $n) {
        $notifTime = strtotime($n['created_at']);
        if (($now - $notifTime) <= 1800 && $n['is_unread']) { // 30 mins
            $newNotifs[] = $n;
        } else {
            $earlierNotifs[] = $n;
        }
    }
}
?>

<style>
/* Header bar */
.topbar {
  background: white;
  border-bottom: 1px solid #ccc;
  padding: 8px 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 1000;
  position: relative;
}
.brand {
  display: flex;
  align-items: center;
  font-weight: bold;
  font-size: 18px;
  cursor: pointer;
}
.brand i { font-size: 20px; margin-right: 8px; transition: transform 0.3s ease-in-out; }
.brand.active i { transform: rotate(90deg); }
.brand .cno { color: #009688; margin-right: 4px; }
.brand .nutrimap { color: #000; }

.topbar-right { display: flex; align-items: center; gap: 15px; }

.searchbox { position: relative; }
.searchbox input {
  padding: 8px 30px 8px 30px;
  border: 1px solid #aaa;
  border-radius: 4px;
  width: 220px;
  font-size: 14px;
  outline: none;
}
.searchbox i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #666; }

.bell { font-size: 20px; cursor: pointer; color: #333; position: relative; }
.bell .notif-count {
  position: absolute; top: -6px; right: -8px;
  background: red; color: white;
  font-size: 12px; font-weight: bold;
  border-radius: 50%; padding: 2px 6px;
}

#sidemenu-container { position: fixed; top: 0; left: 0; width: 0; height: 100%; overflow: hidden; z-index: 2000; }

/* 🔔 Notification Dropdown */
#notifDropdown {
  position: absolute;
  top: 50px; right: 10px;
  width: 340px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  display: none; flex-direction: column;
  z-index: 3000;
  max-height: 420px; overflow-y: auto;
}
.notif-header {
  display: flex; justify-content: space-between; align-items: center;
  padding: 12px 15px; border-bottom: 1px solid #eee; font-weight: bold;
}
.notif-header a { font-size: 13px; color: #009688; text-decoration: none; }

.notif-section {
  padding: 8px 15px; font-size: 13px; font-weight: bold; color: #555;
}
.notif-item {
  display: flex; align-items: center;
  padding: 10px 15px; border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
  text-decoration: none;
  color: inherit;
}
.notif-item:hover { background: #f9f9f9; }
.notif-item img { width: 36px; height: 36px; border-radius: 50%; margin-right: 10px; }
.notif-item .text { font-size: 14px; flex: 1; }
.notif-item .text strong { font-weight: 600; }
.notif-item .time { font-size: 12px; color: #888; }
.notif-item.unread .text { font-weight: 600; }
</style>

<header class="topbar">
  <div class="brand" id="menuBtn">
    <i class="fa fa-bars"></i>
    <span class="cno">CNO</span><span class="nutrimap">NutriMap</span>
  </div>
  <div class="topbar-right">
    <div class="searchbox">
      <i class="fa fa-search"></i>
      <input type="text" placeholder="Search">
    </div>
    <div class="bell" id="bellBtn">
      <i class="fa fa-bell"></i>
      <?php if ($unreadCount > 0): ?>
        <span class="notif-count"><?php echo $unreadCount; ?></span>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- 🔔 Notification Dropdown -->
<div id="notifDropdown">
  <div class="notif-header">
    <span>Notifications</span>
    <a href="notifications.php">See all</a>
  </div>

  <?php if (!empty($newNotifs)): ?>
    <div class="notif-section">New</div>
    <?php foreach ($newNotifs as $n): ?>
<a href="read_notification.php?id=<?php echo $n['id']; ?>" class="notif-item <?php echo $n['is_unread'] ? 'unread' : ''; ?>">
    <img src="../uploads/<?php echo $n['profile_pic'] ?? 'default.png'; ?>" alt="user">
    <div class="text">
        <strong><?php echo htmlspecialchars($n['first_name'].' '.$n['last_name']); ?></strong>
        <?php echo $n['message']; ?>
        <div class="time"><?php echo date("M d, H:i", strtotime($n['created_at'])); ?></div>
    </div>
</a>

    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (!empty($earlierNotifs)): ?>
    <div class="notif-section">Earlier</div>
    <?php foreach ($earlierNotifs as $n): ?>
      <a href="read_notification.php?id=<?php echo $n['id']; ?>" class="notif-item <?php echo $n['is_unread'] ? 'unread' : ''; ?>">
        <img src="../uploads/<?php echo $n['profile_pic'] ?? 'default.png'; ?>" alt="user">
        <div class="text">
          <strong><?php echo htmlspecialchars($n['first_name'].' '.$n['last_name']); ?></strong>
          <?php echo $n['message']; ?>
          <div class="time"><?php echo date("M d, H:i", strtotime($n['created_at'])); ?></div>
        </div>
      </a>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<div id="sidemenu-container"></div>

<script>
document.getElementById('menuBtn').addEventListener('click', async () => {
  const container = document.getElementById('sidemenu-container');
  const brand = document.getElementById('menuBtn');
  brand.classList.toggle('active');
  if (!container.innerHTML.trim()) {
    const response = await fetch('sidemenu.php');
    const html = await response.text();
    container.innerHTML = html;
    attachSideMenuListeners();
  }
  const menu = document.getElementById('sideMenu');
  if (menu) menu.classList.toggle('open');
});

// ✅ Notification Dropdown Toggle & remove count on click
const bellBtn = document.getElementById('bellBtn');
const notifDropdown = document.getElementById('notifDropdown');
const notifCountSpan = bellBtn.querySelector('.notif-count');

bellBtn.addEventListener('click', () => {
  notifDropdown.style.display = notifDropdown.style.display === 'flex' ? 'none' : 'flex';
  if (notifCountSpan) notifCountSpan.remove(); // remove number on click
});

document.addEventListener('click', (e) => {
  if (!bellBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
    notifDropdown.style.display = 'none';
  }
});

// ✅ Side menu listeners
function attachSideMenuListeners() {
  const menu = document.getElementById('sideMenu');
  if (!menu) return;

  const closeBtn = menu.querySelector('.close-btn');
  if (closeBtn) closeBtn.addEventListener('click', () => menu.classList.remove('open'));

  const menuItems = menu.querySelectorAll('.menu-links li[data-url]');
  menuItems.forEach(item => {
    item.addEventListener('click', () => {
      const url = item.getAttribute('data-url');
      if (url) window.location.href = url;
      menu.classList.remove('open');
    });
  });

  const footerLinks = menu.querySelectorAll('.footer-links > a');
  footerLinks.forEach(link => {
    link.addEventListener('click', () => menu.classList.remove('open'));
  });

  const settingsBtn = menu.querySelector('#settingsBtn');
  const settingsMenu = menu.querySelector('#settingsMenu');
  if (settingsBtn && settingsMenu) {
    settingsBtn.addEventListener('click', (e) => {
      e.preventDefault();
      settingsBtn.classList.toggle('open');
      settingsMenu.style.display = settingsMenu.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', (e) => {
      if (!settingsBtn.contains(e.target) && !settingsMenu.contains(e.target)) {
        settingsMenu.style.display = 'none';
        settingsBtn.classList.remove('open');
      }
    });
  }

  const userProfileBtn = menu.querySelector('#userProfileBtn');
  if (userProfileBtn) {
    userProfileBtn.addEventListener('click', () => {
      window.location.href = 'profile.php';
    });
  }
}
</script>