<?php if ($currentFile && $currentExt === 'pdf'): ?>
  <h1><?= htmlspecialchars($docLabel) ?></h1>
  <div class="pdf-viewer">
    <canvas id="pdf-canvas"></canvas>
    <div class="pdf-page-controls">
      <button id="prev-page" disabled>&#8592; Prev</button>
      <span id="page-info">Page 1</span>
      <button id="next-page">Next &#8594;</button>
    </div>
  </div>
  <div class="download-bar">
    <a class="btn-download" href="<?= htmlspecialchars($currentFile) ?>" download>⬇ Download PDF</a>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <script>
    pdfjsLib.GlobalWorkerOptions.workerSrc =
      'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const url     = <?= json_encode($currentFile) ?>;
    const canvas  = document.getElementById('pdf-canvas');
    const ctx     = canvas.getContext('2d');
    const info    = document.getElementById('page-info');
    const btnPrev = document.getElementById('prev-page');
    const btnNext = document.getElementById('next-page');

    let pdfDoc    = null;
    let pageNum   = 1;
    let rendering = false;

function renderPage(num) {
  rendering = true;
  pdfDoc.getPage(num).then(page => {
    const displayWidth = canvas.parentElement.clientWidth;
    const unscaledVp   = page.getViewport({ scale: 1 });
    const scale        = displayWidth / unscaledVp.width;
    const viewport     = page.getViewport({ scale });

    canvas.width  = viewport.width;
    canvas.height = viewport.height;

    // ← Add these two lines: force CSS height to match the aspect ratio
    const cssHeight = Math.round(viewport.height * (displayWidth / viewport.width));
    canvas.style.height = cssHeight + 'px';

    page.render({ canvasContext: ctx, viewport }).promise.then(() => {
      rendering = false;
      info.textContent = `Page ${num} of ${pdfDoc.numPages}`;
      btnPrev.disabled = num <= 1;
      btnNext.disabled = num >= pdfDoc.numPages;
    });
  });
}

    pdfjsLib.getDocument(url).promise.then(doc => {
      pdfDoc = doc;
      btnNext.disabled = doc.numPages <= 1;
      renderPage(pageNum);
    });

    btnPrev.addEventListener('click', () => {
      if (pageNum > 1 && !rendering) renderPage(--pageNum);
    });
    btnNext.addEventListener('click', () => {
      if (pageNum < pdfDoc.numPages && !rendering) renderPage(++pageNum);
    });

    // Re-render on resize so it stays fluid
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => renderPage(pageNum), 200);
    });
  </script>
<?php endif; ?>// JavaScript Document