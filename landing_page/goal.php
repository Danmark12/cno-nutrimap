<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | Goal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #ffffff;
      color: #333;
      line-height: 1.6;
    }

    /* Goal Section */
    .goal-section {
      min-height: 75vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 70px 20px;
      text-align: center;
    }

    .goal-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
      max-width: 850px;
      padding: 70px 50px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .goal-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 4px;
      background: linear-gradient(90deg, #00b3b3, #00e0d1);
      transition: width 0.6s ease;
      border-radius: 4px;
    }

    .goal-card:hover::before {
      width: 80%;
    }

    .goal-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .goal-card h1 {
      color: #00b3b3;
      font-size: 38px;
      margin-bottom: 20px;
      font-weight: 700;
    }

    .goal-card p {
      font-size: 18px;
      color: #444;
      line-height: 1.9;
      max-width: 700px;
      margin: 0 auto;
    }

    /* Animation */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .goal-card {
      animation: fadeUp 1s ease forwards;
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
      .goal-card {
        padding: 50px 30px;
      }
      .goal-card h1 {
        font-size: 30px;
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

  <section class="goal-section">
    <div class="goal-card">
      <h1>Our Goal</h1>
      <p>Improve and sustain at a low public health significance on malnutrition among all age groups.</p>
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
