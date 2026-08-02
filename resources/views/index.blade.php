<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Energy Audit Portal</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

<style>
  :root{
    --ep-navy:#0F2B2E;
    --ep-teal:#12776B;
    --ep-teal-dark:#0C5A51;
    --ep-teal-tint:#E4F2EF;
    --ep-amber:#E2A63B;
    --ep-red:#D3563B;
    --ep-green:#3FA66D;
    --ep-ink:#16232A;
    --ep-paper:#F5F7F5;
    --ep-line:#D7DED9;
    --font-display:'Space Grotesk', system-ui, sans-serif;
    --font-body:'Inter', system-ui, -apple-system, sans-serif;
    --font-mono:'IBM Plex Mono', ui-monospace, 'Consolas', monospace;
  }
  body{
    background:var(--ep-paper);
    color:var(--ep-ink);
    font-family:var(--font-body);
  }
  h1,h2,h3,h4,h5,.brand,.section-eyebrow,.metric-value,.gauge-readout,.stat-num{
    font-family:var(--font-display);
  }
  .mono{ font-family:var(--font-mono); }

  /* Navbar */
  .ep-navbar{ background:var(--ep-navy); }
  .ep-navbar .brand{ color:#fff; font-weight:700; letter-spacing:.2px; }
  .ep-navbar .brand i{ color:var(--ep-amber); }
  .ep-navbar .nav-link{ color:rgba(255,255,255,.75); }
  .ep-navbar .nav-link.active{ color:#fff; }

  /* Hero strip */
  .ep-hero{
    background:linear-gradient(135deg, var(--ep-navy) 0%, var(--ep-teal-dark) 100%);
    color:#fff;
    padding:2.25rem 0 2.75rem;
  }
  .ep-hero .section-eyebrow{
    text-transform:uppercase;
    font-size:.72rem;
    letter-spacing:.14em;
    color:var(--ep-amber);
    font-weight:600;
  }
  .ep-hero h1{ font-weight:700; font-size:1.9rem; margin-bottom:.35rem; }
  .ep-hero p{ color:rgba(255,255,255,.75); max-width:46ch; margin-bottom:0; }

  /* Cards */
  .ep-card{
    background:#fff;
    border:1px solid var(--ep-line);
    border-radius:10px;
    margin-bottom:1.25rem;
  }
  .ep-card .ep-card-head{
    padding:1rem 1.25rem;
    border-bottom:1px solid var(--ep-line);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:.75rem;
  }
  .ep-card .ep-card-head h2{
    font-size:1.02rem;
    font-weight:600;
    margin:0;
    display:flex;
    align-items:center;
    gap:.55rem;
  }
  .ep-card .ep-card-head h2 i{ color:var(--ep-teal); }
  .ep-card .ep-card-body{ padding:1.25rem; }
  .step-badge{
    width:26px; height:26px; border-radius:50%;
    background:var(--ep-teal-tint); color:var(--ep-teal-dark);
    display:inline-flex; align-items:center; justify-content:center;
    font-family:var(--font-mono); font-size:.78rem; font-weight:600;
  }

  /* Form controls */
  label.form-label{ font-size:.82rem; font-weight:500; color:#3c4a4d; }
  .form-control, .form-select{ font-size:.92rem; }
  .form-control:focus, .form-select:focus{
    border-color:var(--ep-teal);
    box-shadow:0 0 0 .2rem rgba(18,119,107,.15);
  }
  .input-group-text{ font-family:var(--font-mono); font-size:.8rem; background:var(--ep-teal-tint); border-color:#cfe4e0; color:var(--ep-teal-dark); }

  /* Appliance checklist */
  .appliance-row{
    border:1px solid var(--ep-line);
    border-radius:8px;
    padding:.75rem .9rem;
    margin-bottom:.6rem;
    background:#fcfdfc;
  }
  .appliance-row.is-checked{ background:var(--ep-teal-tint); border-color:#bfe0da; }
  .appliance-row .form-check-input{ width:1.15em; height:1.15em; margin-top:.15em; }
  .appliance-row .form-check-input:checked{ background-color:var(--ep-teal); border-color:var(--ep-teal); }
  .appliance-name-input{ font-weight:600; border:0; background:transparent; padding:0; font-size:.94rem; }
  .appliance-name-input:focus{ outline:none; box-shadow:none; background:#fff; }
  .category-pill{
    font-family:var(--font-mono);
    font-size:.68rem;
    text-transform:uppercase;
    letter-spacing:.04em;
    padding:.2rem .5rem;
    border-radius:20px;
    background:#eef2f1;
    color:#4b5b5e;
    white-space:nowrap;
  }
  .appliance-fields input{ font-family:var(--font-mono); font-size:.85rem; text-align:right; }
  .appliance-fields .field-label{ font-size:.68rem; color:#6b7876; display:block; margin-bottom:.15rem; }
  .remove-row-btn{ color:#a3423a; }

  .btn-ep-primary{
    background:var(--ep-teal); border-color:var(--ep-teal); color:#fff;
    font-weight:600; letter-spacing:.01em;
  }
  .btn-ep-primary:hover{ background:var(--ep-teal-dark); border-color:var(--ep-teal-dark); color:#fff; }
  .btn-ep-outline{
    border-color:var(--ep-teal); color:var(--ep-teal-dark); font-weight:600;
  }
  .btn-ep-outline:hover{ background:var(--ep-teal-tint); color:var(--ep-teal-dark); }

  /* Gauge */
  .gauge-wrap{ text-align:center; }
  .gauge-readout{ font-size:1.05rem; font-weight:600; margin-top:.25rem; }
  .gauge-readout .unit{ font-size:.7rem; color:#6b7876; font-weight:500; }
  .rating-pill{
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.3rem .75rem; border-radius:20px;
    font-family:var(--font-mono); font-size:.75rem; font-weight:600;
    text-transform:uppercase; letter-spacing:.05em;
    margin-top:.5rem;
  }
  .rating-low{ background:#e5f5ec; color:var(--ep-green); }
  .rating-moderate{ background:#fbf1de; color:#a8781f; }
  .rating-high{ background:#fbe7e3; color:var(--ep-red); }

  /* Metric stat blocks */
  .stat-block{ text-align:center; padding:.6rem .25rem; }
  .stat-num{ font-size:1.5rem; font-weight:700; color:var(--ep-navy); }
  .stat-label{ font-size:.72rem; color:#6b7876; text-transform:uppercase; letter-spacing:.05em; }

  /* Advisor cards */
  .advisor-card{
    border:1px solid var(--ep-line); border-radius:10px; padding:1rem 1.1rem;
    height:100%; background:#fff; border-left:4px solid var(--ep-teal);
  }
  .advisor-card .savings-tag{
    font-family:var(--font-mono); font-size:.78rem; font-weight:600;
    color:var(--ep-green); background:#e5f5ec; padding:.15rem .5rem; border-radius:6px;
  }
  .advisor-card h5{ font-size:.95rem; font-weight:600; margin:.5rem 0 .35rem; }
  .advisor-card p{ font-size:.85rem; color:#4b5b5e; margin-bottom:0; }

  .results-hidden{ display:none; }

  footer.ep-footer{ color:#6b7876; font-size:.82rem; padding:1.75rem 0; }

  @media (max-width:767.98px){
    .ep-hero h1{ font-size:1.5rem; }
    .appliance-fields .col{ margin-bottom:.4rem; }
  }
</style>
</head>
<body>

<nav class="navbar navbar-expand-md ep-navbar navbar-dark py-3">
  <div class="container">
    <a class="navbar-brand brand" href="#"><i class="bi bi-lightning-charge-fill"></i> Energy Audit Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#epNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="epNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#audit-form">New Audit</a></li>
        <li class="nav-item"><a class="nav-link" href="#results">Results</a></li>
        <li class="nav-item"><a class="nav-link" href="#advisor">Advisor</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="ep-hero">
  <div class="container">
    <div class="section-eyebrow">Building energy audit</div>
    <h1>Log appliances, see consumption instantly.</h1>
    <p>Built for facility and building managers working from a phone on the floor — check off equipment, and get a live consumption reading with savings recommendations.</p>
  </div>
</header>

<main class="container py-4">
  <form id="auditForm" novalidate>

    <!-- Building details -->
    <section class="ep-card" id="audit-form">
      <div class="ep-card-head">
        <h2><span class="step-badge">1</span> Building details</h2>
      </div>
      <div class="ep-card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Building name</label>
            <input type="text" class="form-control" id="buildingName" placeholder="e.g. Riverside Office Tower" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email (for report delivery)</label>
            <input type="email" class="form-control" id="youremail" placeholder="your_name@gmail.com">
          </div>
          <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" id="buildingAddress" placeholder="Street, city, state">
          </div>
          <div class="col-md-3 col-6">
            <label class="form-label">Building type</label>
            <select class="form-select" id="buildingType">
              <option>Office</option>
              <option>Retail</option>
              <option>Warehouse</option>
              <option>Multifamily Residential</option>
              <option>Healthcare</option>
              <option>School</option>
              <option>Mixed Use</option>
            </select>
          </div>
          <div class="col-md-3 col-6">
            <label class="form-label">Floors</label>
            <input type="number" class="form-control" id="buildingFloors" value="3" min="1">
          </div>
          <div class="col-md-4 col-6">
            <label class="form-label">Square footage</label>
            <div class="input-group">
              <input type="number" class="form-control" id="buildingSqft" value="25000" min="100" step="100">
              <span class="input-group-text">ft²</span>
            </div>
          </div>
          <div class="col-md-4 col-6">
            <label class="form-label">Occupants</label>
            <input type="number" class="form-control" id="buildingOccupants" value="80" min="0">
          </div>
          <div class="col-md-4">
            <label class="form-label">Utility rate</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" class="form-control" id="ratePerKwh" value="0.14" min="0" step="0.01">
              <span class="input-group-text">/kWh</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Appliance checklist -->
    <section class="ep-card">
      <div class="ep-card-head">
        <h2><span class="step-badge">2</span> Appliance checklist</h2>
        <button type="button" class="btn btn-sm btn-ep-outline" id="addApplianceBtn">
          <i class="bi bi-plus-lg"></i> Add appliance
        </button>
      </div>
      <div class="ep-card-body">
        <p class="text-muted small mb-3">Check every appliance group present in the building and adjust quantity, wattage, and daily runtime as needed.</p>
        <div id="applianceList"></div>
      </div>
    </section>

    <div class="d-grid d-md-flex justify-content-md-end mb-4">
      <button type="submit" class="btn btn-ep-primary btn-lg px-4">
        <i class="bi bi-speedometer2"></i> Run energy audit
      </button>
    </div>
  </form>

  <!-- Results -->
  <section id="results" class="results-hidden">
    <div class="row g-3 mb-1">
      <div class="col-lg-5">
        <div class="ep-card h-100">
          <div class="ep-card-head"><h2><i class="bi bi-speedometer2"></i> Consumption gauge</h2></div>
          <div class="ep-card-body gauge-wrap">
            <svg id="gaugeSvg" viewBox="0 0 300 180" style="max-width:280px; width:100%;">
              <path id="bandLow" fill="none" stroke-width="22" stroke-linecap="round"></path>
              <path id="bandMod" fill="none" stroke-width="22" stroke-linecap="round"></path>
              <path id="bandHigh" fill="none" stroke-width="22" stroke-linecap="round"></path>
              <g id="ticks"></g>
              <g id="needleGroup">
                <line x1="150" y1="160" x2="150" y2="55" stroke="#16232A" stroke-width="4" stroke-linecap="round"/>
                <circle cx="150" cy="160" r="8" fill="#16232A"/>
              </g>
            </svg>
            <div class="gauge-readout mono" id="gaugeEuiReadout">0.00 <span class="unit">kWh/ft²/mo</span></div>
            <div id="ratingPill" class="rating-pill rating-low">Low usage</div>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="ep-card h-100">
          <div class="ep-card-head"><h2><i class="bi bi-graph-up"></i> Key metrics</h2></div>
          <div class="ep-card-body">
            <div class="row g-2 text-center mb-3">
              <div class="col-4 stat-block">
                <div class="stat-num mono" id="statDailyKwh">0</div>
                <div class="stat-label">kWh / day</div>
              </div>
              <div class="col-4 stat-block">
                <div class="stat-num mono" id="statMonthlyKwh">0</div>
                <div class="stat-label">kWh / month</div>
              </div>
              <div class="col-4 stat-block">
                <div class="stat-num mono" id="statCost">$0</div>
                <div class="stat-label">Est. monthly cost</div>
              </div>
            </div>
            <canvas id="savingsChart" height="160"></canvas>
          </div>
        </div>
      </div>
    </div>

    <section class="ep-card" id="advisor">
      <div class="ep-card-head">
        <h2><i class="bi bi-lightbulb"></i> Advisor recommendations</h2>
      </div>
      <div class="ep-card-body">
        <div class="row g-3" id="advisorCards"></div>
      </div>
    </section>

    <section class="ep-card">
      <div class="ep-card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
          <h5 class="mb-1"><i class="bi bi-envelope"></i> Email this report</h5>
          <p class="text-muted small mb-0">Sends the audit summary to the users email above via the Laravel API.</p>
        </div>
        <button type="button" class="btn btn-ep-primary" id="emailReportBtn">
          <i class="bi bi-send"></i> Send report
        </button>
      </div>
    </section>
  </section>
</main>

<footer class="ep-footer text-center border-top">
  <div class="container">Energy Audit Portal &middot; data stays on this device until you send a report</div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// -----------------------------------------------------------------------
// Point this at your deployed Laravel API (see /laravel-backend/routes/api.php)
// -----------------------------------------------------------------------
const API_BASE_URL = 'http://127.0.0.1:8000/api';

// Default appliance checklist for a typical commercial building.
const DEFAULT_APPLIANCES = [
  { name:'HVAC System',              category:'HVAC',              wattage:5000, quantity:1,  hours:10, checked:true },
  { name:'LED Lighting',             category:'Lighting',          wattage:40,   quantity:50, hours:12, checked:true },
  { name:'Refrigeration / Cold Storage', category:'Refrigeration', wattage:750,  quantity:2,  hours:24, checked:true },
  { name:'Water Heater',             category:'Water Heating',     wattage:4000, quantity:1,  hours:6,  checked:false },
  { name:'Office Equipment',         category:'Office Equipment',  wattage:150,  quantity:30, hours:9,  checked:true },
  { name:'Elevators',                category:'Elevators',         wattage:3000, quantity:1,  hours:8,  checked:false },
  { name:'Ventilation Fans',         category:'Ventilation',       wattage:1000, quantity:2,  hours:16, checked:false },
  { name:'Kitchen Equipment',        category:'Kitchen Equipment', wattage:2500, quantity:1,  hours:6,  checked:false },
  { name:'Server / IT Room',         category:'IT/Server',         wattage:2000, quantity:1,  hours:24, checked:false },
];

const CATEGORIES = ['HVAC','Lighting','Refrigeration','Water Heating','Office Equipment','Elevators','Ventilation','Kitchen Equipment','IT/Server','Other'];

// Mirrors database/seeders/SampleAuditDataSeeder.php on the backend.
const SAVING_TIPS = [
  { category:'HVAC', title:'Schedule setback temperatures after hours', description:'Programmable schedules that widen the setpoint outside occupied hours typically cut HVAC runtime without affecting comfort.', savings:12, priority:1 },
  { category:'HVAC', title:'Service filters and coils quarterly', description:'Dirty filters and coils force systems to run longer to hit the same setpoint.', savings:6, priority:2 },
  { category:'Lighting', title:'Convert remaining fixtures to LED', description:'Legacy fluorescent or halogen fixtures draw several times more power per lumen than LED equivalents.', savings:15, priority:1 },
  { category:'Lighting', title:'Add occupancy sensors in low-traffic areas', description:'Storage rooms, stairwells, and restrooms rarely need continuous lighting.', savings:8, priority:2 },
  { category:'Refrigeration', title:'Check door seals and defrost cycles', description:'Worn gaskets let cold air escape, forcing compressors to cycle more often.', savings:5, priority:1 },
  { category:'Water Heating', title:'Lower setpoint to 120°F (49°C)', description:'Each 10°F reduction in setpoint saves meaningfully on standby losses.', savings:7, priority:1 },
  { category:'Office Equipment', title:'Enforce sleep mode on idle workstations', description:'Fleet-wide power management settings on computers and monitors add up across a floor.', savings:9, priority:1 },
  { category:'Elevators', title:'Enable standby / regenerative drive mode', description:'Modern drives can recover braking energy and idle down between calls.', savings:10, priority:1 },
  { category:'Ventilation', title:'Install variable frequency drives on fans', description:'VFDs let fans match airflow to real demand instead of running at fixed speed.', savings:14, priority:1 },
  { category:'Kitchen Equipment', title:'Replace aging units with ENERGY STAR models', description:'Commercial kitchen equipment is often the oldest, least efficient gear in a building.', savings:11, priority:1 },
  { category:'IT/Server', title:'Consolidate or virtualize underused servers', description:'Idle physical servers draw near-full power for a fraction of the workload.', savings:18, priority:1 },
];

let rowCounter = 0;

function applianceRowTemplate(data) {
  const id = 'row_' + (rowCounter++);
  const checkedAttr = data.checked ? 'checked' : '';
  const options = CATEGORIES.map(c => `<option value="${c}" ${c === data.category ? 'selected' : ''}>${c}</option>`).join('');
  return `
  <div class="appliance-row ${data.checked ? 'is-checked' : ''}" data-id="${id}">
    <div class="d-flex align-items-start gap-2 mb-2">
      <input type="checkbox" class="form-check-input mt-1 chk-include" ${checkedAttr}>
      <div class="flex-grow-1">
        <input type="text" class="form-control appliance-name-input p-0" value="${data.name}">
        <select class="form-select form-select-sm category-select mt-1" style="width:auto; display:inline-block; font-family:var(--font-mono); font-size:.72rem;">
          ${options}
        </select>
      </div>
      <button type="button" class="btn btn-sm remove-row-btn" title="Remove"><i class="bi bi-trash3"></i></button>
    </div>
    <div class="row g-2 appliance-fields">
      <div class="col-4 col-md-3">
        <span class="field-label">Wattage (W)</span>
        <input type="number" class="form-control form-control-sm fld-wattage" value="${data.wattage}" min="0">
      </div>
      <div class="col-4 col-md-3">
        <span class="field-label">Quantity</span>
        <input type="number" class="form-control form-control-sm fld-quantity" value="${data.quantity}" min="0">
      </div>
      <div class="col-4 col-md-3">
        <span class="field-label">Hours / day</span>
        <input type="number" class="form-control form-control-sm fld-hours" value="${data.hours}" min="0" max="24" step="0.5">
      </div>
    </div>
  </div>`;
}

function renderApplianceList() {
  const list = document.getElementById('applianceList');
  list.innerHTML = DEFAULT_APPLIANCES.map(applianceRowTemplate).join('');
}
renderApplianceList();

document.getElementById('addApplianceBtn').addEventListener('click', () => {
  const list = document.getElementById('applianceList');
  const div = document.createElement('div');
  div.innerHTML = applianceRowTemplate({ name:'Custom Appliance', category:'Other', wattage:100, quantity:1, hours:8, checked:true });
  list.appendChild(div.firstElementChild);
});

document.getElementById('applianceList').addEventListener('click', (e) => {
  if (e.target.closest('.remove-row-btn')) {
    e.target.closest('.appliance-row').remove();
  }
});
document.getElementById('applianceList').addEventListener('change', (e) => {
  if (e.target.classList.contains('chk-include')) {
    e.target.closest('.appliance-row').classList.toggle('is-checked', e.target.checked);
  }
});

// -------------------- Gauge (SVG) --------------------
function polarToCartesian(cx, cy, r, angleDeg) {
  const rad = (angleDeg) * Math.PI / 180;
  return { x: cx + r * Math.sin(rad), y: cy - r * Math.cos(rad) };
}
function describeArc(cx, cy, r, startAngle, endAngle) {
  const start = polarToCartesian(cx, cy, r, startAngle);
  const end = polarToCartesian(cx, cy, r, endAngle);
  const largeArcFlag = (endAngle - startAngle) > 180 ? 1 : 0;
  return `M ${start.x} ${start.y} A ${r} ${r} 0 ${largeArcFlag} 1 ${end.x} ${end.y}`;
}

const EUI_LOW = 1.2, EUI_MOD = 2.2, EUI_MAX = 4.0; // matches AuditReport thresholds
const GAUGE_CX = 150, GAUGE_CY = 160, GAUGE_R = 110;

function angleForEui(eui) {
  const clamped = Math.max(0, Math.min(eui, EUI_MAX));
  return -90 + 180 * (clamped / EUI_MAX);
}

function initGaugeStatic() {
  const lowEnd = -90 + 180 * (EUI_LOW / EUI_MAX);
  const modEnd = -90 + 180 * (EUI_MOD / EUI_MAX);
  document.getElementById('bandLow').setAttribute('d', describeArc(GAUGE_CX, GAUGE_CY, GAUGE_R, -90, lowEnd));
  document.getElementById('bandLow').setAttribute('stroke', '#3FA66D');
  document.getElementById('bandMod').setAttribute('d', describeArc(GAUGE_CX, GAUGE_CY, GAUGE_R, lowEnd, modEnd));
  document.getElementById('bandMod').setAttribute('stroke', '#E2A63B');
  document.getElementById('bandHigh').setAttribute('d', describeArc(GAUGE_CX, GAUGE_CY, GAUGE_R, modEnd, 90));
  document.getElementById('bandHigh').setAttribute('stroke', '#D3563B');

  const ticks = document.getElementById('ticks');
  ticks.innerHTML = '';
  [0, EUI_LOW, EUI_MOD, EUI_MAX].forEach(v => {
    const a = angleForEui(v);
    const p1 = polarToCartesian(GAUGE_CX, GAUGE_CY, GAUGE_R + 16, a);
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', p1.x);
    text.setAttribute('y', p1.y);
    text.setAttribute('font-size', '9');
    text.setAttribute('font-family', 'IBM Plex Mono, monospace');
    text.setAttribute('fill', '#6b7876');
    text.setAttribute('text-anchor', 'middle');
    text.textContent = v.toFixed(1);
    ticks.appendChild(text);
  });
}
initGaugeStatic();

function setGaugeValue(eui) {
  const angle = angleForEui(eui);
  document.getElementById('needleGroup').setAttribute('transform', `rotate(${angle} ${GAUGE_CX} ${GAUGE_CY})`);
}

// -------------------- Chart --------------------
let savingsChart = null;
function renderSavingsChart(breakdown) {
  const ctx = document.getElementById('savingsChart');
  const labels = breakdown.map(b => b.category);
  const current = breakdown.map(b => b.monthlyKwh);
  const optimized = breakdown.map(b => {
    const tip = bestTipForCategory(b.category);
    const pct = tip ? tip.savings : 0;
    return +(b.monthlyKwh * (1 - pct / 100)).toFixed(1);
  });
  if (savingsChart) savingsChart.destroy();
  savingsChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        { label: 'Current kWh/mo', data: current, backgroundColor: '#0F2B2E' },
        { label: 'Optimized kWh/mo', data: optimized, backgroundColor: '#3FA66D' },
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
      scales: { y: { beginAtZero: true } }
    }
  });
}

// -------------------- Advisor cards --------------------
function bestTipForCategory(category) {
  const tips = SAVING_TIPS.filter(t => t.category === category).sort((a, b) => a.priority - b.priority);
  return tips[0] || null;
}

function renderAdvisorCards(breakdown) {
  const totalDaily = breakdown.reduce((s, b) => s + b.dailyKwh, 0) || 1;
  let significant = breakdown.filter(b => (b.dailyKwh / totalDaily) * 100 >= 15);
  if (significant.length === 0) significant = breakdown.slice(0, 3);

  const container = document.getElementById('advisorCards');
  container.innerHTML = '';
  significant.forEach(b => {
    const tip = bestTipForCategory(b.category);
    if (!tip) return;
    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4';
    col.innerHTML = `
      <div class="advisor-card">
        <span class="category-pill">${b.category}</span>
        <span class="savings-tag float-end">-${tip.savings}%</span>
        <h5>${tip.title}</h5>
        <p>${tip.description}</p>
      </div>`;
    container.appendChild(col);
  });
  if (!container.innerHTML) {
    container.innerHTML = '<p class="text-muted">No significant load categories checked yet — select some appliances above.</p>';
  }
}

// -------------------- Calculation --------------------
function collectAppliances() {
  return [...document.querySelectorAll('.appliance-row')].map(row => ({
    checked: row.querySelector('.chk-include').checked,
    name: row.querySelector('.appliance-name-input').value,
    category: row.querySelector('.category-select').value,
    wattage: parseFloat(row.querySelector('.fld-wattage').value) || 0,
    quantity: parseFloat(row.querySelector('.fld-quantity').value) || 0,
    hours: parseFloat(row.querySelector('.fld-hours').value) || 0,
  }));
}

function ratingForEui(eui) {
  if (eui <= EUI_LOW) return 'low';
  if (eui <= EUI_MOD) return 'moderate';
  return 'high';
}

let lastResult = null;

document.getElementById('auditForm').addEventListener('submit', (e) => {
  e.preventDefault();

  const sqft = parseFloat(document.getElementById('buildingSqft').value) || 1;
  const rate = parseFloat(document.getElementById('ratePerKwh').value) || 0;
  const appliances = collectAppliances().filter(a => a.checked);

  const categoryTotals = {};
  let totalDaily = 0;
  appliances.forEach(a => {
    const dailyKwh = (a.wattage / 1000) * a.quantity * a.hours;
    totalDaily += dailyKwh;
    categoryTotals[a.category] = (categoryTotals[a.category] || 0) + dailyKwh;
  });

  const breakdown = Object.entries(categoryTotals)
    .map(([category, dailyKwh]) => ({ category, dailyKwh, monthlyKwh: +(dailyKwh * 30).toFixed(1) }))
    .sort((a, b) => b.dailyKwh - a.dailyKwh);

  const totalMonthly = totalDaily * 30;
  const cost = totalMonthly * rate;
  const eui = totalMonthly / sqft;
  const rating = ratingForEui(eui);

  lastResult = { totalDaily, totalMonthly, cost, eui, rating, breakdown };

  document.getElementById('statDailyKwh').textContent = totalDaily.toFixed(1);
  document.getElementById('statMonthlyKwh').textContent = Math.round(totalMonthly).toLocaleString();
  document.getElementById('statCost').textContent = '$' + cost.toFixed(0);
  document.getElementById('gaugeEuiReadout').innerHTML = eui.toFixed(2) + ' <span class="unit">kWh/ft²/mo</span>';
  setGaugeValue(eui);

  const pill = document.getElementById('ratingPill');
  pill.className = 'rating-pill rating-' + rating;
  pill.textContent = rating === 'low' ? 'Low usage' : rating === 'moderate' ? 'Moderate usage' : 'High usage';

  renderSavingsChart(breakdown);
  renderAdvisorCards(breakdown);

  document.getElementById('results').classList.remove('results-hidden');
  document.getElementById('results').scrollIntoView({ behavior: 'smooth' });
});

// -------------------- Email report (calls Laravel API) --------------------
document.getElementById('emailReportBtn').addEventListener('click', async () => {
  const email = document.getElementById('youremail').value;
  if (!email) { alert('Add an email above first.'); return; }
  if (!lastResult) { alert('Run the audit first.'); return; }

  const payload = {
    building: {
      name: document.getElementById('buildingName').value,
      address: document.getElementById('buildingAddress').value,
      building_type: document.getElementById('buildingType').value,
      square_footage: parseFloat(document.getElementById('buildingSqft').value) || 0,
      floors: parseInt(document.getElementById('buildingFloors').value) || 1,
      occupants: parseInt(document.getElementById('buildingOccupants').value) || 0,
    },
    appliances: collectAppliances(),
    rate_per_kwh: parseFloat(document.getElementById('ratePerKwh').value) || 0.14,
    email,
  };

  const btn = document.getElementById('emailReportBtn');
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending…';

  try {
    const res = await fetch(`${API_BASE_URL}/audits`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload),
    });
    if (!res.ok) throw new Error('API responded with ' + res.status);
    alert('Report sent — check ' + email + '.');
  } catch (err) {
    alert('Could not reach the audit API at ' + API_BASE_URL + '. Deploy the Laravel backend and set window.EP_API_BASE_URL to its URL.\n\n(' + err.message + ')');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
  }
});
</script>
</body>
</html>
