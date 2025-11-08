<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../db/config.php';

// ✅ Get current logged-in user
$userId = $_SESSION['user_id'] ?? 0;

// ✅ Fetch recent notifications (Approved/Rejected only for this BNS user) with SENDER info
$stmt = $pdo->prepare("
    SELECT 
        n.id, 
        n.related_id AS report_id, 
        n.message, 
        n.is_read, 
        n.created_at, 
        br.title,              
        r.status,              
        n.type,
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
    LIMIT 10
");
$stmt->execute([':user_id' => $userId]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Count unread notifications
$unreadStmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM notifications n
    JOIN reports r ON n.related_id = r.id
    WHERE n.user_id = :user_id
      AND n.receiver_type = 'BNS'
      AND n.is_read = 0
      AND (n.type = 'report_approved' OR n.type = 'report_rejected')
");
$unreadStmt->execute([':user_id' => $userId]);
$unreadCount = $unreadStmt->fetchColumn();

// ✅ Split into "new" (<30 mins) and "earlier"
$newNotifs = [];
$earlierNotifs = [];
foreach ($notifications as $notif) {
    $createdAt = strtotime($notif['created_at']);
    if ((time() - $createdAt) <= 1800) { // 30 minutes
        $newNotifs[] = $notif;
    } else {
        $earlierNotifs[] = $notif;
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

.brand i {
  font-size: 20px;
  margin-right: 8px;
}
.brand img {
  height: 35px;
  width: auto;
  margin-right: 0px;
}

.brand .cno {
  color: #009688;
  margin-right: 4px;
}

.brand .nutrimap {
  color: #000;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.searchbox {
  position: relative;
}

.searchbox input {
  padding: 8px 30px 8px 30px;
  border: 1px solid #aaa;
  border-radius: 4px;
  width: 220px;
  font-size: 14px;
  outline: none;
}

.searchbox i {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #666;
}

.bell {
  font-size: 20px;
  cursor: pointer;
  color: #333;
  position: relative;
}

.bell .badge {
  position: absolute;
  top: -5px;
  right: -10px;
  background: red;
  color: white;
  font-size: 12px;
  padding: 2px 6px;
  border-radius: 50%;
}

/* Dropdown */
.notif-dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 30px;
  background: white;
  border: 1px solid #ccc;
  width: 370px;
  max-height: 400px;
  overflow-y: auto;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  z-index: 2000;
}

.notif-header {
  margin: 10px;
  font-size: 13px;
  font-weight: bold;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #eee;
  padding-bottom: 5px;
}

.notif-header span {
  font-size: 13px;
  font-weight: bold;
}

.notif-header a {
  font-size: 12px;
  color: #007bff;
  text-decoration: none;
}

.notif-header a:hover {
  text-decoration: underline;
}

.notif-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 10px;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px;
  text-decoration: none;
  position: relative;
}

.notif-item img {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  object-fit: cover;
}

.notif-item .notif-text {
  flex: 1;
}

.notif-item.unread {
  font-weight: bold;
  color: black;
}

.notif-item.read {
  color: #555;
}

/* Three-dot menu */
.menu-container {
  position: relative;
}

.menu-button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 16px;
  color: #666;
}

.menu {
  display: none;
  position: absolute;
  right: 0;
  top: 20px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  z-index: 100;
}

.menu button {
  display: block;
  width: 100%;
  padding: 6px 10px;
  border: none;
  background: none;
  cursor: pointer;
  text-align: left;
  font-size: 13px;
}

.menu button:hover {
  background: #f2f2f2;
}
</style>

<header class="topbar">
  <div class="brand" id="menuBtn">
    <i class="fa fa-bars"></i>
        <img src="../image/cno.png" alt="Logo">
    <span class="cno">CNO</span><span class="nutrimap">NutriMap</span>
  </div>
  <div class="topbar-right">
    <!-- <div class="searchbox">
      <i class="fa fa-search"></i>
      <input type="text" placeholder="Search">
    </div> -->
    <!-- 🔔 Notification Bell -->
    <div class="bell" id="bellBtn">
      <i class="fa fa-bell"></i>
      <?php if ($unreadCount > 0): ?>
        <span class="badge"><?= $unreadCount ?></span>
      <?php endif; ?>
      <div class="notif-dropdown" id="notifMenu">
        <div class="notif-header">
          <span>Notifications</span>
          <a href="notifications.php">Show all</a>
        </div>

        <?php if (empty($notifications)): ?>
          <p style="padding: 10px; font-size: 13px;">No notifications</p>
        <?php else: ?>

          <?php if (!empty($newNotifs)): ?>
            <div class="notif-header"><span>New</span></div>
            <?php foreach ($newNotifs as $notif): ?>
              <div class="notif-item <?= $notif['is_read'] ? 'read' : 'unread' ?>" id="notif-<?= $notif['id'] ?>">
                <img src="../uploads/<?= htmlspecialchars($notif['profile_pic'] ?? 'default.png') ?>" alt="User">
                <div class="notif-text">
                  <strong><?= htmlspecialchars($notif['username'] ?? 'Unknown User') ?></strong><br>
                  Your report <strong><?= htmlspecialchars($notif['title']) ?></strong>
                  was <strong style="color:black;"><?= $notif['status'] ?></strong><br>
                  <small><?= date("M d, Y H:i", strtotime($notif['created_at'])) ?></small>
                </div>
                <div class="menu-container">
                  <button class="menu-button">⋮</button>
                  <div class="menu">
                    <button class="delete-btn" data-id="<?= $notif['id'] ?>">Delete</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!empty($earlierNotifs)): ?>
            <div class="notif-header"><span>Earlier</span></div>
            <?php foreach ($earlierNotifs as $notif): ?>
              <div class="notif-item <?= $notif['is_read'] ? 'read' : 'unread' ?>" id="notif-<?= $notif['id'] ?>">
                <img src="../uploads/<?= htmlspecialchars($notif['profile_pic'] ?? 'default.png') ?>" alt="User">
                <div class="notif-text">
                  <strong><?= htmlspecialchars($notif['username'] ?? 'Unknown User') ?></strong><br>
                  Your report <strong><?= htmlspecialchars($notif['title']) ?></strong>
                  was <strong style="color:black;"><?= $notif['status'] ?></strong><br>
                  <small><?= date("M d, Y H:i", strtotime($notif['created_at'])) ?></small>
                </div>
                <div class="menu-container">
                  <button class="menu-button">⋮</button>
                  <div class="menu">
                    <button class="delete-btn" data-id="<?= $notif['id'] ?>">Delete</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<div id="sidemenu-container"></div>
