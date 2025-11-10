<style>
    /* === Reset === */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        background-color: #fff;
    }

    /* === Navbar === */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 60px;
        background-color: #fff;
        border-bottom: 1px solid #eaeaea;
        position: sticky;
        top: 0;
        z-index: 100;
        height: 60px;
    }

    /* Logo area */
    .logo {
        display: flex;
        align-items: center;
        margin-left: 0px;
        cursor: pointer;
    }

    .logo img {
        width: 50px;
        height: 50px;
        margin-right: 8px;
    }

    .logo-text {
        font-size: 1.3rem;
        font-weight: 600;
    }

    .logo-text .cno {
        color: #00bfff;
        font-weight: bold;
    }

    .logo-text .nutrim {
        color: #333;
    }

    /* Nav links */
    nav {
        flex-grow: 1;
        display: flex;
        justify-content: flex-end;
        margin-right: 5px;
    }

    nav ul {
        list-style: none;
        display: flex;
        gap: 30px;
        align-items: center;
    }

    nav a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: color 0.3s;
    }

    nav a:hover,
    nav a.active {
        color: #00bfff;
    }

    /* Dropdown */
    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #fff;
        list-style: none;
        border: 1px solid #eaeaea;
        border-radius: 4px;
        padding: 10px 0;
        min-width: 180px;
    }

    .dropdown-menu li {
        padding: 8px 20px;
    }

    .dropdown-menu li a {
        color: #333;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    /* Login button */
    .login-btn {
        border: 1px solid #333;
        padding: 6px 16px;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        background-color: #f1f5f7;
        transition: all 0.3s;
        margin-left: 5px;
        margin-right: 5px;
    }

    .login-btn:hover {
        background-color: #00bfff;
        color: white;
        border-color: #00bfff;
    }
</style>

<header>
    <div class="logo" onclick="window.location.href='home.php'">
        <img src="../../image/cno.png" alt="CNO Logo">
        <div class="logo-text">
            <span class="cno">CNO</span> <span class="nutrim">NutriMap</span>
        </div>
    </div>

    <nav>
        <ul>
            <li><a href="home.php" class="nav-link active">HOME</a></li>
            <li><a href="map.php" class="nav-link">NUTRITIONAL MAP</a></li>
            <li class="dropdown">
                <a href="" class="nav-link">GET TO KNOW US ▾</a>
                <ul class="dropdown-menu">
                    <li><a href="../organizational_chart.php">Organizational Chart</a></li>
                    <li><a href="../mission.php">Mission</a></li>
                    <li><a href="../vision.php">Vision</a></li>
                    <li><a href="../goal.php">Goal</a></li>
                    <li><a href="../history.php">History</a></li>
                </ul>
            </li>
            <li><a href="../contact.php" class="nav-link">CONTACT US</a></li>
            <li><a href="../../login.php" class="login-btn">LOGIN</a></li>
        </ul>
    </nav>
</header>

<script>
    // ✅ Highlight the current page in the nav
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll(".nav-link").forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active");
        }
    });

    // ✅ Handle active state on click
    document.querySelectorAll(".nav-link").forEach(link => {
        link.addEventListener("click", () => {
            document.querySelectorAll(".nav-link").forEach(a => a.classList.remove("active"));
            link.classList.add("active");
        });
    });
</script>
