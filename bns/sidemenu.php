<?php
// sidemenu.php
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

/* Settings dropdown */
.settings-dropdown {
  flex-direction: column;
  align-items: stretch;
}

.settings-btn {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.settings-btn i:last-child {
  margin-left: auto;
  transition: transform 0.2s ease;
}

.settings-btn.open i:last-child {
  transform: rotate(180deg);
}

#settingsMenu {
  list-style: none;
  padding: 0;
  margin: 0;
  display: none;
  flex-direction: column;
}

#settingsMenu li {
  padding: 10px 40px; /* indent to align like submenu */
  cursor: pointer;
  font-size: 15px;
  color: #333;
  display: flex;
  align-items: center;
  gap: 12px; /* spacing between icon and text */
  transition: background 0.2s, color 0.2s;
}

#settingsMenu li:hover {
  background: #f5f5f5;
  color: #009688;
}

#settingsMenu li i {
  font-size: 16px;
  color: #666;
}
</style>

<div id="sideMenu">
  <div class="sideMenu-header">
    <h2>CNO</h2>
    <span class="close-btn">&times;</span>
  </div>

  <ul class="menu-links">
    <li data-url="home.php"><i class="fa fa-home"></i> Home</li>
    <li data-url="reports.php"><i class="fa fa-file-alt"></i> Reports</li>
    <li data-url="report_history.php"><i class="fa fa-history"></i> Report History</li>
    <li data-url="barangay_data.php"><i class="fa fa-database"></i> Barangay Data</li>

    <!-- Settings dropdown -->
    <li class="settings-dropdown">
      <div class="settings-btn" id="settingsBtn">
        <span><i class="fa fa-cog"></i> Settings</span>
        <i class="fa fa-chevron-down"></i>
      </div>
      <ul id="settingsMenu">
        <li data-url="archive.php"><i class="fa fa-archive"></i> Archive</li>
        <li data-url="security.php"><i class="fa fa-shield-alt"></i> Security</li>
      </ul>
    </li>
  </ul>

  <div class="divider"></div>

  <div class="sideMenu-footer">
    <div class="user-info" id="userProfileBtn" style="cursor:pointer;">
      <img src="https://via.placeholder.com/40" alt="User">
      <span>Bean Root</span>
    </div>
    <div class="footer-links">
      <a href="../logout.php"><i class="fa fa-sign-out-alt"></i> Sign Out</a>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // User profile click
  const userProfileBtn = document.getElementById('userProfileBtn');
  if (userProfileBtn) {
    userProfileBtn.addEventListener('click', () => {
      window.location.href = 'profile.php';
      closeSideMenu();
    });
  }

  // Settings dropdown toggle
  const settingsBtn = document.getElementById('settingsBtn');
  const settingsMenu = document.getElementById('settingsMenu');
  if (settingsBtn) {
    settingsBtn.addEventListener('click', () => {
      settingsBtn.classList.toggle('open');
      settingsMenu.style.display = settingsMenu.style.display === 'flex' ? 'none' : 'flex';
    });
  }

  // Settings sublinks click
  const settingsItems = settingsMenu.querySelectorAll('li');
  settingsItems.forEach(item => {
    item.addEventListener('click', () => {
      const url = item.getAttribute('data-url');
      if (url) window.location.href = url;
      closeSideMenu();
    });
  });
});
</script>