<script>
document.getElementById('menuBtn').addEventListener('click', async () => {
  const container = document.getElementById('sidemenu-container');
  if (!container.innerHTML.trim()) {
    const response = await fetch('sidemenu.php');
    const html = await response.text();
    container.innerHTML = html;
    const menu = document.getElementById('sideMenu');
    if (!menu) return;

    // 🔒 Close button behavior
    const closeBtn = menu.querySelector('.close-btn');
    if (closeBtn) closeBtn.addEventListener('click', () => menu.classList.remove('open'));

    // 📄 Menu item click navigation
    const menuItems = menu.querySelectorAll('.menu-links li[data-url]');
    menuItems.forEach(item => {
      item.addEventListener('click', () => {
        const url = item.getAttribute('data-url');
        if (url) window.location.href = url;
        menu.classList.remove('open');
      });
    });

    // 👤 User profile click
    const userProfileBtn = document.getElementById('userProfileBtn');
    if (userProfileBtn) {
      userProfileBtn.addEventListener('click', () => {
        window.location.href = 'profile.php';
      });
    }

    // ⚙️ Settings dropdown toggle
    const settingsBtn = document.getElementById('settingsBtn');
    const settingsMenu = document.getElementById('settingsMenu');
    if (settingsBtn && settingsMenu) {
      settingsBtn.addEventListener('click', () => {
        settingsBtn.classList.toggle('open');
        settingsMenu.style.display = settingsMenu.style.display === 'flex' ? 'none' : 'flex';
      });
    }
  }

  // 🟢 Open menu when clicked
  const menu = document.getElementById('sideMenu');
  if (menu) menu.classList.add('open');
});

// 🔔 Toggle notification dropdown
document.getElementById('bellBtn').addEventListener('click', function(e) {
  e.stopPropagation();
  const menu = document.getElementById('notifMenu');
  menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
});
document.addEventListener('click', function(e) {
  const menu = document.getElementById('notifMenu');
  const bell = document.getElementById('bellBtn');
  if (!bell.contains(e.target)) {
    menu.style.display = 'none';
  }
});

// ⋮ Toggle delete menu
document.querySelectorAll('.menu-button').forEach(button => {
  button.addEventListener('click', (e) => {
    e.stopPropagation();
    const menu = button.nextElementSibling;
    document.querySelectorAll('.menu').forEach(m => {
      if (m !== menu) m.style.display = 'none';
    });
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
  });
});

// Close menu when clicking elsewhere
document.addEventListener('click', () => {
  document.querySelectorAll('.menu').forEach(m => m.style.display = 'none');
});

// ✅ Delete notification logic
document.querySelectorAll('.delete-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    if (confirm('Delete this notification?')) {
      fetch('archive/delete_notification.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + encodeURIComponent(id)
      })
      .then(res => res.text())
      .then(response => {
        console.log('Server response:', response);
        if (response.trim() === 'success') {
          document.getElementById('notif-' + id).remove();
        } else {
          alert('Failed to delete notification.');
        }
      })
      .catch(err => {
        console.error('Fetch error:', err);
        alert('Error connecting to server.');
      });
    }
  });
});
</script>
