<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNO NutriMap | Organizational Chart</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #f9fafb;
      color: #333;
      overflow-x: hidden;
    }

    .container {
      max-width: 1200px;
      margin: 60px auto;
      padding: 0 20px;
      text-align: center;
    }

    h1 {
      font-size: 28px;
      font-weight: 700;
      color: #0a0a0a;
      margin-bottom: 50px;
      position: relative;
    }

    h1::after {
      content: "";
      width: 80px;
      height: 3px;
      background: #00bfff;
      display: block;
      margin: 10px auto 0;
      border-radius: 2px;
    }

    /* === Chart Container === */
    .chart {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .level {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-bottom: 60px;
      flex-wrap: wrap;
      position: relative;
    }

    .member {
      background: #fff;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      width: 220px;
      position: relative;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .member:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .member img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #00bfff;
      margin-bottom: 10px;
    }

    .member h3 {
      font-size: 16px;
      font-weight: 600;
      color: #111;
      margin-bottom: 5px;
    }

    .member p {
      font-size: 14px;
      color: #666;
    }

    /* Connecting lines */
    .connector {
      width: 2px;
      background: #00bfff;
      height: 40px;
      position: absolute;
      left: 50%;
      top: -40px;
      transform: translateX(-50%);
    }

    .horizontal-line {
      width: 60px;
      height: 2px;
      background: #00bfff;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }

    .left-line { right: 50%; }
    .right-line { left: 50%; }

    @media (max-width: 768px) {
      .level {
        flex-direction: column;
        gap: 30px;
      }
      .horizontal-line {
        display: none;
      }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container">
    <h1>Organizational Chart</h1>

    <div class="chart">

      <!-- === Top Level === -->
      <div class="level">
        <div class="member">
          <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Head">
          <h3>Ma. Lourdes S. Balasabas</h3>
          <p>City Nutrition Officer</p>
        </div>
      </div>

      <!-- === Mid Level === -->
      <div class="level">
        <div class="connector"></div>
        <div class="member">
          <img src="https://cdn-icons-png.flaticon.com/512/236/236832.png" alt="Assistant">
          <h3>Elvie M. Caraga</h3>
          <p>City Nutrition Program Coordinator</p>
        </div>
        <div class="member">
          <img src="https://cdn-icons-png.flaticon.com/512/194/194938.png" alt="Assistant">
          <h3>Rhea D. Ga-as</h3>
          <p>City Nutrition Staff</p>
        </div>
      </div>

      <!-- === Barangay Nutrition Scholars === -->
      <div class="level">
        <div class="connector"></div>
        <div class="member">
          <img src="https://cdn-icons-png.flaticon.com/512/4322/4322991.png" alt="BNS">
          <h3>Barangay Nutrition Scholars</h3>
          <p>Community-Based Workers</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
