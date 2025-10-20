<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | History</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: linear-gradient(135deg, #f3f9f9, #ffffff);
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

    /* Header */
    .history-header {
      text-align: center;
      margin-bottom: 60px;
    }

    .history-header h1 {
      font-size: 2.8rem;
      color: #014d4d;
      letter-spacing: 1px;
      position: relative;
      display: inline-block;
    }

    .history-header h1::after {
      content: "";
      display: block;
      width: 80px;
      height: 4px;
      background-color: #00bfa6;
      margin: 12px auto;
      border-radius: 10px;
    }

    /* Timeline */
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

    .timeline-content {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      padding: 25px;
      position: relative;
      transition: 0.3s;
    }

    .timeline-content:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    .timeline-content h3 {
      color: #00bfa6;
      font-size: 1.4rem;
      margin-bottom: 10px;
    }

    .timeline-content p {
      color: #444;
      line-height: 1.7;
      font-size: 15px;
      text-align: justify;
    }

    .timeline-item::after {
      content: "";
      position: absolute;
      top: 30px;
      width: 22px;
      height: 22px;
      background: #00bfa6;
      border-radius: 50%;
      border: 4px solid #eaf6f6;
      left: calc(50% - 11px);
      z-index: 1;
    }

    /* Image inside timeline */
    .timeline-img {
      margin-top: 15px;
      border-radius: 10px;
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    /* Responsive */
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

    /* Footer */
    footer {
      background-color: #013241;
      color: #f9f9f9;
      padding: 80px 80px 20px;
      text-align: left;
      position: relative;
      margin-top: 80px;
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
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container">
    <div class="history-header">
      <h1>Our History</h1>
    </div>

    <div class="timeline">
      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Early Beginnings</h3>
          <p>
            The City Nutrition Office (CNO) of El Salvador City started its mission to improve
            community nutrition awareness, working with local leaders to fight malnutrition and
            promote healthy living among Tagnipan-ons.
          </p>
          <img src="../image/history1.jpg" alt="Early Beginnings" class="timeline-img">
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Program Development</h3>
          <p>
            Over the years, the CNO developed evidence-based nutrition programs, community outreach
            efforts, and educational campaigns, focusing on the health of every family and child.
          </p>
          <img src="../image/history2.jpg" alt="Program Development" class="timeline-img">
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Digital Transformation</h3>
          <p>
            With the creation of <strong>CNO NutriMap</strong>, the office embraced technology
            to collect, analyze, and visualize nutrition data — enabling faster and more effective
            decision-making.
          </p>
          <img src="../image/history3.jpg" alt="Digital Transformation" class="timeline-img">
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-content">
          <h3>Today and Beyond</h3>
          <p>
            Today, the City Nutrition Office continues its legacy of service — combining data-driven
            insights, partnerships, and innovation to build a healthier future for all Tagnipan-ons.
          </p>
          <img src="../image/history4.jpg" alt="Today and Beyond" class="timeline-img">
        </div>
      </div>
    </div>
  </div>

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
