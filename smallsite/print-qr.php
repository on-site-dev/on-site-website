<?php
// ─── OSS Studio Instructions – QR Print Sheet ──────────────────────────────
// Access via: yoursite.com/On-Site/print-qr.php?key=oss-admin-2024&doc=your-slug

require_once 'config.php';

// Auth
$key = $_GET['key'] ?? '';
if ($key !== ADMIN_KEY) {
    http_response_code(403);
    die('403 – Not Authorized');
}

// Doc slug
$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['doc'] ?? ''));
if (!$slug) die('No document specified. Add ?doc=your-slug to the URL.');

$uploadDir = rtrim(UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR;
$exists = file_exists($uploadDir . $slug . '.pdf') || file_exists($uploadDir . $slug . '.png');
if (!$exists) die('Document not found: ' . htmlspecialchars($slug));

$label      = ucwords(str_replace('-', ' ', $slug));
$viewerUrl  = PUBLIC_URL . '?doc=' . urlencode($slug);
$qrUrl      = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($viewerUrl);
$perPage    = (int)($_GET['count'] ?? 16);
if ($perPage < 1 || $perPage > 30) $perPage = 16;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Print QR – <?= htmlspecialchars($label) ?></title>
<style>
  /* ── Screen chrome ── */
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #f0f0f0;
    margin: 0;
    padding: 1rem;
  }

  .toolbar {
    max-width: 750px;
    margin: 0 auto 1rem;
    background: #1a202c;
    color: #e2e8f0;
    border-radius: 8px;
    padding: .85rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
  }
  .toolbar strong { color: #fff; font-size: .95rem; }
  .toolbar span   { color: #a0aec0; font-size: .82rem; }
  .toolbar-right  { margin-left: auto; display: flex; gap: .5rem; align-items: center; }

  .btn-print {
    background: #2b6cb0;
    color: #fff;
    border: none;
    padding: .5rem 1.1rem;
    border-radius: 6px;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
  }
  .btn-print:hover { background: #2c5282; }

  .count-label { font-size: .82rem; color: #a0aec0; }
  .count-select {
    background: #2d3748;
    color: #e2e8f0;
    border: 1px solid #4a5568;
    border-radius: 5px;
    padding: .3rem .6rem;
    font-size: .85rem;
    cursor: pointer;
  }

  /* ── Print sheet ── */
  .sheet {
    width: 8.5in;
    min-height: 11in;
    margin: 0 auto;
    background: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,.15);
    padding: .35in .35in .25in;
    box-sizing: border-box;
  }

  .sheet-header {
    text-align: center;
    margin-bottom: .2in;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: .15in;
  }
  .sheet-header h2 { font-size: 13pt; color: #1a202c; margin: 0 0 2pt; }
  .sheet-header p  { font-size: 8pt; color: #718096; margin: 0; }

  .qr-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .18in;
  }

  .qr-cell {
    border: 1px dashed #cbd5e0;
    border-radius: 6px;
    padding: .12in .1in .1in;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .qr-cell img {
    width: 1.55in;
    height: 1.55in;
    display: block;
  }

  .qr-cell .qr-label {
    font-size: 7.5pt;
    font-weight: 700;
    color: #2d3748;
    margin-top: .06in;
    line-height: 1.3;
  }

  .qr-cell .qr-brand {
    font-size: 6.5pt;
    color: #a0aec0;
    margin-top: 2pt;
  }

  /* ── Print media ── */
  @media print {
    body    { background: none; padding: 0; }
    .toolbar { display: none; }
    .sheet  { box-shadow: none; margin: 0; width: 100%; }
    @page   { size: 8.5in 11in; margin: 0; }
  }
</style>
</head>
<body>

<!-- Screen toolbar -->
<div class="toolbar">
  <div>
    <strong><?= htmlspecialchars($label) ?></strong><br>
    <span><?= htmlspecialchars($viewerUrl) ?></span>
  </div>
  <div class="toolbar-right">
    <span class="count-label">QR codes per sheet:</span>
    <select class="count-select" onchange="updateCount(this.value)">
      <?php foreach ([4,6,8,9,12,16] as $n): ?>
        <option value="<?= $n ?>" <?= $n === $perPage ? 'selected' : '' ?>><?= $n ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn-print" onclick="window.print()">🖨 Print</button>
  </div>
</div>

<!-- Print sheet -->
<div class="sheet">
  <div class="sheet-header">
    <h2><?= htmlspecialchars($label) ?> — On-Site Studios</h2>
    <p>Scan QR code for setup instructions &nbsp;·&nbsp; <?= htmlspecialchars($viewerUrl) ?></p>
  </div>
  <div class="qr-grid">
    <?php for ($i = 0; $i < $perPage; $i++): ?>
      <div class="qr-cell">
        <img src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code">
        <div class="qr-label"><?= htmlspecialchars($label) ?></div>
        <div class="qr-brand">On-Site Studios</div>
      </div>
    <?php endfor; ?>
  </div>
</div>

<script>
function updateCount(n) {
  const url = new URL(window.location.href);
  url.searchParams.set('count', n);
  window.location.href = url.toString();
}
</script>
</body>
</html>