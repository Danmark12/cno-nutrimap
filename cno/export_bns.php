
<?php
// export_bns.php
session_start();
require '../db/config.php';

require_once '../vendor/autoload.php'; // Dompdf installed via Composer
use Dompdf\Dompdf;
use Dompdf\Options;

// ✅ Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Get report id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid report ID.");
}
$reportId = (int) $_GET['id'];

// ✅ Load report info (join reports + bns_reports + users for barangay info)
$stmt = $pdo->prepare("
    SELECT r.*, b.*, u.barangay, u.first_name, u.last_name
    FROM reports r
    INNER JOIN bns_reports b ON r.id = b.report_id
    INNER JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
");
$stmt->execute([$reportId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Report not found.");
}

$has_bns = !empty($row);

// ✅ Helper function
function val($arr, $key, $format = '') {
    if (!isset($arr[$key]) || $arr[$key] === null) return '—';
    $v = $arr[$key];
    if ($format === 'int') return number_format((int)$v);
    if ($format === 'dec2') return number_format((float)$v, 2);
    if ($format === 'pct') return number_format((float)$v, 2) . '%';
    return htmlspecialchars($v);
}
function getBarangayLogo($barangay) {
    $logos = [
        'CNO' => 'CNO.png',
        'Amoros' => 'Amoros.png',
        'Bolisong' => 'Bolisong.png',
        'Cogon' => 'Cogon.png',
        'Himaya' => 'Himaya.png',
        'Hinigdaan' => 'Hinigdaan.png',
        'Kalabaylabay' => 'Kalabaylabay.png',
        'Molugan' => 'Molugan.png',
        'Pedro S. Baculio' => 'Pedro sa Baculio.png',
        'Pedro sa Baculio' => 'Pedro sa Baculio.png',
        'Poblacion' => 'Poblacion.png',
        'Quibonbon' => 'Quibonbon.png',
        'Sambulawan' => 'Sambulawan.png',
        'San Francisco de Asis' => 'San Francisco de Asis.png',
        'Sinaloc' => 'Sinaloc.png',
        'Taytay' => 'Taytay.png',
        'Ulaliman' => 'Ulaliman.png'
    ];

    $logo = isset($logos[$barangay]) ? $logos[$barangay] : 'default.png';
    $path = __DIR__ . '/../logos/barangays/' . $logo;

    if (!file_exists($path)) {
        $logo = 'default.png';
    }

    return $logo;
}
// ✅ Logos path
$barangay_logo = getBarangayLogo($row['barangay'] ?? '');

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barangay Nutrition Report</title>
    <style>
        @page { margin: 1in; }
        body {
            font-family:"Times New Roman", serif;
            font-size:12px;
            line-height:1.4;
        }
        .document { width:100%; margin:0 auto; }
        h3 { margin-bottom:10px; text-align:center; }

        .header-table { width:100%; margin-bottom:20px; border:none; }
        .header-table td { padding:4px 6px; border:none; vertical-align:middle; }
        .header-left { font-weight:bold; font-size:14px; }
        .header-logos { display:flex; justify-content:flex-end; gap:10px; }
        .header-logos img { height:55px; }

        .report-info { text-align:center; margin-bottom:15px; font-size:12px; }

        /* ✅ Indicators Table */
        table.indicators { width:100%; border-collapse:collapse; margin-bottom:12px; }
        table.indicators th, table.indicators td {
            border:1px solid #000;
            padding:4px 6px;
            font-size:11px;
            text-align:center;
        }
        table.indicators th:first-child,
        table.indicators td:first-child {
            text-align:left;
        }
        table.indicators th { background:#e9ecef; }
        .indent td:first-child { padding-left:20px; }

        .page-number { text-align:right; font-size:11px; margin-top:6px; }
    </style>
</head>
<body>

<div class="document">
  <table class="header-table">
    <tr>
      <td class="header-left">
        BNS Form No. IC<br>Barangay Nutrition Profile
      </td>
      <td class="header-logos">
        <?php if (!empty($row['barangay_logo'])): ?>
          <img src="<?= $basePath . '../logos/barangays/' . htmlspecialchars($row['barangay_logo']) ?>" alt="Barangay Logo">
        <?php endif; ?>
        <img src="<?= $basePath ?>../logos/fixed/Seal_of_El_Salvador__Misamis_Oriental-removebg-preview.png">
        <img src="<?= $basePath ?>../logos/fixed/National_Nutrition_Council__NNC_.svg-removebg-preview.png">
        <img src="<?= $basePath ?>../logos/fixed/Bagong-Pilipinas-logo.png">
      </td>
    </tr>
  </table>

  <div class="report-info">
      <h3>BARANGAY SITUATIONAL ANALYSIS (BSA)</h3>				
      <strong>Calendar Year:</strong> <?= $has_bns ? val($row,'year') : '—' ?> &nbsp;
      <strong>Barangay:</strong> <?= val($row,'barangay') ?> &nbsp;
      <strong>City:</strong> EL SALVADOR CITY &nbsp;
      <strong>Province:</strong> MISAMIS ORIENTAL
  </div>

  <!-- ✅ Indicators Table -->
  <table class="indicators">
    <thead>
      <tr>
        <th>Indicator</th>
        <th>No.</th>
        <th>%</th>
      </tr>
    </thead>
    <tbody>

    <!-- 1–3 -->
    <tr><td>1. Total Population</td><td><?= $has_bns ? val($row,'ind1','int') : '—' ?></td><td></td></tr>
    <tr><td>2. Number of households</td><td><?= $has_bns ? val($row,'ind2','int') : '—' ?></td><td></td></tr>
    <tr><td>3. Total number of families</td><td><?= $has_bns ? val($row,'ind3','int') : '—' ?></td><td></td></tr>

    <!-- 4 -->
    <tr><td>4. Total number of women who are:</td><td></td><td></td></tr>
    <tr class="indent"><td>a. Pregnant</td><td><?= $has_bns ? val($row,'ind4a','int') : '—' ?></td><td></td></tr>
    <tr class="indent"><td>b. Lactating</td><td><?= $has_bns ? val($row,'ind4b','int') : '—' ?></td><td></td></tr>

    <!-- 5–6 -->
    <tr><td>5. Households with preschool children (0-59 months)</td><td><?= $has_bns ? val($row,'ind5','int') : '—' ?></td><td></td></tr>
    <tr><td>6. Actual population of preschool children (0-59 months)</td><td><?= $has_bns ? val($row,'ind6','int') : '—' ?></td><td></td></tr>

    <!-- 7 -->
    <tr><td>7. Total preschool children 0-59 months measured during OPT Plus</td><td></td><td></td></tr>
    <tr class="indent"><td>a. % measured coverage (OPT Plus)</td><td></td><td><?= $has_bns ? val($row,'ind7a','dec2') : '—' ?></td></tr>
    <tr class="indent"><td>b. Preschool children by Nutritional Status</td><td></td><td></td></tr>
    <?php 
      $nutri = ['Severely underweight','Underweight','Normal weight','Severely wasted','Wasted','Overweight','Obese','Severely stunted','Stunted'];
      for ($i=1;$i<=9;$i++): ?>
      <tr class="indent">
        <td><?= $i.') '.$nutri[$i-1] ?></td>
        <td><?= $has_bns ? val($row,"ind7b{$i}_no",'int') : '—' ?></td>
        <td><?= $has_bns ? val($row,"ind7b{$i}_pct",'pct') : '—' ?></td>
      </tr>
    <?php endfor; ?>

    <!-- 8–14 -->
    <tr><td>8. Infants 0-5 months old</td><td><?= $has_bns ? val($row,'ind8','int') : '—' ?></td><td></td></tr>
    <tr><td>9. Infants 6-11 months old</td><td><?= $has_bns ? val($row,'ind9','int') : '—' ?></td><td></td></tr>
    <tr><td>10. Preschool children 0-23 months old</td><td><?= $has_bns ? val($row,'ind10','int') : '—' ?></td><td></td></tr>
    <tr><td>11. Preschool children 12-59 months old</td><td><?= $has_bns ? val($row,'ind11','int') : '—' ?></td><td></td></tr>
    <tr><td>12. Preschool children 24-59 months old</td><td><?= $has_bns ? val($row,'ind12','int') : '—' ?></td><td></td></tr>
    <tr><td>13. Families with wasted/severely wasted preschool children</td><td><?= $has_bns ? val($row,'ind13','int') : '—' ?></td><td></td></tr>
    <tr><td>14. Families with stunted/severely stunted preschool children</td><td><?= $has_bns ? val($row,'ind14','int') : '—' ?></td><td></td></tr>

    <!-- 15 -->
    <tr><td>15. Educational Institutions</td><td>Public</td><td>Private</td></tr>
    <tr class="indent"><td>a. Day Care Centers</td><td><?= $has_bns ? val($row,'ind15a_public','int') : '—' ?></td><td><?= $has_bns ? val($row,'ind15a_private','int') : '—' ?></td></tr>
    <tr class="indent"><td>b. Elementary Schools</td><td><?= $has_bns ? val($row,'ind15b_public','int') : '—' ?></td><td><?= $has_bns ? val($row,'ind15b_private','int') : '—' ?></td></tr>

    <!-- 16–19 -->
    <tr><td>16. Kindergarten Enrolled</td><td><?= $has_bns ? val($row,'ind16','int') : '—' ?></td><td></td></tr>
    <tr><td>17. School children (Grades 1-6)</td><td><?= $has_bns ? val($row,'ind17','int') : '—' ?></td><td></td></tr>
    <tr><td>18. School children weighed (K-Gr. 6)</td><td><?= $has_bns ? val($row,'ind18','int') : '—' ?></td><td></td></tr>
    <tr><td>19. % coverage measured</td><td></td><td><?= $has_bns ? val($row,'ind19','dec2') : '—' ?></td></tr>

    <!-- 20 -->
    <tr><td>20. School children by Nutritional Status</td><td></td><td></td></tr>
    <?php 
      $s20 = ['a. Severely Wasted','b. Wasted','c. Normal','d. Overweight','e. Obese'];
      foreach($s20 as $k=>$label): 
        $key = chr(97+$k); ?>
      <tr class="indent">
        <td><?= $label ?></td>
        <td><?= $has_bns ? val($row,"ind20{$key}_no",'int') : '—' ?></td>
        <td><?= $has_bns ? val($row,"ind20{$key}_pct",'pct') : '—' ?></td>
      </tr>
    <?php endforeach; ?>

    <!-- 21–25 -->
    <tr><td>21. Exclusively breastfed 0-5 months</td><td><?= $has_bns ? val($row,'ind21','int') : '—' ?></td><td></td></tr>
    <tr><td>22. Complementary foods at 6 months</td><td><?= $has_bns ? val($row,'ind22','int') : '—' ?></td><td></td></tr>
    <tr><td>23. Households with wasted school children</td><td><?= $has_bns ? val($row,'ind23','int') : '—' ?></td><td></td></tr>
    <tr><td>24. School children dewormed</td><td><?= $has_bns ? val($row,'ind24','int') : '—' ?></td><td></td></tr>
    <tr><td>25. Fully immunized children</td><td><?= $has_bns ? val($row,'ind25','int') : '—' ?></td><td></td></tr>

    <!-- 26 -->
    <tr><td>26. Toilet facility by type</td><td>No.</td><td>%</td></tr>
    <?php $toilets=['a. Water-sealed','b. Antipolo','c. Open Pit/Shared','d. No Toilet']; 
    foreach($toilets as $k=>$label): $i=chr(97+$k); ?>
      <tr class="indent">
        <td><?= $label ?></td>
        <td><?= $has_bns ? val($row,"ind26{$i}_no",'int') : '—' ?></td>
        <td><?= $has_bns ? val($row,"ind26{$i}_pct",'pct') : '—' ?></td>
      </tr>
    <?php endforeach; ?>

    <!-- 27 -->
    <tr><td>27. Garbage disposal by type</td><td>No.</td><td>%</td></tr>
    <?php $g=['a. Barangay/City garbage','b. Own compost pit','c. Burning','d. Dumping']; 
    foreach($g as $k=>$label): $i=chr(97+$k); ?>
      <tr class="indent">
        <td><?= $label ?></td>
        <td><?= $has_bns ? val($row,"ind27{$i}_no",'int') : '—' ?></td>
        <td><?= $has_bns ? val($row,"ind27{$i}_pct",'pct') : '—' ?></td>
      </tr>
    <?php endforeach; ?>

    <!-- 28 -->
    <tr><td>28. Water source by type</td><td>No.</td><td>%</td></tr>
    <?php $w=['a. Pipe water system','b. Well – Level II','c. Deep well (Level II)','d. Mineral water','e. Open shallow dug well']; 
    foreach($w as $k=>$label): $i=chr(97+$k); ?>
      <tr class="indent">
        <td><?= $label ?></td>
        <td><?= $has_bns ? val($row,"ind28{$i}_no",'int') : '—' ?></td>
        <td><?= $has_bns ? val($row,"ind28{$i}_pct",'pct') : '—' ?></td>
      </tr>
    <?php endforeach; ?>

    <!-- 29 -->
    <tr><td>29. Households with</td><td>No.</td><td>%</td></tr>
    <?php $h=['a. Vegetable garden','b. Livestock/poultry','c. Combination garden & livestock','d. Fishponds','e. No garden']; 
    foreach($h as $k=>$label): $i=chr(97+$k); ?>
      <tr class="indent">
        <td><?= $label ?></td>
        <td><?= $has_bns ? val($row,"ind29{$i}_no",'int') : '—' ?></td>
        <td><?= $has_bns ? val($row,"ind29{$i}_pct",'pct') : '—' ?></td>
      </tr>
    <?php endforeach; ?>

    <!-- 30 -->
    <tr><td>30. Type of dwelling unit</td><td>No.</td><td>%</td></tr>
    <?php $d=['a. Concrete','b. Semi concrete','c. Wooden house','d. Nipa bamboo house','e. Barong-barong']; 
    foreach($d as $k=>$label): $i=chr(97+$k); ?>
      <tr class="indent">
        <td><?= $label ?></td>
        <td><?= $has_bns ? val($row,"ind30{$i}_no",'int') : '—' ?></td>
        <td><?= $has_bns ? val($row,"ind30{$i}_pct",'pct') : '—' ?></td>
      </tr>
    <?php endforeach; ?>

    <!-- 31–34 -->
    <tr><td>31. Households using iodized salt</td><td><?= $has_bns ? val($row,'ind31','int') : '—' ?></td><td></td></tr>
    <tr><td>32. Total number of eateries/carinderia</td><td><?= $has_bns ? val($row,'ind32','int') : '—' ?></td><td></td></tr>
    <tr><td>33. Total number of bakeries</td><td><?= $has_bns ? val($row,'ind33','int') : '—' ?></td><td></td></tr>
    <tr><td>34. Total number of sari-sari stores</td><td><?= $has_bns ? val($row,'ind34','int') : '—' ?></td><td></td></tr>

    <!-- 35 -->
    <tr><td>35. Health and nutrition workers</td><td></td><td></td></tr>
    <tr class="indent"><td>a. Barangay Nutrition Scholar</td><td><?= $has_bns ? val($row,'ind35a','int') : '—' ?></td><td></td></tr>
    <tr class="indent"><td>b. Barangay Health Worker</td><td><?= $has_bns ? val($row,'ind35b','int') : '—' ?></td><td></td></tr>

    <!-- 36 -->
    <tr><td>36. Pantawid Pamilya households</td><td><?= $has_bns ? val($row,'ind36','int') : '—' ?></td><td></td></tr>

    </tbody>
  </table>

  <div class="page-number">Page 1</div>
</div>

</body>
</html>

<?php
$html = ob_get_clean();

// ✅ Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 612, 936], 'portrait');
$dompdf->render();

// ✅ Output PDF
$dompdf->stream("report_$reportId.pdf", ["Attachment" => true]);
exit;
