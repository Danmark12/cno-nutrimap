<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | Vision</title>
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
      color: #1b1b1bff;
      line-height: 1.6;
    }

    /* Vision Section */
    .vision-section {
      min-height: 70vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
      text-align: center;
    } 

    .vision-card {
      background: #ffffff73;
      border-radius: 20px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.39);
      max-width: 800px;
      padding: 60px 40px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .vision-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .vision-card h1 {
      color: #00b3b3;
      font-size: 36px;
      margin-bottom: 20px;
      font-weight: 700;
    }

    .vision-card p {
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
    .vision-card {
      animation: fadeIn 1s ease forwards;
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

    @media (max-width: 600px) {
      .vision-card {
        padding: 40px 25px;
      }
      .vision-card h1 {
        font-size: 28px;
      }
      footer {
        padding: 40px 20px 15px;
      }
    }
   .footer-social h3,
    .footer-title {
      color: #00e0d1;
      font-size: 18px;
      margin-bottom: 10px;
    }
        .footer-links a {
      color: #ccc;
      text-decoration: none;
      font-size: 15px;
      transition: 0.3s;
    }

    .footer-links a:hover {
      color: #00e0d1;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <section class="vision-section">
    <div class="vision-card">
      <h1>Our Vision</h1>
      <div class="divider"></div>
      <p>Healthy Tagnipan-ons through Committed, People-Centered and Excellent Nutrition Services.</p>
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

        <div class="footer-legal">
          <h3 class="footer-title">Legal & Support</h3>
          <ul class="footer-links">
            <li><a href="footer/terms.php">Terms of Use</a></li>
            <li><a href="footer/privacy.php">Privacy Policy</a></li>
            <li><a href="footer/cookies.php">Cookies</a></li>
            <li><a href="footer/help.php">Help</a></li>
            <li><a href="footer/faqs.php">FAQs</a></li>
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
