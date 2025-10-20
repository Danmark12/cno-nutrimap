<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      color: #333;
      background-color: #fff;
    }

    /* Header */
    .logo {
      font-weight: 700;
      font-size: 20px;
    }

    .logo span {
      color: #00b3b3;
    }

    nav ul {
      list-style: none;
      display: flex;
      align-items: center;
      gap: 30px;
    }

    nav ul li a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
      transition: 0.3s;
    }

    nav ul li a:hover,
    nav ul li a.active {
      color: #00b3b3;
    }

    .login-btn {
      border: 1px solid #00b3b3;
      color: #00b3b3;
      padding: 6px 20px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .login-btn:hover {
      background: #00b3b3;
      color: white;
    }

    /* Hero Section */
    .hero {
      height: 85vh;
      background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)),
        url("../image/building.jpg") center/cover no-repeat;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      text-align: left;
      padding: 0 80px;
      position: relative;
      top: 0;
    }

    .hero-content {
      color: white;
      max-width: 600px;
    }

    .hero-content h2 {
      font-size: 20px;
      font-weight: 500;
      margin-bottom: 5px;
    }

    .hero-content h1 {
      font-size: 48px;
      font-weight: 800;
      color: #00e0d1;
      line-height: 1.2;
      margin-bottom: 10px;
    }

    .hero-content p {
      font-size: 18px;
      margin-bottom: 30px;
      color: #f1f1f1;
    }

    .hero-content .btn {
      background: #00b3b3;
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .hero-content .btn:hover {
      background: #009999;
    }

    /* Info Card Section */
    .info-card {
      background: white;
      width: 100%;
      max-width: 1100px;
      margin: 10px auto;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      padding: 50px;
      text-align: center;
    }

    .info-card h2 {
      color: #00b3b3;
      margin-bottom: 10px;
      font-size: 28px;
    }

    .info-card p {
      font-size: 16px;
      line-height: 1.6;
      color: #555;
    }

    /* Footer */
    footer {
      background-color: #013241;
      color: #f9f9f9;
      padding: 80px 80px 20px;
      text-align: left;
      position: relative;
      z-index: 1;
    }

    .footer-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-logo h2 {
      font-size: 22px;
      font-weight: 700;
    }

    .footer-logo span {
      color: #00b3b3;
    }

    .footer-about {
      max-width: 400px;
    }

    .footer-about p {
      margin-top: 10px;
      font-size: 15px;
      line-height: 1.6;
      color: #ddd;
    }

    .footer-contact h3,
    .footer-social h3 {
      color: #00e0d1;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .footer-contact p {
      font-size: 15px;
      margin-bottom: 5px;
      color: #ccc;
    }

    .footer-social a {
      color: #00b3b3;
      font-size: 20px;
      margin-right: 15px;
      text-decoration: none;
      transition: 0.3s;
    }

    .footer-social a:hover {
      color: #00e0d1;
    }

    .footer-bottom {
      border-top: 1px solid #333;
      text-align: center;
      padding-top: 15px;
      font-size: 14px;
      color: #aaa;
    }

    /* Responsive */
    @media (max-width: 900px) {
      header {
        padding: 15px 40px;
      }
      .hero {
        padding: 0 40px;
        height: 70vh;
      }
      .info-card {
        width: 90%;
        padding: 30px;
      }
      footer {
        padding: 60px 40px 20px;
      }
      .footer-container {
        flex-direction: column;
        gap: 20px;
      }
    }

    @media (max-width: 600px) {
      nav ul {
        display: none;
      }
      .hero {
        padding: 0 20px;
        height: 65vh;
      }
      .hero-content h1 {
        font-size: 32px;
      }
      footer {
        padding: 40px 20px 15px;
      }
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h2>Welcome to</h2>
        <h1>City Nutrition Office</h1>
        <p>El Salvador, Misamis Oriental</p>
        <a href="#" class="btn">Know More About Us!</a>
      </div>
    </section>

    <!-- Info Card Section -->
    <div class="info-card">
      <h2>Empowering Nutrition Awareness</h2>
      <p>
        The City Nutrition Office of El Salvador, Misamis Oriental, is committed to promoting a healthier community 
        through education, data-driven decisions, and continuous collaboration with local partners and stakeholders.
      </p>
    </div>

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

        <div class="footer-contact">
          <h3>Contact Us</h3>
          <p><i class="fa-solid fa-location-dot"></i> El Salvador, Misamis Oriental</p>
          <p><i class="fa-solid fa-envelope"></i> cnonutrimap@gmail.com</p>
          <p><i class="fa-solid fa-phone"></i> +63 912 345 6789</p>
        </div>

        <div class="footer-social">
          <h3>Follow Us</h3>
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 City Nutrition Office | All Rights Reserved.</p>
      </div>
    </footer>
  </div>
</body>
</html>
