<?php
?>
<style>
#sideMenu {
  position: fixed;
  top: 0;
  left: 0;
  width: 280px;
  height: 100%;
  background: #fff;
  box-shadow: 2px 0 8px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  padding: 15px 0;
  transform: translateX(-100%);
  transition: transform 0.3s ease-in-out;
  z-index: 2000;
  font-family: 'Segoe UI', Arial, sans-serif;
}

#sideMenu.open {
  transform: translateX(0);
}

.sideMenu-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  margin-bottom: 20px;
}

.sideMenu-header h2 {
  font-size: 22px;
  font-weight: 600;
  color: #009688;
  margin: 0;
}

.sideMenu-header .close-btn {
  font-size: 22px;
  cursor: pointer;
  color: #555;
}

.menu-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.menu-links li {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  color: #333;
  border-radius: 6px;
  margin: 3px 10px;
  position: relative;
}

.menu-links li:hover {
  background: #f0f0f0;
  color: #009688;
}

.menu-links i {
  margin-right: 12px;
  font-size: 18px;
  color: #666;
}

/* Dropdown for Settings */
.dropdown {
  display: none;
  flex-direction: column;
  margin-left: 20px;
}

.menu-links li.settings:hover .dropdown {
  display: flex;
}

.dropdown a {
  padding: 8px 0;
  font-size: 14px;
  color: #555;
  text-decoration: none;
  transition: color 0.2s;
}

.dropdown a:hover {
  color: #009688;
}

.divider {
  height: 1px;
  background: #e0e0e0;
  margin: 20px 0;
}

.sideMenu-footer {
  margin-top: auto;
  padding: 0 20px 15px 20px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #e0e0e0;
}

.user-info img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.footer-links a {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  color: #555;
  font-size: 15px;
  padding: 8px 0;
  transition: color 0.2s;
}

.footer-links a:hover {
  color: #009688;
}
</style>

<div id="sideMenu">
  <!-- HEADER -->
  <div class="sideMenu-header">
    <h2>CNO</h2>
    <span class="close-btn">&times;</span>
  </div>

  <!-- MENU LINKS -->
  <ul class="menu-links">
    <li data-url="home.php"><i class="fa fa-home"></i> Home</li>
    <li data-url="reports.php"><i class="fa fa-file-alt"></i> Reports</li>
    <li data-url="report_history.php"><i class="fa fa-history"></i> Report History</li>
    <li data-url="barangay_data.php"><i class="fa fa-database"></i> Barangay Data</li>
    <li class="settings"><i class="fa fa-cog"></i> Settings
      <div class="dropdown">
        <a href="archive.php">Archive</a>
        <a href="security.php">Security</a>
      </div>
    </li>
    <li data-url="logout.php"><i class="fa fa-sign-out-alt"></i> Sign Out</li>
  </ul>

  <div class="divider"></div>

  <!-- FOOTER -->
  <div class="sideMenu-footer">
    <div class="user-info">
      <img src="https://via.placeholder.com/40" alt="User">
      <span>Bean Root</span>
    </div>
  </div>
</div>

<script>
// Close menu function
function closeSideMenu() {
  document.getElementById('sideMenu').classList.remove('open');
}

// Attach events after DOM loaded
document.addEventListener('DOMContentLoaded', () => {
  const menu = document.getElementById('sideMenu');
  if (!menu) return;

  // Close button
  const closeBtn = menu.querySelector('.close-btn');
  if (closeBtn) closeBtn.addEventListener('click', closeSideMenu);

  // Menu links navigation
  const menuItems = menu.querySelectorAll('.menu-links li[data-url]');
  menuItems.forEach(item => {
    item.addEventListener('click', () => {
      const url = item.getAttribute('data-url');
      if (url) window.location.href = url;
      closeSideMenu();
    });
  });

  // Footer links close menu
  const footerLinks = menu.querySelectorAll('.footer-links a');
  footerLinks.forEach(link => {
    link.addEventListener('click', closeSideMenu);
  });
});
</script>
