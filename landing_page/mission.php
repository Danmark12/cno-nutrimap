<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | Mission</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #fff;
      color: #333;
      line-height: 1.6;
    }

    /* Mission Section */
    .mission-section {
      min-height: 70vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
      text-align: center;
    }

    .mission-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.43);
      max-width: 800px;
      padding: 60px 40px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .mission-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .mission-card h1 {
      color: #00b3b3;
      font-size: 36px;
      margin-bottom: 20px;
      font-weight: 700;
    }

    .mission-card p {
      font-size: 18px;
      color: #444;
      line-height: 1.8;
    }

    .divider {
      width: 80px;
      height: 4px;
      background: #00b3b3;
      border-radius: 2px;
      margin: 15px auto 25px;
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .mission-card {
      animation: fadeIn 1s ease forwards;
    }

    /* Footer (copied from home page) */
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

    @media (max-width: 600px) {
      .mission-card {
        padding: 40px 25px;
      }
      .mission-card h1 {
        font-size: 28px;
      }
      footer {
        padding: 40px 20px 15px;
      }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <section class="mission-section">
    <div class="mission-card">
      <h1>Our Mission</h1>
      <div class="divider"></div>
      <p>Safeguard the nutrition integrity and well-being of Tagnipan-ons through pro-active nutrition program implementation.</p>
    </div>
  </section>

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
</body>
</html>
