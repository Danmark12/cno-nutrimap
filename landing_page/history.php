<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | History</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* ===== Reset & Base ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: linear-gradient(135deg, #f5fbfb, #ffffff);
      color: #333;
      overflow-x: hidden;
    }

    .container {
      max-width: 1100px;
      margin: 100px auto;
      padding: 0 20px;
      position: relative;
      animation: fadeIn 1.2s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ===== Header Section ===== */
    .history-header {
      text-align: center;
      margin-bottom: 80px;
      animation: fadeDown 1s ease;
    }

    @keyframes fadeDown {
      from { opacity: 0; transform: translateY(-40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .history-header h1 {
      font-size: 2.8rem;
      color: #014d4d;
      letter-spacing: 1px;
      text-transform: uppercase;
      position: relative;
      display: inline-block;
    }

    .history-header h1::after {
      content: "";
      display: block;
      width: 100px;
      height: 4px;
      background-color: #00bfa6;
      margin: 12px auto 0;
      border-radius: 10px;
    }

    .history-header p {
      font-size: 1rem;
      color: #555;
      max-width: 800px;
      margin: 20px auto 0;
      line-height: 1.8;
    }

    /* ===== Timeline ===== */
    .timeline {
      position: relative;
      margin: 50px 0;
      padding-left: 20px;
    }

    .timeline::before {
      content: "";
      position: absolute;
      left: 50%;
      width: 4px;
      background: #00bfa6;
      top: 0;
      bottom: 0;
      border-radius: 10px;
      transform: translateX(-50%);
    }

    .timeline-item {
      position: relative;
      width: 50%;
      padding: 30px 40px;
      box-sizing: border-box;
    }

    .timeline-item:nth-child(odd) {
      left: 0;
      text-align: right;
    }

    .timeline-item:nth-child(even) {
      left: 50%;
    }

    .timeline-item::after {
      content: "";
      position: absolute;
      top: 35px;
      width: 22px;
      height: 22px;
      background: #00bfa6;
      border-radius: 50%;
      border: 4px solid #eaf6f6;
      left: calc(50% - 11px);
      z-index: 1;
    }

    .timeline-content {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      padding: 25px;
      position: relative;
      transition: 0.3s ease-in-out;
    }

    .timeline-content:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .timeline-content h3 {
      color: #00bfa6;
      font-size: 1.5rem;
      margin-bottom: 12px;
    }

    .timeline-content p {
      color: #444;
      line-height: 1.8;
      font-size: 15px;
      text-align: justify;
    }

    .timeline-img {
      margin-top: 15px;
      border-radius: 12px;
      width: 100%;
      height: 220px;
      object-fit: cover;
      transition: 0.3s ease;
    }

    .timeline-content:hover .timeline-img {
      transform: scale(1.02);
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
      .timeline::before {
        left: 10px;
      }
      .timeline-item {
        width: 100%;
        padding-left: 40px;
        padding-right: 0;
      }
      .timeline-item:nth-child(odd),
      .timeline-item:nth-child(even) {
        left: 0;
        text-align: left;
      }
      .timeline-item::after {
        left: 0;
        transform: translateX(-50%);
      }
    }

    /* ===== Footer ===== */
    footer {
      background-color: #013241;
      color: #f9f9f9;
      padding: 80px 80px 20px;
      text-align: left;
      position: relative;
      margin-top: 100px;
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

    @media (max-width: 900px) {
      footer { padding: 60px 40px 20px; }
      .footer-container { flex-direction: column; gap: 20px; }
    }

    @media (max-width: 600px) {
      footer { padding: 40px 20px 15px; }
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="container">
    <div class="history-header">
      <h1>Our History</h1>
      <p>
        The history of the City Nutrition Office of El Salvador, Misamis Oriental, is closely tied 
        to the city’s development and the efforts to combat malnutrition. The office was established 
        to address challenges in food availability and nutrition through community-driven initiatives.
      </p>
    </div>

    <div class="timeline">
      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Early Beginnings</h3>
          <p>
            The City Nutrition Office (CNO) of El Salvador City began its mission to improve 
            community nutrition awareness, working with local leaders to promote health and combat 
            malnutrition among residents.
          </p>
          <img src="../image/history1.jpg" alt="Early Beginnings" class="timeline-img">
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Program Development</h3>
          <p>
            Over the years, the CNO expanded its initiatives with evidence-based nutrition programs, 
            outreach campaigns, and health education, emphasizing family wellness and child growth.
          </p>
          <img src="../image/history2.jpg" alt="Program Development" class="timeline-img">
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Digital Transformation</h3>
          <p>
            The creation of <strong>CNO NutriMap</strong> marked a major step in modernization — 
            introducing data collection, analysis, and visualization to guide efficient decision-making.
          </p>
          <img src="../image/history3.jpg" alt="Digital Transformation" class="timeline-img">
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Today and Beyond</h3>
          <p>
            Today, the City Nutrition Office continues to lead efforts toward a healthier future — 
            integrating technology, data, and collaboration to build sustainable nutrition programs.
          </p>
          <img src="../image/history4.jpg" alt="Today and Beyond" class="timeline-img">
        </div>
      </div>
    </div>
  </div>

  <!-- ===== Footer ===== -->
  <footer>
    <div class="footer-container">
      <div class="footer-logo">
        <h2><span>CNO</span> NutriMap</h2>
        <div class="footer-about">
          <p>
            Dedicated to improving the nutritional health of our community through
          </p>
          <P>data-driven insights, collaboration, and sustainable nutrition programs.</P>
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
