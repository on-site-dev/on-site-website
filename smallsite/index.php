<?php
// ─── OSS Studio Instructions – Public Viewer ───────────────────────────────
require_once 'config.php';

$uploadDir = rtrim(UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR;

// Sanitize slug
// $slug = $_GET['doc'] ?? '';
// if ($slug == '') {
//   $slug = 'OSS-SOP-Generic';
// }

$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['doc'] ?? ''));
error_log('slug=' . $slug);
// $slug = '';

$currentFile = null;
$currentExt  = null;
$docLabel    = '';

if ($slug) {
    foreach (['png', 'pdf'] as $ext) {
        $path = $uploadDir . $slug . '.' . $ext;
        if (file_exists($path)) {
            $currentFile = $path;
            $currentExt  = $ext;
            $docLabel    = ucwords(str_replace('-', ' ', $slug));
            break;
        }
    }
}

// Build list of all PDFs in upload dir for the thumbnail panel
$allDocs = [];
foreach (glob($uploadDir . '*.pdf') as $pdfPath) {
    $fn = basename($pdfPath, '.pdf');
    $allDocs[] = [
        'slug'  => $fn,
        'label' => ucwords(str_replace('-', ' ', $fn)),
        'path'  => $pdfPath,
    ];
}
usort($allDocs, fn($a, $b) => strcmp($a['label'], $b['label']));

