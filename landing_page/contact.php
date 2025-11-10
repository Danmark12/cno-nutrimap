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

    /* Message Card */
    .message-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      margin-top: 30px;
      padding: 30px;
    }

    .message-card h2 {
      font-size: 22px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #000;
    }

    .message-card form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .message-card input,
    .message-card textarea {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      outline: none;
      transition: 0.2s;
    }

    .message-card input:focus,
    .message-card textarea:focus {
      border-color: #00bfff;
      box-shadow: 0 0 4px rgba(0, 191, 255, 0.3);
    }

    .message-card textarea {
      min-height: 120px;
      resize: vertical;
    }

    .message-card button {
      align-self: flex-start;
      background: #00bfff;
      color: #fff;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .message-card button:hover {
      background: #0099cc;
    }

    .success-msg {
      color: green;
      margin-bottom: 10px;
      transition: opacity 0.5s ease;
    }

    .error-msg {
      color: red;
      margin-bottom: 10px;
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
  <?php require_once '../otp/mailer.php'; // ✅ Include PHPMailer configuration ?>

  <?php
  $successMsg = "";
  $errorMsg = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
      $name = trim($_POST['name']);
      $email = trim($_POST['email']);
      $message = trim($_POST['message']);

      if (!empty($name) && !empty($email) && !empty($message)) {
          $to = "danmarkpetalcurin@gmail.com"; // Admin/CNO email
          $subject = "New Message from Guest User - $name";

          $body = "
              <h3>Message form the guest user of CNO nutrimap</h3>
              <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
              <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
              <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
              <hr>
              <p>This message was sent via the CNO NutriMap Contact Form.</p>
          ";

          if (sendEmailNotification($to, $subject, $body)) {
              $successMsg = "Message sent successfully!";
          } else {
              $errorMsg = "Failed to send message. Please try again later.";
          }
      } else {
          $errorMsg = "All fields are required.";
      }
  }
  ?>

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

    <!-- Message Card -->
    <div class="message-card">
      <h2>Send Us a Message</h2>

      <?php if (!empty($successMsg)) echo "<p class='success-msg' id='successMsg'>$successMsg</p>"; ?>
      <?php if (!empty($errorMsg)) echo "<p class='error-msg'>$errorMsg</p>"; ?>

      <form method="POST" action="">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Write your message here..." required></textarea>
        <button type="submit" name="send_message">Send</button>
      </form>
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

  <!-- ✅ Added JavaScript -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const msg = document.getElementById("successMsg");
      if (msg) {
        setTimeout(() => {
          msg.style.opacity = "0";
          setTimeout(() => msg.remove(), 500);
        }, 10000); // 10 seconds
      }
    });
  </script>
</body>
</html>
