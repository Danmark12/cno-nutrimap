<?php
// map.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>CNO NutriMap</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    /* ---------- layout & styles (kept similar to your design) ---------- */
    body { font-family: Arial, sans-serif; margin:0; background:#f8f8f8; color:#333; }
    header { background:#fff; border-bottom:1px solid #ccc; padding:10px 20px; display:flex; align-items:center; justify-content:space-between; }
    .content { padding:20px; }
    .section-title { color:#4a90e2; font-weight:bold; font-size:14px; }
    .section-subtitle { font-weight:bold; font-size:20px; margin:5px 0 20px 0; }
    .main-container { display:flex; background:#fff; border:1px solid #ccc; border-radius:6px; padding:15px; gap:12px; }
    .map-section { flex:2; border-right:1px solid #ddd; padding-right:20px; position:relative; }
    .map-header { font-size:14px; margin-bottom:10px; }
    .toggle-buttons { display:flex; gap:10px; margin-bottom:8px; }
    .toggle-buttons button { padding:6px 12px; border-radius:4px; border:1px solid #4a90e2; background:#fff; color:#4a90e2; cursor:pointer; }
    .toggle-buttons button.active { background:#4a90e2; color:#fff; }
    .map-box, .linegraph-box { height:480px; border:1px solid #ccc; border-radius:4px; background:#f3f3f3; position:relative; }
    #map { width:100%; height:100%; }
    .side-panel { width:320px; display:flex; flex-direction:column; gap:10px; padding-left:12px; }
    .dropdowns { display:flex; flex-direction:column; gap:10px; }
    select { padding:8px; border-radius:4px; border:1px solid #ccc; }
    .legend-item { display:flex; align-items:center; gap:8px; padding:6px;border-radius:6px; cursor:pointer; user-select:none; }
    .legend-item.active { background: rgba(0,0,0,0.06); transform:scale(1.01); }
    .legend-color { width:16px; height:16px; border-radius:3px; border:1px solid #aaa; display:inline-block; }
    .gradient-wrapper { margin-top:12px; }
    .gradient-grid { display:grid; grid-template-columns:repeat(10,1fr); gap:0; height:18px; border-radius:4px; overflow:hidden; border:1px solid #ccc; }
    .gradient-cell { height:18px; }
    .gradient-cell.active { outline:2px solid #000; transform:scale(1.02); }
    .percent-labels { display:flex; justify-content:space-between; font-size:12px; margin-top:6px; }
    .data-source { font-size:12px; color:#666; margin-top:8px; }
    .no-data-legend { font-size:13px; color:#666; margin-top:8px; }
    @media (max-width:900px) {
      .main-container { flex-direction:column; }
      .side-panel { width:100%; padding-left:0; border-top:1px solid #eee; margin-top:8px; }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="content">
    <div class="section-title">DATA</div>
    <div class="section-subtitle">Health and Nutrition</div>

    <div class="main-container">
      <div class="map-section">
        <div class="map-header">El Salvador — Health & Nutrition Map: Share of children who are stunted</div>

        <div class="toggle-buttons">
          <button id="mapBtn" class="active">Map</button>
          <button id="lineBtn">Line Graph</button>
        </div>

        <div class="map-box" id="mapBox">
          <div id="map"></div>
        </div>

        <div class="linegraph-box" id="lineBox" style="display:none;">
          <canvas id="mainChart" width="900" height="480"></canvas>
        </div>

        <div class="gradient-wrapper">
          <div id="gradient-grid" class="gradient-grid"></div>
          <div class="percent-labels"><span>0%</span><span>100%</span></div>
        </div>

        <div class="data-source">Data source: Operation Timbang Plus — showing only <strong>Approved</strong> reports</div>
      </div>

      <div class="side-panel">
        <div class="dropdowns">
          <label><strong>Select Year</strong></label>
          <select id="yearSelect"><option value="All">All</option></select>

          <label><strong>Select Barangay</strong></label>
          <select id="barangaySelect">
            <option value="All">All</option>
            <option>Amoros</option>
            <option>Bolisong</option>
            <option>Cogon</option>
            <option>Himaya</option>
            <option>Hinigdaan</option>
            <option>Kalabaylabay</option>
            <option>Molugan</option>
            <option>Pedro S. Baculio</option>
            <option>Poblacion</option>
            <option>Quibonbon</option>
            <option>Sambulawan</option>
            <option>San Francisco de Asis</option>
            <option>Sinaloc</option>
            <option>Taytay</option>
            <option>Ulaliman</option>
          </select>
        </div>

        <h3 style="margin:0 0 4px 0;">Legend (click to filter)</h3>
        <div id="legendList" style="display:flex;flex-direction:column;gap:6px;">
          <div class="legend-item" data-field="ind9b1_pct" data-color="#000000ff"><span class="legend-color" style="background:#939393"></span> Severely Underweight</div>
          <div class="legend-item" data-field="ind9b2_pct" data-color="#FFA500"><span class="legend-color" style="background:#FFA500"></span> Underweight</div>
          <div class="legend-item" data-field="ind9b3_pct" data-color="#016801"><span class="legend-color" style="background:#016801"></span> Normal Weight</div>
          <div class="legend-item" data-field="ind9b4_pct" data-color="#00F6FF"><span class="legend-color" style="background:#00F6FF"></span> Severely Wasted</div>
          <div class="legend-item" data-field="ind9b5_pct" data-color="#FFEA00"><span class="legend-color" style="background:#FFEA00"></span> Wasted</div>
          <div class="legend-item" data-field="ind9b6_pct" data-color="#8B4513"><span class="legend-color" style="background:#8B4513"></span> Overweight</div>
          <div class="legend-item" data-field="ind9b7_pct" data-color="#c70000"><span class="legend-color" style="background:#c70000"></span> Obese</div>
          <div class="legend-item" data-field="ind9b8_pct" data-color="#c7009c"><span class="legend-color" style="background:#c7009c"></span> Severely Stunted</div>
          <div class="legend-item" data-field="ind9b9_pct" data-color="#0073ca"><span class="legend-color" style="background:#0073ca"></span> Stunted</div>
        </div>

        <div class="no-data-legend">Gray = No data (shows all barangay borders even when there's no data)</div>
      </div>
    </div>
  </div>

  <!-- Leaflet & Chart libs -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

  <script>
  /**************************************************************************
   * Map & Chart application logic
   *
   * - Loads barangay_boundary.geojson (all polygons)
   * - Loads get_map_data.php (approved reports)
   * - Merges DB properties into geojson features (case-insensitive match on BARANGAY)
   * - Displays all polygons; no-data ones get #dcdcdc fill
   * - Legend & year & barangay filters update map + chart
   **************************************************************************/

  // Configuration
  const geoJsonPath = 'barangay_boundary.geojson';  // your geojson (must be accessible)
  const dataEndpoint = 'get_map_data.php';         // returns FeatureCollection of approved report rows
  const geoPropsName = 'BARANGAY';                 // property name in geojson that contains barangay string
  const NO_DATA_FILL = '#dcdcdc';                  // chosen no-data color

  // UI elements
  const yearSelect = document.getElementById('yearSelect');
  const barangaySelect = document.getElementById('barangaySelect');
  const legendItems = Array.from(document.querySelectorAll('#legendList .legend-item'));
  const gradientGrid = document.getElementById('gradient-grid');
  const mapBtn = document.getElementById('mapBtn');
  const lineBtn = document.getElementById('lineBtn');
  const mapBox = document.getElementById('mapBox');
  const lineBox = document.getElementById('lineBox');

  // app state
  let map, baseLayer;
  let geoAll = null;       // raw geojson
  let reportsData = null;  // features from get_map_data.php
  let mergedFeatures = []; // features with merged properties to display on map (one per geo polygon per report or aggregated)
  let geoLayer = null;
  let activeLegendField = null; // e.g. 'ind9b1_pct'
  let activeLegendColor = null;
  let activeYear = 'All';
  let activeBarangay = 'All';
  let activeGradientRange = null; // {min,max} or null
  let chart = null;

  // init map (fully pannable/zoomable)
  map = L.map('map', {
    center: [8.4760268, 124.4809540], // center of El Salvador, Misamis Oriental
    zoom: 12,
    minZoom: 10,
    maxZoom: 18,
    zoomControl: true,
    dragging: true,
    scrollWheelZoom: true
  });
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // toggle map/line
  mapBtn.addEventListener('click', ()=>{ mapBox.style.display='block'; lineBox.style.display='none'; mapBtn.classList.add('active'); lineBtn.classList.remove('active'); });
  lineBtn.addEventListener('click', ()=>{ mapBox.style.display='none'; lineBox.style.display='block'; lineBtn.classList.add('active'); mapBtn.classList.remove('active'); });

  // fetch both geojson and data, then prepare map
  Promise.all([
    fetch(geoJsonPath).then(r=>r.ok? r.json() : Promise.reject('GeoJSON load failed ('+r.status+')')),
    fetch(dataEndpoint).then(r=>r.ok? r.json() : r.text().then(t=>Promise.reject('Data endpoint error: '+t)))
  ]).then(([geojson, data])=>{
    geoAll = geojson;
    reportsData = data;
    // Ensure features arrays exist
    geoAll.features = geoAll.features || [];
    reportsData.features = reportsData.features || [];

    // Build lookup of reports per barangay & year (case-insensitive)
    // We'll attach to each geo feature a map of year->reportProperties (so geo always present)
    const reportsIndex = {}; // reportsIndex[BARANGAY_UPPER] = [ {year:..., props: {...}}, ... ]
    reportsData.features.forEach(f=>{
      const props = f.properties || {};
      const b = (props.barangay || '').toString().trim().toUpperCase();
      if(!b) return;
      if(!reportsIndex[b]) reportsIndex[b] = [];
      reportsIndex[b].push(props);
    });

    // Normalize: sort each barangay's reports by year ascending
    for(const k in reportsIndex){
      reportsIndex[k].sort((a,b)=> (Number(a.year||0) - Number(b.year||0)));
    }

    // Attach to geo features: a property 'reports' containing array of report props; also set a 'NO_DATA' flag if none
    geoAll.features.forEach(f=>{
      const props = f.properties || {};
      const bname = (props[geoPropsName] || props['BARANGAY'] || props['Barangay'] || '').toString().trim();
      const key = bname.toUpperCase();
      const list = reportsIndex[key] || [];
      f.properties._standard_name = bname;
      f.properties._reports = list; // array (possibly empty)
      f.properties._hasData = list.length > 0;
    });

    // Fill Year select options (unique years from reports)
    const yearsSet = new Set();
    reportsData.features.forEach(f=> { if(f.properties && f.properties.year) yearsSet.add(String(f.properties.year)); });
    const years = Array.from(yearsSet).sort((a,b)=> Number(b)-Number(a));
    yearSelect.innerHTML = '<option value="All">All</option>';
    years.forEach(y => { const o = document.createElement('option'); o.value = y; o.textContent = y; yearSelect.appendChild(o); });

    // initial draw
    drawMapLayer();
    drawChart();

    // Fit map to all geometry bounds (expand slightly)
    try {
      const geojsonLayer = L.geoJSON(geoAll);
      const bounds = geojsonLayer.getBounds();
      if(bounds.isValid()) map.fitBounds(bounds.pad(0.08));
    } catch(e) { console.warn('fitBounds failed', e); }

  }).catch(err=>{
    console.error('Failed to load data or geojson:', err);
    alert('Failed to load map or data. Open console for details.');
  });

  /*****************************
   * Draw / update GeoJSON layer
   *****************************/
  function drawMapLayer(){
    if(geoLayer) geoLayer.remove();

    // Build a feature collection filtered by year (if year selected, attach report props for that year; else attach "latest" report props)
    const featuresToShow = geoAll.features.map(f=>{
      // clone feature shallowly, we will add effective properties for display
      const clone = JSON.parse(JSON.stringify(f));
      const reports = f.properties._reports || [];
      let chosen = null;

      if(activeYear && activeYear !== 'All'){
        // find report for this year
        chosen = reports.find(r => String(r.year) === String(activeYear)) || null;
      } else {
        // choose latest (highest year) if exists
        if(reports.length > 0) chosen = reports.slice(-1)[0];
      }

      clone.properties._effective = chosen || null;
      // For convenience, expose standard name in lowercase for comparisons
      clone.properties._name = (f.properties._standard_name || '').toString();
      return clone;
    });

    geoLayer = L.geoJSON({ type:'FeatureCollection', features: featuresToShow }, {
      style: styleFeature,
      onEachFeature: function(feature, layer){
        const p = feature.properties || {};
        const name = p._name || 'Unknown';
        // Build popup content: show available indicators (if any) otherwise No Data
        if(p._effective){
          // show the 9 indicators percent values (present or N/A)
          const pe = p._effective;
          const popupHtml = `<strong>${name}</strong><br>Year: ${pe.year || 'N/A'}<br>
            Severely Underweight: ${pe.ind9b1_pct ?? 'N/A'}%<br>
            Underweight: ${pe.ind9b2_pct ?? 'N/A'}%<br>
            Normal: ${pe.ind9b3_pct ?? 'N/A'}%<br>
            Severely Wasted: ${pe.ind9b4_pct ?? 'N/A'}%<br>
            Wasted: ${pe.ind9b5_pct ?? 'N/A'}%<br>
            Overweight: ${pe.ind9b6_pct ?? 'N/A'}%<br>
            Obese: ${pe.ind9b7_pct ?? 'N/A'}%<br>
            Severely Stunted: ${pe.ind9b8_pct ?? 'N/A'}%<br>
            Stunted: ${pe.ind9b9_pct ?? 'N/A'}%`;
          layer.bindPopup(popupHtml);
        } else {
          layer.bindPopup(`<strong>${name}</strong><br>No Data`);
        }

        // highlight on hover
        layer.on('mouseover', function(){
          this.setStyle({ weight:4, color:'#000' });
          this.openPopup();
        });
        layer.on('mouseout', function(){
          // revert to styleFeature
          geoLayer.resetStyle(this);
        });

        // click selects barangay in dropdown and highlights others
        layer.on('click', function(){
          const bname = feature.properties._name || '';
          activeBarangay = bname || 'All';
          // set select value if exists, else set to All
          const foundOption = Array.from(barangaySelect.options).find(o=>o.value === activeBarangay || o.text === activeBarangay);
          if(foundOption) barangaySelect.value = foundOption.value || foundOption.text;
          else barangaySelect.value = 'All';
          highlightSelectedBarangay();
          drawChart();
        });
      }
    }).addTo(map);

    highlightSelectedBarangay(); // apply any barangay filtering/highlighting
  }

  // Style each polygon based on activeLegendField and active gradient/selection
  function styleFeature(feature){
    const p = feature.properties || {};
    const eff = p._effective || null; // chosen report for current year or latest
    const hasData = !!eff;
    // base style (all borders black)
    let style = { color:'#000', weight:2, fillOpacity: 0.15, fillColor: NO_DATA_FILL };

    if(!hasData){
      // No data -> light gray fill, still show border
      style.fillColor = NO_DATA_FILL;
      style.fillOpacity = 0.6;
      style.dashArray = ''; // solid border
      style.color = '#000';
      style.weight = 1.5;
      return style;
    }

    // if a legend is selected, color by that indicator
    if(activeLegendField && activeLegendColor && eff && eff[activeLegendField] != null){
      const val = Number(eff[activeLegendField]) || 0;
      style.fillColor = interpolateColor(activeLegendColor, val);
      style.fillOpacity = 0.9;
      style.color = '#000';
      style.weight = 2;
    } else {
      // no legend selected -> neutral pastel fill (combined or lite)
      style.fillColor = '#e9e9e9';
      style.fillOpacity = 0.28;
      style.color = '#000';
      style.weight = 1.8;
    }

    // apply gradient filter (if any): if activeGradientRange exists then dim features outside the range
    if(activeGradientRange && activeLegendField && eff){
      const v = Number(eff[activeLegendField]) || 0;
      const inRange = (v >= activeGradientRange.min && v <= activeGradientRange.max);
      if(!inRange){
        style.fillOpacity = Math.min(style.fillOpacity, 0.12);
        style.color = '#777';
      } else {
        style.fillOpacity = Math.max(style.fillOpacity, 0.85);
        style.color = '#000';
      }
    }

    // apply barangay selection visual: if selected, highlight; if not selected and a selection exists, blur/dim
    if(activeBarangay && activeBarangay !== 'All'){
      if(String(p._name) === String(activeBarangay)){
        style.weight = 4;
        style.color = '#000';
        style.fillOpacity = Math.max(style.fillOpacity, 0.9);
      } else {
        style.fillOpacity = Math.min(style.fillOpacity, 0.08);
        style.color = '#999';
      }
    }

    return style;
  }

  // highlight selected barangay (re-style layer)
  function highlightSelectedBarangay(){
    if(!geoLayer) return;
    geoLayer.eachLayer(layer=>{
      const p = layer.feature.properties || {};
      const name = p._name || '';
      if(activeBarangay && activeBarangay !== 'All'){
        if(String(name) === String(activeBarangay)){
          layer.setStyle({ weight:4, color:'#000', fillOpacity:0.95 });
          try { map.fitBounds(layer.getBounds()); } catch(e) {}
          layer.openPopup();
        } else {
          layer.setStyle({ fillOpacity:0.08, color:'#999' });
        }
      } else {
        // reset all styles
        geoLayer.resetStyle(layer);
      }
    });
  }

  /************************
   * Legend & Gradient UI
   ************************/
  legendItems.forEach(li=>{
    li.addEventListener('click', ()=>{
      const field = li.dataset.field;
      const color = li.dataset.color;
      // toggle selection
      if(activeLegendField === field){
        // clear
        activeLegendField = null;
        activeLegendColor = null;
        li.classList.remove('active');
        resetGradient();
      } else {
        activeLegendField = field;
        activeLegendColor = color;
        // highlight clicked legend and remove active from others
        legendItems.forEach(x=>x.classList.remove('active'));
        li.classList.add('active');
        buildGradient(color);
      }
      drawMapLayer();
      drawChart();
    });
  });

  // build 10-cell gradient grid using activeLegendColor
  function buildGradient(baseHex){
    gradientGrid.innerHTML = '';
    if(!baseHex) return;
    for(let i=0;i<10;i++){
      const min = i*10;
      const max = (i+1)*10;
      const val = (i+1)*10;
      const cell = document.createElement('div');
      cell.className = 'gradient-cell';
      cell.style.background = interpolateColor(baseHex, val);
      cell.title = `${min}% - ${max}%`;
      cell.dataset.min = min;
      cell.dataset.max = max;

      cell.addEventListener('click', (ev)=>{
        // set active gradient range
        activeGradientRange = { min, max };
        // mark active cell
        Array.from(gradientGrid.children).forEach(ch=>ch.classList.remove('active'));
        cell.classList.add('active');
        drawMapLayer();
        drawChart();
      });

      cell.addEventListener('mouseover', ()=>{
        // temporary filter on hover
        activeGradientRange = { min, max };
        cell.classList.add('active');
        drawMapLayer();
      });
      cell.addEventListener('mouseout', ()=>{
        // remove temporary unless clicked
        if(!cell.classList.contains('active')) {
          activeGradientRange = null;
          drawMapLayer();
        }
        cell.classList.remove('active');
      });

      gradientGrid.appendChild(cell);
    }
    // add "No Data" trailing cell (transparent)
    const nodata = document.createElement('div');
    nodata.className = 'gradient-cell';
    nodata.style.background = '#ffffff';
    nodata.style.border = '1px dashed #777';
    nodata.title = 'No Data';
    gradientGrid.appendChild(nodata);
  }

  function resetGradient(){
    gradientGrid.innerHTML = '';
    activeGradientRange = null;
  }

  /************************
   * Year & Barangay UI
   ************************/
  yearSelect.addEventListener('change', ()=>{
    activeYear = yearSelect.value || 'All';
    drawMapLayer();
    drawChart();
  });

  barangaySelect.addEventListener('change', ()=>{
    activeBarangay = barangaySelect.value || 'All';
    highlightSelectedBarangay();
    drawChart();
  });

  // double-click map to clear barangay selection
  map.on('dblclick', ()=>{
    activeBarangay = 'All';
    barangaySelect.value = 'All';
    highlightSelectedBarangay();
    drawChart();
  });

  /************************
   * Chart drawing (Chart.js)
   ************************/
  function drawChart(){
    // Destroy previous chart
    if(chart) { chart.destroy(); chart = null; }

    // If legend selected -> show time series for selected indicator
    if(activeLegendField){
      // Build years array (sorted ascending)
      const yearsSet = new Set();
      reportsData.features.forEach(f=>{
        if(f.properties && f.properties.year) yearsSet.add(String(f.properties.year));
      });
      const years = Array.from(yearsSet).sort((a,b)=> Number(a)-Number(b));
      if(years.length === 0) {
        // no data
        const ctx = document.getElementById('mainChart').getContext('2d');
        chart = new Chart(ctx, { type:'bar', data:{ labels:['No Data'], datasets:[{label:'No Data', data:[0]}] } });
        return;
      }

      // If a single barangay selected -> show its series
      if(activeBarangay && activeBarangay !== 'All'){
        const values = years.map(y=>{
          // find report for this barangay & year from reportsData
          const f = reportsData.features.find(ff => (ff.properties.barangay||'').toString().trim() === activeBarangay && String(ff.properties.year) === String(y));
          return f && f.properties && f.properties[activeLegendField] != null ? Number(f.properties[activeLegendField]) : null;
        });
        const ctx = document.getElementById('mainChart').getContext('2d');
        chart = new Chart(ctx, {
          type: 'line',
          data: { labels: years, datasets: [{ label: `${activeBarangay} — ${activeLegendField}`, data: values, borderColor: activeLegendColor, backgroundColor: activeLegendColor, fill:false, tension:0.2 }]},
          options: { plugins:{ legend:{display:false} }, scales:{ y:{ min:0, max:100 } }, responsive:true, maintainAspectRatio:false }
        });
        return;
      }

      // All barangays: pick top N barangays by latest value of this indicator and show multiple series
      // Compute latest per barangay
      const latest = {};
      reportsData.features.forEach(f=>{
        const b = (f.properties.barangay||'').toString().trim();
        const yr = Number(f.properties.year||0);
        if(!b) return;
        if(!latest[b] || yr > latest[b].year){
          latest[b] = { year: yr, value: Number(f.properties[activeLegendField] ?? 0) };
        }
      });
      // sort by value desc and take top 6
      const sorted = Object.entries(latest).sort((a,b)=> b[1].value - a[1].value).slice(0,6);
      const labels = years;
      const datasets = sorted.map((entry, idx)=>{
        const bname = entry[0];
        const color = palette(idx);
        const vals = labels.map(y => {
          const f = reportsData.features.find(ff => (ff.properties.barangay||'').toString().trim() === bname && String(ff.properties.year) === String(y));
          return f && f.properties && f.properties[activeLegendField] != null ? Number(f.properties[activeLegendField]) : null;
        });
        return { label: bname, data: vals, borderColor: color, backgroundColor: color, fill:false, tension:0.2 };
      });

      const ctx = document.getElementById('mainChart').getContext('2d');
      chart = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets },
        options: { plugins:{ legend:{display:true} }, scales:{ y:{ min:0, max:100 } }, responsive:true, maintainAspectRatio:false }
      });
      return;
    }

    // If no legend selected:
    // - If a single barangay selected -> show 9 indicators as a bar chart for that barangay (latest or selected year)
    // - If All barangays -> show top barangays by stunted (ind9b9_pct) as a bar chart
    if(activeBarangay && activeBarangay !== 'All'){
      // find report for this barangay (respect year)
      let candidate = null;
      if(activeYear && activeYear !== 'All'){
        candidate = reportsData.features.find(f => (f.properties.barangay||'').toString().trim() === activeBarangay && String(f.properties.year) === String(activeYear));
      } else {
        // pick latest
        const list = reportsData.features.filter(f => (f.properties.barangay||'').toString().trim() === activeBarangay);
        if(list.length) candidate = list.sort((a,b)=> Number(b.properties.year||0) - Number(a.properties.year||0))[0];
      }
      const labels = ['Sev Under','Under','Normal','Sev Wasted','Wasted','Overweight','Obese','Sev Stunted','Stunted'];
      const keys = ['ind9b1_pct','ind9b2_pct','ind9b3_pct','ind9b4_pct','ind9b5_pct','ind9b6_pct','ind9b7_pct','ind9b8_pct','ind9b9_pct'];
      const colors = ['#000000ff','#FFA500','#016801','#00F6FF','#FFEA00','#8B4513','#c70000','#c7009c','#0073ca'];
      const values = keys.map(k => candidate && candidate.properties && candidate.properties[k] != null ? Number(candidate.properties[k]) : 0);

      const ctx = document.getElementById('mainChart').getContext('2d');
      chart = new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets: [{ label: `${activeBarangay} (${candidate?candidate.properties.year:'N/A'})`, data: values, backgroundColor: colors, borderColor: colors, borderWidth:1 }]},
        options: { plugins:{ legend:{display:false} }, scales:{ y:{ beginAtZero:true, max:100 } }, responsive:true, maintainAspectRatio:false }
      });
      return;
    }

    // All barangays and no legend: show latest stunted % (ind9b9_pct) per barangay (top N)
    // Build latest per barangay
    const latestByBarangay = {};
    reportsData.features.forEach(f=>{
      const b = (f.properties.barangay||'').toString().trim();
      if(!b) return;
      const yr = Number(f.properties.year||0);
      if(!latestByBarangay[b] || yr > latestByBarangay[b].year){
        latestByBarangay[b] = { year: yr, value: Number(f.properties.ind9b9_pct ?? 0) };
      }
    });
    const arr = Object.entries(latestByBarangay).map(([b,obj]) => ({ barangay:b, year:obj.year, value:obj.value }));
    arr.sort((a,b)=> b.value - a.value);
    const top = arr.slice(0,12);
    const labels = top.map(t=>t.barangay);
    const values = top.map(t=>t.value);

    const ctx = document.getElementById('mainChart').getContext('2d');
    chart = new Chart(ctx, {
      type: 'bar',
      data: { labels, datasets: [{ label:'Stunted (%) - latest per barangay', data: values, backgroundColor: '#0073ca' }]},
      options: { plugins:{ legend:{display:false} }, scales:{ y:{ beginAtZero:true, max:100 } }, responsive:true, maintainAspectRatio:false }
    });
  }

  // palette helper for multiple lines
  function palette(i){
    const p = ['#c70000','#FFA500','#016801','#0073ca','#c7009c','#8B4513','#00F6FF','#FFEA00','#000000ff'];
    return p[i % p.length];
  }

  /************************
   * Helpers
   ************************/
  // Interpolate base hex color to a "lighter->darker" scale by percent (0-100).
  // We blend between a light start (near white) to the base color as percent increases.
  function interpolateColor(hex, percent){
    const rgb = hexToRgb(hex);
    const start = { r: 230, g: 230, b: 230 }; // light base
    const ratio = Math.max(0, Math.min(1, percent/100));
    const r = Math.round(start.r + (rgb.r - start.r) * ratio);
    const g = Math.round(start.g + (rgb.g - start.g) * ratio);
    const b = Math.round(start.b + (rgb.b - start.b) * ratio);
    return `rgb(${r},${g},${b})`;
  }
  function hexToRgb(hex){
    const h = hex.replace('#','');
    const num = parseInt(h,16);
    return { r:(num>>16)&255, g:(num>>8)&255, b:num&255 };
  }

  </script>
</body>
</html>
