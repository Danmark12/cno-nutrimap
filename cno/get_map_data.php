<?php
// get_map_data.php
// Returns a FeatureCollection JSON combining your barangay_boundary.geojson geometry
// with bns_reports (only approved reports joined with reports.status = 'Approved').
//
// Requires: ../db/config.php that provides $pdo (PDO instance).
// Adjust the include path if your config is in a different location.

header('Content-Type: application/json; charset=utf-8');

try {
    // adjust config path if needed
    require_once __DIR__ . '../../db/config.php'; // expects $pdo (PDO) available

    // Read GeoJSON
    $geojsonPath = __DIR__ . '/barangay_boundary.geojson';
    if(!file_exists($geojsonPath)) {
        echo json_encode(['error' => 'barangay_boundary.geojson not found']);
        exit;
    }
    $geojsonRaw = file_get_contents($geojsonPath);
    $geo = json_decode($geojsonRaw, true);
    if(!$geo || !isset($geo['features'])) {
        echo json_encode(['error' => 'Invalid geojson']);
        exit;
    }

    // Query approved bns_reports joined to reports
    $sql = "SELECT b.* 
            FROM bns_reports b
            JOIN reports r ON b.report_id = r.id
            WHERE r.status = 'Approved'
            ORDER BY b.barangay, b.year ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build index of geo features by barangay name (normalized)
    $geomIndex = [];
    foreach($geo['features'] as $f){
        $props = $f['properties'] ?? [];
        $bname = isset($props['BARANGAY']) ? trim($props['BARANGAY']) : null;
        if($bname){
            $geomIndex[strtoupper($bname)] = $f['geometry'];
        }
    }

    // Build feature list: for each approved report row, create feature using geometry from geomIndex
    $features = [];

    foreach($rows as $r){
        $b = trim($r['barangay']);
        $key = strtoupper($b);
        if(!isset($geomIndex[$key])){
            // If no geometry found, skip or create a NO_GEOM placeholder
            // We'll skip to keep things clean — you can log missing names if needed
            continue;
        }
        $geom = $geomIndex[$key];

        // Properties to send to client: include the percent fields and year and barangay
        $props = [
            'barangay' => $r['barangay'],
            'year' => $r['year'],
            'report_id' => $r['report_id'],
            'id' => $r['id'],

            // include all 9 indicators percent fields (if exist)
            'ind9b1_pct' => isset($r['ind9b1_pct']) ? (float)$r['ind9b1_pct'] : null,
            'ind9b2_pct' => isset($r['ind9b2_pct']) ? (float)$r['ind9b2_pct'] : null,
            'ind9b3_pct' => isset($r['ind9b3_pct']) ? (float)$r['ind9b3_pct'] : null,
            'ind9b4_pct' => isset($r['ind9b4_pct']) ? (float)$r['ind9b4_pct'] : null,
            'ind9b5_pct' => isset($r['ind9b5_pct']) ? (float)$r['ind9b5_pct'] : null,
            'ind9b6_pct' => isset($r['ind9b6_pct']) ? (float)$r['ind9b6_pct'] : null,
            'ind9b7_pct' => isset($r['ind9b7_pct']) ? (float)$r['ind9b7_pct'] : null,
            'ind9b8_pct' => isset($r['ind9b8_pct']) ? (float)$r['ind9b8_pct'] : null,
            'ind9b9_pct' => isset($r['ind9b9_pct']) ? (float)$r['ind9b9_pct'] : null,

            // include counts if useful
            'ind9b1_no' => isset($r['ind9b1_no']) ? (int)$r['ind9b1_no'] : null,
            'ind9b2_no' => isset($r['ind9b2_no']) ? (int)$r['ind9b2_no'] : null,
            'ind9b3_no' => isset($r['ind9b3_no']) ? (int)$r['ind9b3_no'] : null,
            'ind9b4_no' => isset($r['ind9b4_no']) ? (int)$r['ind9b4_no'] : null,
            'ind9b5_no' => isset($r['ind9b5_no']) ? (int)$r['ind9b5_no'] : null,
            'ind9b6_no' => isset($r['ind9b6_no']) ? (int)$r['ind9b6_no'] : null,
            'ind9b7_no' => isset($r['ind9b7_no']) ? (int)$r['ind9b7_no'] : null,
            'ind9b8_no' => isset($r['ind9b8_no']) ? (int)$r['ind9b8_no'] : null,
            'ind9b9_no' => isset($r['ind9b9_no']) ? (int)$r['ind9b9_no'] : null,
        ];

        $features[] = [
            'type' => 'Feature',
            'geometry' => $geom,
            'properties' => $props
        ];
    }

    $out = [
        'type' => 'FeatureCollection',
        'features' => $features
    ];

    echo json_encode($out);
    exit;

} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(['error' => $ex->getMessage()]);
    exit;
}
