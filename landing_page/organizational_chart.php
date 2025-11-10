<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Organizational Chart | City Nutrition Office</title>

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: linear-gradient(to bottom right, #e8f5e9, #f4fdf7);
      color: #013241;
    }

    /* ===== Header ===== */
    header {
      text-align: center;
      padding: 60px 20px 40px;
      background: #f8faf9;
      border-bottom: 3px solid #00b3b3;
    }

    header h1 {
      font-size: 32px;
      font-weight: 700;
      color: #013241;
      margin-bottom: 10px;
      letter-spacing: 1px;
    }

    header h2 {
      font-size: 22px;
      color: #008b8b;
      margin-bottom: 5px;
    }

    header p {
      color: #555;
      font-size: 16px;
    }

    /* ===== Chart Section ===== */
    .chart-container {
      max-width: 1300px;
      margin: 0 auto;
      text-align: center;
      padding: 60px 20px;
    }

    .top-level {
      display: inline-block;
      margin-bottom: 50px;
      position: relative;
    }

    .top-level::after {
      content: '';
      position: absolute;
      width: 2px;
      height: 40px;
      background: #00b3b3;
      left: 50%;
      bottom: -40px;
      transform: translateX(-50%);
    }

    /* ===== Person Card ===== */
    .person-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
      display: inline-block;
      padding: 20px 15px;
      margin: 10px;
      text-align: center;
      width: 230px;
      transition: all 0.3s ease;    
      border-top: 5px solid #00b3b3;
    }

    .person-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 20px rgba(0, 179, 179, 0.25);
    }

    .person-card img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 10px;
      border: 3px solid #00b3b3;
    }

    .person-card h3 {
      font-size: 16px;
      font-weight: 600;
      margin: 5px 0 3px;
      color: #013241;
    }

    .person-card p {
      font-size: 14px;
      color: #555;
      margin: 0;
    }

    /* ===== Divisions ===== */
    .division {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      flex-wrap: wrap;
      gap: 25px;
      margin-bottom: 50px;
    }

    .division-title {
      font-size: 20px;
      font-weight: 700;
      color: #008b8b;
      margin: 0 auto 25px;
      border-bottom: 3px solid #00b3b3;
      display: inline-block;
      padding-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .divider-line {
      width: 100%;
      height: 1px;
      background: #00b3b3;
      margin: 30px 0;
      opacity: 0.3;
    }

    /* ===== Footer ===== */
    footer {
      background-color: #013241;
      color: #f9f9f9;
      padding: 80px 80px 20px;
      text-align: left;
      position: relative;
    }

    .footer-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-logo h2 { font-size: 22px; font-weight: 700; }
    .footer-logo span { color: #00b3b3; }
    .footer-social h3 { color:#00b3b3 }
    .footer-about { max-width: 400px; }
    .footer-about p { margin-top: 10px; font-size: 15px; line-height: 1.6; color: #ddd; }

    .footer-title { color: #00e0d1; font-size: 18px; margin-bottom: 10px; }

    .footer-links { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 8px; }
    .footer-links a {
      color: #ccc; text-decoration: none; font-size: 15px; transition: 0.3s;
    }
    .footer-links a:hover { color: #00e0d1; }

    .footer-social a {
      color: #00b3b3; font-size: 20px; margin-right: 15px;
      text-decoration: none; transition: 0.3s;
    }
    .footer-social a:hover { color: #00e0d1; }

    .footer-bottom {
      border-top: 1px solid #333;
      text-align: center;
      padding-top: 15px;
      font-size: 14px;
      color: #aaa;
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 1000px) {
      .division {
        flex-wrap: wrap;
      }
    }

    @media (max-width: 900px) {
      footer { padding: 60px 40px 20px; }
      .footer-container { flex-direction: column; gap: 20px; }
    }

    @media (max-width: 768px) {
      header h1 { font-size: 26px; }
      header h2 { font-size: 18px; }
      .person-card {
        width: 90%;
        max-width: 300px;
      }
      .division-title { font-size: 18px; }
    }

    @media (max-width: 600px) {
      footer { padding: 40px 20px 15px; }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <header>
    <h1>Organizational Chart</h1>
  </header>

  <section class="chart-container">
    <div class="top-level">
      <div class="person-card">
        <img src="../image/1 (7).png" alt="Elma M. Clapano">
        <h3>Elma M. Clapano, RN</h3>
        <p><strong>City Nutrition Action Officer</strong></p>
      </div>
    </div>

    <div class="divider-line"></div>

    <h3 class="division-title">Technical Division</h3>
    <div class="division">
      <div class="person-card">
        <img src="../image/1 (6).png" alt="Edgar B. Napilas">
        <h3>Edgar B. Napilas</h3>
        <p>City Nutrition Program Coordinator</p>
      </div>

      <div class="person-card">
        <img src="../image/1 (5).png" alt="Arlie Joy O. Damiles">
        <h3>Arlie Joy O. Damiles, RND</h3>
        <p>Nutritionist-Dietitian</p>
      </div>

      <div class="person-card">
        <img src="../image/1 (4).png" alt="Karen Jay B. Lagala">
        <h3>Karen Jay B. Lagala, RND</h3>
        <p>Nutritionist-Dietitian</p>
      </div>

      <div class="person-card">
        <img src="../image/1 (3).png" alt="Jay S. Boctot">
        <h3>Jay S. Boctot, LPT</h3>
        <p>City Nutrition Program Coordinator</p>
      </div>
    </div>

    <div class="divider-line"></div>

    <h3 class="division-title">Administrative Division</h3>
    <div class="division">
      <div class="person-card">
        <img src="../image/1 (2).png" alt="Honey Grace S. Magrifila">
        <h3>Honey Grace S. Magrifila</h3>
        <p>Office Clerk</p>
      </div>

      <div class="person-card">
        <img src="../image/1 (1).png" alt="Antonette E. Vilbar">
        <h3>Antonette E. Vilbar</h3>
        <p>Administrative Aide III</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-logo">
        <h2><span>CNO</span> NutriMap</h2>
        <div class="footer-about">
          <p>
            Dedicated to improving the nutritional health of our community through
            data-driven insights, collaboration, and sustainable nutrition programs.
          </p>
        </div>
      </div>

      <div class="footer-legal">
        <h3 class="footer-title">Legal & Support</h3>
        <ul class="footer-links">
          <li><a href="terms.php">Terms of Use</a></li>
          <li><a href="privacy.php">Privacy Policy</a></li>
          <li><a href="cookies.php">Cookies</a></li>
          <li><a href="help.php">Help</a></li>
          <li><a href="faqs.php">FAQs</a></li>
        </ul>
      </div>

      <div class="footer-social">
        <h3>Follow Us</h3>
        <a href="https://www.facebook.com/profile.php?id=100070642943154"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2025 City Nutrition Office | All Rights Reserved.</p>
    </div>
  </footer>
</body>
</html>
