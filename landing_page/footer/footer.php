<!-- includes/footer.php -->
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

<style>
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

  @media (max-width: 900px) {
    footer { padding: 60px 40px 20px; }
    .footer-container { flex-direction: column; gap: 20px; }
  }

  @media (max-width: 600px) {
    footer { padding: 40px 20px 15px; }
  }
</style>
