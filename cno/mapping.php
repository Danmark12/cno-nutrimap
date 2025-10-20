<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>CNO NutriMap</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f8f8f8;
      color: #333;
    }

    /* Header */
    header {
      background: #ffffff;
      border-bottom: 1px solid #ccc;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
    }

    header .logo {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: bold;
    }

    header .logo span {
      color: #0097e6;
    }

    header .search {
      position: relative;
      display: flex;
      align-items: center;
    }

    header .search input {
      padding: 6px 30px 6px 10px;
      border-radius: 4px;
      border: 1px solid #ccc;
      width: 200px;
    }

    header .search i {
      position: absolute;
      right: 8px;
      color: #888;
    }

    header .icons i {
      font-size: 18px;
      color: #555;
      margin-left: 15px;
      cursor: pointer;
    }

    /* Layout */
    .content {
      display: flex;
      flex-direction: column;
      padding: 20px;
    }

    .section-title {
      color: #4a90e2;
      font-weight: bold;
      font-size: 14px;
    }

    .section-subtitle {
      font-weight: bold;
      font-size: 20px;
      margin: 5px 0 20px 0;
    }

    /* Main container */
    .main-container {
      display: flex;
      background: white;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 15px;
    }

    .map-section {
      flex: 2;
      border-right: 1px solid #ddd;
      padding-right: 20px;
    }

    .map-header {
      font-size: 14px;
      margin-bottom: 10px;
    }

    .map-box {
      background: #f3f3f3;
      height: 320px;
      border: 1px solid #ccc;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #555;
      position: relative;
    }

    .map-tooltip {
      position: absolute;
      bottom: 80px;
      background: white;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 5px 8px;
      box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    }

    .map-tooltip strong {
      display: block;
      font-size: 13px;
      margin-bottom: 2px;
    }

    /* Right panel */
    .side-panel {
      width: 250px;
      padding-left: 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .dropdowns {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    select {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background: #fff;
    }

    /* Legend (no card) */
    .legend-title {
      font-weight: bold;
      margin-top: 10px;
      font-size: 14px;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 4px 0;
      font-size: 13px;
    }

    .legend-color {
      width: 14px;
      height: 14px;
      border-radius: 3px;
      border: 1px solid #aaa;
    }

    .color1 { background-color: #939393; } /* Severely underweight */
    .color2 { background-color: #FFA500; } /* Underweight */
    .color3 { background-color: #016801ff; } /* Normal weight */
    .color4 { background-color: #00F6FF; } /* Severely wasted */
    .color5 { background-color: #FFEA00; } /* Wasted */
    .color6 { background-color: #8B4513; } /* Overweight */
    .color7 { background-color: #c70000ff; } /* Obese */
    .color8 { background-color: #c7009cff; } /* Severely stunted */
    .color9 { background-color: #0073caff; } /* Stunted */

    /* Gradient Bar with Percent */
    .gradient-container {
      margin-top: 20px;
    }

    .color-bar {
      width: 100%;
      height: 12px;
      background: linear-gradient(to right, #f0f0f0, #333);
      border-radius: 4px;
    }

    .percent-labels {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      color: #555;
      margin-top: 3px;
    }

    /* Footer label */
    .data-source {
      font-size: 12px;
      color: #666;
      margin-top: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .main-container {
        flex-direction: column;
      }
      .side-panel {
        width: 100%;
        padding-left: 0;
        border-top: 1px solid #ccc;
        margin-top: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'header.php'; ?>


  <div class="content">
    <div class="section-title">DATA</div>
    <div class="section-subtitle">Health and Nutrition</div>

    <div class="main-container">
      <div class="map-section">
        <div class="map-header">
          El Salvador &nbsp; Health and Nutrition Map: Share of children who are stunted
        </div>

        <div class="map-box">
          <div class="map-tooltip">
            <strong>Himaya</strong>
            No Data
          </div>
          <span>Map Area</span>
        </div>

        <div class="gradient-container">
                      <div class="percent-labels">
            <span>0%</span>
            <span>100%</span>
          </div>
          <div class="color-bar"></div>

        </div>

        <div class="data-source">
          Data source: Operation Timbang Plus CY 2025
        </div>
      </div>

      <div class="side-panel">
        <div class="dropdowns">
          <label>SELECT YEAR</label>
          <select>
            <option>ALL</option>
          </select>

          <label>SELECT BARANGAY</label>
          <select>
            <option>ALL</option>
          </select>
        </div>

        <h3>Legend</h3>
        <div class="legend-item"><div class="legend-color color1"></div> Severely underweight</div>
        <div class="legend-item"><div class="legend-color color2"></div> Underweight</div>
        <div class="legend-item"><div class="legend-color color3"></div> Normal weight</div>
        <div class="legend-item"><div class="legend-color color4"></div> Severely wasted</div>
        <div class="legend-item"><div class="legend-color color5"></div> Wasted</div>
        <div class="legend-item"><div class="legend-color color6"></div> Overweight</div>
        <div class="legend-item"><div class="legend-color color7"></div> Obese</div>
        <div class="legend-item"><div class="legend-color color8"></div> Severely stunted</div>
        <div class="legend-item"><div class="legend-color color9"></div> Stunted</div>
      </div>
    </div>
  </div>

</body>
</html>
