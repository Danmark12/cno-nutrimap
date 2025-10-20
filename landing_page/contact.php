<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | Contact Us</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* === Reset === */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #f9fafb;
      color: #333;
    }

    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 0 20px;
    }

    /* Contact Card */
    .contact-card {
      display: flex;
      flex-wrap: wrap;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      overflow: hidden;
      margin-bottom: 20px;
    }

    .contact-info {
      flex: 1 1 350px;
      padding: 30px;
    }

    .contact-info h2 {
      font-size: 22px;
      font-weight: 600;
      margin-bottom: 25px;
      color: #000;
    }

    .info-item {
      margin-bottom: 20px;
    }

    .info-item i {
      color: #00bfff;
      margin-right: 10px;
      font-size: 18px;
    }

    .info-item h4 {
      font-size: 14px;
      font-weight: 600;
      color: #111;
    }

    .info-item p {
      font-size: 15px;
      color: #555;
      margin-top: 3px;
    }

    .map {
      flex: 1 1 350px;
      min-height: 300px;
    }

    .map iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    /* Bottom Contact Boxes */
    .bottom-contact {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 10px;
    }

    .contact-box {
      flex: 1 1 350px;
      background: #fff;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.32);
    }

    .contact-box i {
      color: #00bfff;
      font-size: 20px;
    }

    .contact-box p {
      font-size: 15px;
      color: #333;
      line-height: 1.4;
    }

    .contact-box span {
      display: block;
      font-size: 13px;
      color: #666;
    }

    /* Footer */
    footer {
      background-color: #013241;
      color: #f9f9f9;
      padding: 80px 80px 20px;
      text-align: left;
      position: relative;
      z-index: 1;
      margin-top: 60px;
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
    @media (max-width: 768px) {
      .contact-card {
        flex-direction: column;
      }
      .map {
        height: 250px;
      }
      footer {
        padding: 40px 20px 15px;
      }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container">
    <!-- Contact Information Card -->
    <div class="contact-card">
      <div class="contact-info">
        <h2>Contact Information</h2>

        <div class="info-item">
          <i class="fa-solid fa-location-dot"></i>
          <h4>Address</h4>
          <p>Poblacion, El Salvador, Philippines, 9017</p>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-map-location"></i>
          <h4>Service area</h4>
          <p>El Salvador, Philippines</p>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-calendar-days"></i>
          <h4>Open Days</h4>
          <p>Monday to Friday</p>
        </div>

        <div class="info-item">
          <i class="fa-regular fa-clock"></i>
          <h4>Open/Closing Hours</h4>
          <p>08:00 am - 17:00 pm</p>
        </div>
      </div>

      <!-- Map Section -->
      <div class="map">
        <iframe
          src="https://www.google.com/maps?q=El%20Salvador%20Misamis%20Oriental&output=embed"
          allowfullscreen=""
          loading="lazy">
        </iframe>
      </div>
    </div>

    <!-- Contact Details Boxes -->
    <div class="bottom-contact">
      <div class="contact-box">
        <i class="fa-solid fa-phone"></i>
        <div>
          <p>0917 713 2398</p>
          <span>Mobile</span>
        </div>
      </div>

      <div class="contact-box">
        <i class="fa-solid fa-envelope"></i>
        <div>
          <p>citynutritionoffice@elsalvadorcity.gov.ph</p>
          <span>Email</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Section -->
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
