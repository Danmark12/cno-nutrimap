<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require '../db/config.php';
require_once('../vendor/autoload.php');   // TCPDF

// ---------- Access Control ----------
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'CNO') {
    header("Location: ../login.php");
    exit();
}

// ---------- Helper ----------
function val(array $a, string $k, string $fmt = 'int'): string {
    if (!isset($a[$k]) || $a[$k] === '' || $a[$k] === null) return '—';
    if ($fmt === 'int')  return (string)(int)$a[$k];
    if ($fmt === 'pct')  return number_format((float)$a[$k], 2) . '%';
    if ($fmt === 'dec2') return number_format((float)$a[$k], 2);
    return htmlspecialchars((string)$a[$k]);
    
}
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// ---------- Query Totals ----------
$base = [
    'ind1','ind_male','ind_female','ind2','ind3','ind4','ind5',
    'ind6a','ind6b','ind7','ind8','ind9','ind9a','ind10','ind11',
    'ind12','ind13','ind14','ind15','ind16','ind18','ind19',
    'ind20','ind21','ind23','ind24','ind25','ind26',
    'ind37a','ind37b','ind38'
];

$groups = [
    '9b'  => ['ind9b1','ind9b2','ind9b3','ind9b4','ind9b5','ind9b6','ind9b7','ind9b8','ind9b9'],

    '17a' => ['ind17a_public','ind17a_private'],
    '17b' => ['ind17b_public','ind17b_private'],

    '22'  => ['ind22a','ind22b','ind22c','ind22d','ind22e','ind22f','ind22g'],

    '27'  => ['ind27a','ind27b','ind27c','ind27d','ind27e'],

    '28'  => ['ind28a','ind28b','ind28c','ind28d'],

    '29'  => ['ind29a','ind29b','ind29c','ind29d','ind29e','ind29f','ind29g'],

    '30'  => ['ind30a','ind30b','ind30c','ind30d'],

    '31'  => ['ind31a','ind31b','ind31c','ind31d','ind31e','ind31f'],

    '32'  => ['ind32'],
    '33'  => ['ind33'],
    '34'  => ['ind34'],
    '35'  => ['ind35'],
    '36'  => ['ind36']
];
$sel=[];
foreach($base as $f) $sel[]="SUM(bns.$f) AS $f";
foreach($groups as $arr){
    foreach($arr as $f){
        $sel[]="SUM(bns.{$f}_no)  AS {$f}_no";
        $sel[]="SUM(bns.{$f}_pct) AS {$f}_pct";
    }
}
$sel[]="SUM(bns.ind17a_public)  AS ind17a_public";
$sel[]="SUM(bns.ind17a_private) AS ind17a_private";
$sel[]="SUM(bns.ind17_public)  AS ind17_public";
$sel[]="SUM(bns.ind17_private) AS ind17_private";
$sel[]="SUM(bns.ind37a) AS ind37a";
$sel[]="SUM(bns.ind37b) AS ind37b";

$barangayFilter = '';
$params = [];
if (!empty($_GET['barangays'])) {
    $barangays = $_GET['barangays'];
    $placeholders = implode(',', array_fill(0, count($barangays), '?'));
    $barangayFilter = "AND br2.barangay IN ($placeholders)";
    $params = $barangays;
}

$sql = "
SELECT ".implode(',', $sel)."
FROM bns_reports bns
JOIN reports r ON bns.report_id = r.id
WHERE r.status='approved'
AND bns.id IN (
    SELECT MAX(br2.id)
    FROM bns_reports br2
    JOIN reports r2 ON r2.id = br2.report_id
    WHERE r2.status='approved' $barangayFilter
    GROUP BY br2.barangay
)";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$totals = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$totals) die('No data to export');

// ---------- TCPDF Setup ----------
class MYPDF extends TCPDF {
    public $reportYear = null;

    // This overrides TCPDF's header
    public function Header() {
        // Left text
        $this->SetFont('times','B',12);
        $this->SetXY(12, 10);
        $this->MultiCell(60, 5, "BNS Form No. IC\nBarangay Nutrition Profile", 0, 'L', 0, 0);

        // Logos
        $this->Image(__DIR__.'/../logos/fixed/Seal_of_El_Salvador__Misamis_Oriental-removebg-preview.jpg', 130, 8.5, 17);
        $this->Image(__DIR__.'/../logos/fixed/National_Nutrition_Council__NNC_.svg-removebg-preview.jpg', 150, 8.5, 17);
        $this->Image(__DIR__.'/../logos/fixed/Bagong-Pilipinas-logo.jpg', 170, 8.5, 17);

        // Centered title
        $this->SetY(35);
        $this->SetFont('times','B',14);
        $this->Cell(0, 0, 'CONSOLIDATED BARANGAY SITUATIONAL ANALYSIS (BSA)', 0, 1, 'C');

        $this->Ln(2);
        $this->SetFont('times','',11);
        $year = $this->reportYear ?? date('Y');
        $this->Cell(0, 0, "Calendar Year: $year | City: EL SALVADOR CITY | Province: MISAMIS ORIENTAL", 0, 1, 'C');

        $this->Ln(8);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('times','I',10);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, 0, 'R');
    }
}