// Auto-redirect to first doc if none specified
if ((!$slug || !$currentFile) && !empty($allDocs)) {
    header('Location: index.php?doc=' . urlencode($allDocs[0]['slug']));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $docLabel ? htmlspecialchars($docLabel) . ' – ' : '' ?>On-Site Studios</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --oss-purple:      #3d1a6e;
    --oss-purple-mid:  #5a2d9c;
    --oss-purple-light:#7b4bbf;
    --oss-text-light:  #e8d9ff;
    --oss-accent:      #c9a8ff;
    --sidebar-w:       210px;
  }

  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #f4f0fa;
    color: #1a0a2e;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* ── Header ── */
  header {
    background: var(--oss-purple);
    padding: .85rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-shrink: 0;
  }
  .header-logo { display: flex; align-items: center; flex-shrink: 0; }
  .header-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-left: auto;
    flex-shrink: 0;
  }
  .header-nav a.btn-contact {
    background: var(--oss-purple-mid);
    color: #fff;
    text-decoration: none;
    padding: .35rem .9rem;
    border-radius: 5px;
    font-weight: 600;
    font-size: clamp(.75rem, 2.5vw, .9rem);
    white-space: nowrap;
    transition: background .2s;
  }
  .header-nav a.btn-contact:hover { background: var(--oss-purple-light); }

  /* ── Page body layout ── */
  .page-body {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  /* ── Two-column layout (desktop/tablet) ── */
  .content-layout {
    flex: 1;
    display: flex;
    max-width: 1100px;
    width: 100%;
    margin: 0 auto;
    padding: 1.25rem 1rem;
    align-items: flex-start;
    gap: 1rem;
  }

  /* ── Thumbnail sidebar ── */
  .thumb-panel {
    width: var(--sidebar-w);
    flex-shrink: 0;
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(61,26,110,.10);
    overflow: hidden;
    position: sticky;
    top: 1rem;
    max-height: calc(100vh - 5rem);
    display: flex;
    flex-direction: column;
  }

  .thumb-panel-header {
    background: var(--oss-purple);
    color: #fff;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .6rem .75rem;
    flex-shrink: 0;
  }

  .thumb-list {
    overflow-y: auto;
    flex: 1;
    padding: .5rem;
    display: flex;
    flex-direction: column;
    gap: .5rem;
  }

  .thumb-item {
    cursor: pointer;
    border-radius: 6px;
    border: 2px solid transparent;
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
    background: #f4f0fa;
  }
  .thumb-item:hover { border-color: var(--oss-purple-light); }
  .thumb-item.active {
    border-color: var(--oss-purple-mid);
    box-shadow: 0 0 0 2px rgba(90,45,156,.25);
  }

  .thumb-canvas-wrap {
    width: 100%;
    background: #e8e0f5;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  .thumb-canvas {
    display: block;
    width: 100%;
  }

  .thumb-loading {
    position: absolute;
    font-size: .7rem;
    color: var(--oss-purple-light);
  }

  .thumb-label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--oss-purple);
    padding: .3rem .4rem;
    line-height: 1.3;
    word-break: break-word;
  }

  /* ── Viewer column ── */
  .viewer-col {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
  }

  /* ── Title + download bar (above PDF) ── */
  .doc-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .75rem;
  }

  .doc-topbar h1 {
    font-size: clamp(1rem, 3.5vw, 1.35rem);
    font-weight: 700;
    color: var(--oss-purple);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .btn-download {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: var(--oss-purple-mid);
    color: #fff;
    padding: .5rem 1.1rem;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    font-size: .88rem;
    white-space: nowrap;
    flex-shrink: 0;
    transition: background .2s;
  }
  .btn-download:hover { background: var(--oss-purple); }

  /* ── PDF viewer ── */
  .pdf-viewer {
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(61,26,110,.12);
  }

  #pdf-canvas {
    display: block;
    width: 100%;
  }

  .pdf-page-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: .6rem 1rem;
    background: #f4f0fa;
    border-top: 1px solid #ddd0f5;
    font-size: .85rem;
    color: var(--oss-purple);
  }
  .pdf-page-controls button {
    background: var(--oss-purple-mid);
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: .3rem .8rem;
    cursor: pointer;
    font-size: .85rem;
  }
  .pdf-page-controls button:disabled { background: #bbb; cursor: not-allowed; }

  /* ── PNG viewer ── */
  .img-viewer {
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(61,26,110,.12);
  }
  .img-viewer img { width: 100%; height: auto; display: block; }

  /* ── Not found ── */
  .not-found {
    text-align: center;
    padding: 4rem 2rem;
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 10px;
  }
  .not-found .icon { font-size: 3rem; margin-bottom: 1rem; }
  .not-found p { color: #718096; }

  /* ── Mobile: floating "All Docs" button ── */
  .fab-all-docs {
    display: none;
    position: fixed;
    bottom: 1.25rem;
    right: 1.25rem;
    z-index: 200;
    background: var(--oss-purple-mid);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: .65rem 1.2rem;
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(61,26,110,.35);
    align-items: center;
    gap: .4rem;
    transition: background .2s;
  }
  .fab-all-docs:hover { background: var(--oss-purple); }

  /* ── Thumbnail modal (mobile) ── */
  .thumb-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(29,10,60,.6);
    z-index: 300;
    align-items: flex-end;
    justify-content: center;
  }
  .thumb-modal-overlay.open { display: flex; }

  .thumb-modal {
    background: #fff;
    border-radius: 16px 16px 0 0;
    width: 100%;
    max-height: 75vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 -4px 32px rgba(61,26,110,.25);
  }

  .thumb-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .85rem 1rem;
    background: var(--oss-purple);
    color: #fff;
    flex-shrink: 0;
  }
  .thumb-modal-header span {
    font-weight: 700;
    font-size: .95rem;
    letter-spacing: .04em;
    text-transform: uppercase;
  }
  .thumb-modal-close {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.4rem;
    cursor: pointer;
    line-height: 1;
    padding: 0 .25rem;
  }

  .thumb-modal-grid {
    overflow-y: auto;
    flex: 1;
    padding: .75rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: .6rem;
  }

  /* ── Footer ── */
  footer {
    background: var(--oss-purple);
    color: var(--oss-text-light);
    text-align: center;
    padding: 1.1rem 1rem;
    font-size: .8rem;
    line-height: 1.7;
    margin-top: auto;
    flex-shrink: 0;
  }
  footer strong { color: #fff; font-size: .85rem; }
  footer a { color: var(--oss-accent); text-decoration: none; }
  footer a:hover { text-decoration: underline; }

  /* ── Responsive: hide sidebar, show FAB on mobile ── */
  @media (max-width: 640px) {
    .thumb-panel { display: none; }
    .fab-all-docs { display: flex; }
    .content-layout { padding: .85rem .75rem; }
  }
</style>
</head>
<body>

<header>
  <div class="header-logo">
    <a href="index.php">
      <img src="onsite_logo.png" alt="On-Site Studios" style="height: 48px; width: auto;">
    </a>
  </div>
  <nav class="header-nav">
    <a href="contact.php" class="btn-contact">Contact</a>
  </nav>
</header>

<div class="page-body">
  <div class="content-layout">

    <?php if (count($allDocs) > 0): ?>
    <!-- ── Thumbnail sidebar (desktop/tablet) ── -->
    <aside class="thumb-panel" id="thumb-panel">
      <div class="thumb-panel-header">All Documents</div>
      <div class="thumb-list" id="thumb-list-desktop">
        <?php foreach ($allDocs as $doc): ?>
          <div class="thumb-item <?= $doc['slug'] === $slug ? 'active' : '' ?>"
               data-slug="<?= htmlspecialchars($doc['slug']) ?>"
               data-path="<?= htmlspecialchars($doc['path']) ?>"
               data-label="<?= htmlspecialchars($doc['label']) ?>"
               onclick="loadDoc(this.dataset.path, this.dataset.label, this.dataset.slug)">
            <div class="thumb-canvas-wrap">
              <span class="thumb-loading">Loading…</span>
              <canvas class="thumb-canvas" data-path="<?= htmlspecialchars($doc['path']) ?>"></canvas>
            </div>
            <div class="thumb-label"><?= htmlspecialchars($doc['label']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </aside>
    <?php endif; ?>

    <!-- ── Viewer column ── -->
    <div class="viewer-col">

      <?php if ($currentFile && $currentExt === 'pdf'): ?>
        <div class="doc-topbar">
          <h1 id="doc-title"><?= htmlspecialchars($docLabel) ?></h1>
          <a class="btn-download" id="btn-download"
             href="<?= htmlspecialchars($currentFile) ?>" download>
            ⬇ Download PDF
          </a>
        </div>
        <div class="pdf-viewer">
          <canvas id="pdf-canvas"></canvas>
          <div class="pdf-page-controls">
            <button id="prev-page" disabled>&#8592; Prev</button>
            <span id="page-info">Page 1</span>
            <button id="next-page">Next &#8594;</button>
          </div>
        </div>

      <?php elseif ($currentFile && $currentExt === 'png'): ?>
        <div class="doc-topbar">
          <h1 id="doc-title"><?= htmlspecialchars($docLabel) ?></h1>
          <a class="btn-download" id="btn-download"
             href="<?= htmlspecialchars($currentFile) ?>" download>
            ⬇ Download
          </a>
        </div>
        <div class="img-viewer">
          <img src="<?= htmlspecialchars($currentFile) ?>" alt="<?= htmlspecialchars($docLabel) ?>">
        </div>

      <?php else: ?>
        <div class="not-found">
          <div class="icon">📋</div>
          <p><?= $slug ? 'Document not found.' : 'No document specified.' ?></p>
        </div>
      <?php endif; ?>

    </div><!-- /.viewer-col -->
  </div><!-- /.content-layout -->
</div><!-- /.page-body -->

<!-- ── Mobile FAB ── -->
<?php if (count($allDocs) > 0): ?>
<button class="fab-all-docs" onclick="openModal()">☰ All Docs</button>

<!-- ── Thumbnail modal (mobile) ── -->
<div class="thumb-modal-overlay" id="thumb-modal-overlay" onclick="overlayClick(event)">
  <div class="thumb-modal">
    <div class="thumb-modal-header">
      <span>All Documents</span>
      <button class="thumb-modal-close" onclick="closeModal()">✕</button>
    </div>
    <div class="thumb-modal-grid" id="thumb-modal-grid">
      <?php foreach ($allDocs as $doc): ?>
        <div class="thumb-item <?= $doc['slug'] === $slug ? 'active' : '' ?>"
             data-slug="<?= htmlspecialchars($doc['slug']) ?>"
             data-path="<?= htmlspecialchars($doc['path']) ?>"
             data-label="<?= htmlspecialchars($doc['label']) ?>"
             onclick="loadDoc(this.dataset.path, this.dataset.label, this.dataset.slug); closeModal();">
          <div class="thumb-canvas-wrap">
            <span class="thumb-loading">Loading…</span>
            <canvas class="thumb-canvas" data-path="<?= htmlspecialchars($doc['path']) ?>"></canvas>
          </div>
          <div class="thumb-label"><?= htmlspecialchars($doc['label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

  <!-- <strong>On-Site Studios</strong><br> -->
  <!-- <a href="https://on-sitestudios.com">on-sitestudios.com</a> -->
  <!-- &nbsp;&nbsp; -->

<footer>
  1400 Lombardi Ave, Suite 50, Green Bay, WI 54304<br>
  © 2012 - <?= date('Y') ?> On-Site Studios. All rights reserved.
</footer>

<!-- ── PDF.js ── -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
  pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

  const STD_FONTS = 'standard_fonts/';

  // ── Main viewer state ──────────────────────────────────────────────────────
  const mainCanvas = document.getElementById('pdf-canvas');
  const mainCtx    = mainCanvas ? mainCanvas.getContext('2d') : null;
  const pageInfo   = document.getElementById('page-info');
  const btnPrev    = document.getElementById('prev-page');
  const btnNext    = document.getElementById('next-page');
  const btnDl      = document.getElementById('btn-download');
  const docTitle   = document.getElementById('doc-title');

  let pdfDoc      = null;
  let pageNum     = 1;
  let rendering   = false;
  let currentPath = <?= json_encode($currentFile ?? '') ?>;

  function renderPage(num) {
    if (!pdfDoc || !mainCanvas) return;
    rendering = true;
    pdfDoc.getPage(num).then(page => {
      const dpr          = window.devicePixelRatio || 1;
      const displayWidth = mainCanvas.parentElement.clientWidth;
      const unscaledVp   = page.getViewport({ scale: 1 });
      const scale        = (displayWidth / unscaledVp.width) * dpr;
      const viewport     = page.getViewport({ scale });

      // Canvas buffer is dpr× larger for retina sharpness
      mainCanvas.width  = viewport.width;
      mainCanvas.height = viewport.height;

      // CSS size stays at display width so layout is unchanged
      mainCanvas.style.width  = displayWidth + 'px';
      mainCanvas.style.height = (viewport.height / dpr) + 'px';

      page.render({ canvasContext: mainCtx, viewport }).promise.then(() => {
        rendering = false;
        if (pageInfo) pageInfo.textContent = `Page ${num} of ${pdfDoc.numPages}`;
        if (btnPrev)  btnPrev.disabled = num <= 1;
        if (btnNext)  btnNext.disabled = num >= pdfDoc.numPages;
      });
    });
  }

  function initMainPdf(path) {
    if (!path || !mainCanvas) return;
    pdfjsLib.getDocument({ url: path, standardFontDataUrl: STD_FONTS })
      .promise.then(doc => {
        pdfDoc  = doc;
        pageNum = 1;
        if (btnNext) btnNext.disabled = doc.numPages <= 1;
        if (btnPrev) btnPrev.disabled = true;
        renderPage(1);
      });
  }

  if (btnPrev) btnPrev.addEventListener('click', () => {
    if (pageNum > 1 && !rendering) renderPage(--pageNum);
  });
  if (btnNext) btnNext.addEventListener('click', () => {
    if (pdfDoc && pageNum < pdfDoc.numPages && !rendering) renderPage(++pageNum);
  });

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => { if (pdfDoc) renderPage(pageNum); }, 200);
  });

  // Load initial PDF
  initMainPdf(currentPath);

  // ── Switch document ────────────────────────────────────────────────────────
  function loadDoc(path, label, slug) {
    currentPath = path;

    if (docTitle) docTitle.textContent = label;

    if (btnDl) {
      btnDl.href     = path;
      btnDl.download = label + '.pdf';
    }

    // Highlight active thumb in both sidebar and modal
    document.querySelectorAll('.thumb-item').forEach(el => {
      el.classList.toggle('active', el.dataset.slug === slug);
    });

    pdfjsLib.getDocument({ url: path, standardFontDataUrl: STD_FONTS })
      .promise.then(doc => {
        pdfDoc  = doc;
        pageNum = 1;
        if (btnPrev) btnPrev.disabled = true;
        if (btnNext) btnNext.disabled = doc.numPages <= 1;
        renderPage(1);
      });
  }

  // ── Thumbnail rendering queue ──────────────────────────────────────────────
  const thumbCanvases = [...document.querySelectorAll('.thumb-canvas')];

  // Deduplicate by path — sidebar and modal share the same render
  const seenPaths  = new Set();
  const uniqueThumbs = thumbCanvases.filter(c => {
    if (seenPaths.has(c.dataset.path)) return false;
    seenPaths.add(c.dataset.path);
    return true;
  });

  const thumbCache = {};
  let thumbIdx = 0;

  async function renderThumbs() {
    if (thumbIdx >= uniqueThumbs.length) return;
    const canvas = uniqueThumbs[thumbIdx++];
    const path   = canvas.dataset.path;

    try {
      let imageBitmap = thumbCache[path];

      if (!imageBitmap) {
        const pdf    = await pdfjsLib.getDocument({ url: path, standardFontDataUrl: STD_FONTS }).promise;
        const page   = await pdf.getPage(1);
        const vp0    = page.getViewport({ scale: 1 });
        const thumbW = canvas.parentElement.clientWidth || 160;
        const scale  = thumbW / vp0.width;
        const vp     = page.getViewport({ scale });

        const offscreen  = document.createElement('canvas');
        offscreen.width  = vp.width;
        offscreen.height = vp.height;
        await page.render({ canvasContext: offscreen.getContext('2d'), viewport: vp }).promise;
        imageBitmap = await createImageBitmap(offscreen);
        thumbCache[path] = imageBitmap;
      }

      // Paint to this canvas
      canvas.width  = imageBitmap.width;
      canvas.height = imageBitmap.height;
      canvas.style.width  = '100%';
      canvas.style.height = 'auto';
      canvas.getContext('2d').drawImage(imageBitmap, 0, 0);

      // Paint to sibling canvas in the other panel (modal or sidebar)
      thumbCanvases
        .filter(c => c !== canvas && c.dataset.path === path)
        .forEach(s => {
          s.width  = imageBitmap.width;
          s.height = imageBitmap.height;
          s.style.width  = '100%';
          s.style.height = 'auto';
          s.getContext('2d').drawImage(imageBitmap, 0, 0);
          const sp = s.closest('.thumb-canvas-wrap')?.querySelector('.thumb-loading');
          if (sp) sp.style.display = 'none';
        });

      // Hide loading spinner
      const sp = canvas.closest('.thumb-canvas-wrap')?.querySelector('.thumb-loading');
      if (sp) sp.style.display = 'none';

    } catch(e) {
      console.warn('Thumb render failed:', path, e);
    }

    setTimeout(renderThumbs, 30);
  }

  renderThumbs();

  // ── Mobile modal ───────────────────────────────────────────────────────────
  const modalOverlay = document.getElementById('thumb-modal-overlay');

  function openModal()  { if (modalOverlay) modalOverlay.classList.add('open'); }
  function closeModal() { if (modalOverlay) modalOverlay.classList.remove('open'); }
  function overlayClick(e) { if (e.target === modalOverlay) closeModal(); }
</script>

</body>
</html>