$pdf = new MYPDF('P','mm','A4',true,'UTF-8',false);
$pdf->reportYear = $selectedYear;
$pdf->SetCreator('Nutrimap');
$pdf->SetAuthor('CNO');
$pdf->SetTitle('Consolidated Barangay Situation Analysis');
$pdf->SetMargins(12, 50, 12);
$pdf->SetAutoPageBreak(true,15);
$pdf->SetFont('times','',11);

// ---------- Table Builder ----------
function makeTable(array $rows): string {
    $html  = '<table cellpadding="4" cellspacing="0" width="100%" style="border-collapse:collapse;">';
    $html .= '<thead><tr>'
          .  '<th width="33.4%" style="border:1px solid #000;background:#f2f2f2;font-weight:bold;text-align:left;">Indicator</th>'
          .  '<th width="33.3%" style="border:1px solid #000;background:#f2f2f2;font-weight:bold;text-align:center;">No.</th>'
          .  '<th width="33.3%" style="border:1px solid #000;background:#f2f2f2;font-weight:bold;text-align:center;">%</th>'
          .  '</tr></thead><tbody>';
    foreach ($rows as $r) {
        $indicator = htmlspecialchars($r[0]);
        $no        = $r[1] ?? '—';
        $pct       = $r[2] ?? '';
        $html .= '<tr>';
        $html .= '<td style="border:1px solid #000;">'.$indicator.'</td>';
        if ($pct === '' || $pct === null) {
            $html .= '<td colspan="2" style="border:1px solid #000;text-align:center;">'.$no.'</td>';
        } else {
            $html .= '<td style="border:1px solid #000;text-align:center;">'.$no.'</td>';
            $html .= '<td style="border:1px solid #000;text-align:center;">'.$pct.'</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
    return $html;
}


// ---------- Page 1 ----------
$pdf->AddPage();
$p1 = [
    ['Total Population', val($totals, 'ind1')],
    ['Male', val($totals, 'ind_male')],
    ['Female', val($totals, 'ind_female')],
    ['Total Number of Households', val($totals, 'ind2')],
    ['Total Number of Families', val($totals, 'ind3')],
    ['Total Number of HHs with 5 or Below Members', val($totals, 'ind4')],
    ['Total Number of HHs with More Than 5 Members', val($totals, 'ind5')],
    ['Women - Pregnant', val($totals, 'ind6a')],
    ['Women - Lactating', val($totals, 'ind6b')],
    ['Households with Preschool Children (0–59 mos.)', val($totals, 'ind7')],
    ['Actual Population of Preschool Children (0–59 mos.)', val($totals, 'ind8')],
    ['Preschool Children Measured During OPT Plus', val($totals, 'ind9')],
    ['Percent Measured Coverage (OPT Plus)', val($totals, 'ind9a', 'pct')],
];

$nutri = [
    'Severely Underweight',
    'Underweight',
    'Normal Weight',
    'Severely Wasted',
    'Wasted',
    'Overweight',
    'Obese',
    'Severely Stunted',
    'Stunted'
];
for ($i = 1; $i <= 9; $i++) {
    $p1[] = [
        $nutri[$i - 1],
        val($totals, "ind9b{$i}_no"),
        val($totals, "ind9b{$i}_pct", 'pct')
    ];
}
$p1 = array_merge($p1, [
    ['Infants 0–5 months old', val($totals, 'ind10')],
    ['Infants 6–11 months old', val($totals, 'ind11')],
    ['Preschool Children 0–23 months', val($totals, 'ind12')],
    ['Preschool Children 12–59 months', val($totals, 'ind13')],
    ['Preschool Children 24–59 months', val($totals, 'ind14')],
    ['Families with Wasted and Severely Wasted Preschool Children', val($totals, 'ind15')],
    ['Families with Stunted and Severely Stunted Preschool Children', val($totals, 'ind16')],
]);
$pdf->writeHTML(makeTable($p1), true, false, false, false, '');



// ---------- Page 2 ----------
$pdf->AddPage();
$p2 = [];


$p2[] = [
    'Number of Day Care Centers – Public / Private',
    val($totals, 'ind17a_public'),
    val($totals, 'ind17a_private')
];
$p2[] = [
    'Number of Elementary Schools – Public / Private',
    val($totals, 'ind17b_public'),
    val($totals, 'ind17b_private')
];
$p2[] = ['Children Enrolled in Kindergarten', val($totals, 'ind18')];
$p2[] = ['School Children (Grades 1–6)', val($totals, 'ind19')];
$p2[] = ['School Children Weighed at Start of School Year', val($totals, 'ind20')];
$p2[] = ['Percentage Coverage of School Children Measured', val($totals, 'ind21', 'pct')];


foreach ([
    'a' => 'Severely Wasted',
    'b' => 'Wasted',
    'c' => 'Severely Stunted',
    'd' => 'Stunted',
    'e' => 'Normal',
    'f' => 'Overweight',
    'g' => 'Obese'
] as $c => $lbl) {
    $p2[] = [$lbl, val($totals, "ind22{$c}_no"), val($totals, "ind22{$c}_pct", 'pct')];
}

$p2[] = ['0–5 Months Old Children Exclusively Breastfed', val($totals, 'ind23')];
$p2[] = ['Households with Severely Wasted School Children', val($totals, 'ind24')];
$p2[] = ['School Children Dewormed at Start of School Year', val($totals, 'ind25')];
$p2[] = ['Fully Immunized Children (FIC)', val($totals, 'ind26')];


foreach ([
    'a' => 'Water-sealed Toilet',
    'b' => 'Antipolo (Unsanitary Toilet)',
    'c' => 'Open Pit',
    'd' => 'Shared',
    'e' => 'No Toilet'
] as $c => $lbl) {
    $p2[] = [$lbl, val($totals, "ind27{$c}_no"), val($totals, "ind27{$c}_pct", 'pct')];
}
$pdf->writeHTML(makeTable($p2), true, false, false, false, '');



// ---------- Page 3 ----------
$pdf->AddPage();
$p3 = [];


foreach ([
    'a' => 'Barangay/City Garbage Collection',
    'b' => 'Own Compost Pit',
    'c' => 'Burning',
    'd' => 'Dumping'
] as $c => $lbl) {
    $p3[] = [$lbl, val($totals, "ind28{$c}_no"), val($totals, "ind28{$c}_pct", 'pct')];
}


foreach ([
    'a' => 'Pipe Water System (Level III)',
    'b' => 'Spring (Level II)',
    'c' => 'Deep Well with Communal Source (Level II)',
    'd' => 'Deep Well with Individual Faucet (Level III)',
    'e' => 'Purified Station (Level III)',
    'f' => 'Open Shallow Dug Well (Level I)',
    'g' => 'Artesian Well'
] as $c => $lbl) {
    $p3[] = [$lbl, val($totals, "ind29{$c}_no"), val($totals, "ind29{$c}_pct", 'pct')];
}


foreach ([
    'a' => 'Vegetable Garden',
    'b' => 'Livestock/Poultry',
    'c' => 'Fishponds',
    'd' => 'No Garden'
] as $c => $lbl) {
    $p3[] = [$lbl, val($totals, "ind30{$c}_no"), val($totals, "ind30{$c}_pct", 'pct')];
}


foreach ([
    'a' => 'Concrete',
    'b' => 'Semi Concrete',
    'c' => 'Wooden House',
    'd' => 'Nipa Bamboo House',
    'e' => 'Barong-Barong Makeshift',
    'f' => 'Makeshift'
] as $c => $lbl) {
    $p3[] = [$lbl, val($totals, "ind31{$c}_no"), val($totals, "ind31{$c}_pct", 'pct')];
}

$p3 = array_merge($p3, [
    ['Households Using Iodized Salt', val($totals, 'ind32_no'), val($totals, 'ind32_pct', 'pct')],
    ['Eateries/Carinderia', val($totals, 'ind33_no'), val($totals, 'ind33_pct', 'pct')],
    ['Sari-Sari Stores Related to Iodized Salt', val($totals, 'ind34_no'), val($totals, 'ind34_pct', 'pct')],
    ['Sari-Sari Stores Related to Cooking Oil', val($totals, 'ind35_no'), val($totals, 'ind35_pct', 'pct')],
    ['Bakeries with Fortified Flour', val($totals, 'ind36_no'), val($totals, 'ind36_pct', 'pct')],
    ['Barangay Nutrition Scholar', val($totals, 'ind37a')],
    ['Barangay Health Worker', val($totals, 'ind37b')],
    ['Households Beneficiaries of 4Ps', val($totals, 'ind38')],
]);
$pdf->writeHTML(makeTable($p3), true, false, false, false, '');

// ---------- Output ----------
$pdf->Output('Consolidated_Barangay_Situation_Analysis.pdf', 'I');
