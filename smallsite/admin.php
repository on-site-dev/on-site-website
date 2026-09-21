<?php
// ─── OSS Studio Instructions – Admin Upload Page ───────────────────────────
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';

// ── Login / logout handlers ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_key'])) {
    if ($_POST['admin_key'] === ADMIN_KEY) {
        $_SESSION['oss_admin'] = true;
    } else {
        $loginError = 'Incorrect access key. Please try again.';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// ── Show login form if not authenticated ─────────────────────────────────
if (empty($_SESSION['oss_admin'])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login – <?= htmlspecialchars(SITE_TITLE) ?></title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --oss-purple:     #3d1a6e;
    --oss-purple-mid: #5a2d9c;
    --oss-accent:     #c9a8ff;
    --oss-text-light: #e8d9ff;
  }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #f4f0fa;
    color: #1a0a2e;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  header {
    background: var(--oss-purple);
    padding: .85rem 1.25rem;
    display: flex;
    align-items: center;
  }
  header img { height: 48px; width: auto; }

  .login-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
  }
  .login-card {
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 12px;
    box-shadow: 0 2px 24px rgba(61,26,110,.12);
    padding: 2rem 2rem 2.25rem;
    width: 100%;
    max-width: 380px;
    text-align: center;
  }
  .login-card h1 {
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--oss-purple);
    margin-bottom: .35rem;
  }
  .login-card p {
    font-size: .88rem;
    color: #718096;
    margin-bottom: 1.5rem;
  }
  .login-card label {
    display: block;
    text-align: left;
    font-size: .82rem;
    font-weight: 600;
    color: var(--oss-purple-mid);
    margin-bottom: .35rem;
  }
  .login-card input[type="password"] {
    width: 100%;
    padding: .6rem .85rem;
    border: 1.5px solid #c9a8ff;
    border-radius: 6px;
    font-size: .95rem;
    background: #faf7ff;
    color: #1a0a2e;
    outline: none;
    margin-bottom: 1rem;
    transition: border-color .2s;
  }
  .login-card input[type="password"]:focus { border-color: var(--oss-purple-mid); }
  .btn-login {
    width: 100%;
    background: var(--oss-purple-mid);
    color: #fff;
    border: none;
    padding: .65rem;
    border-radius: 6px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s;
  }
  .btn-login:hover { background: var(--oss-purple); }
  .error {
    background: #fff5f5;
    border: 1px solid #fc8181;
    color: #9b2c2c;
    border-radius: 6px;
    padding: .65rem .85rem;
    font-size: .85rem;
    margin-bottom: 1rem;
  }
  footer {
    background: var(--oss-purple);
    color: var(--oss-text-light);
    text-align: center;
    padding: 1rem;
    font-size: .8rem;
    line-height: 1.7;
  }
  footer strong { color: #fff; }
  footer a { color: var(--oss-accent); text-decoration: none; }
</style>
</head>
<body>

<header>
  <img src="onsite_logo.png" alt="On-Site Studios">
</header>

<div class="login-wrap">
  <div class="login-card">
    <h1>Admin Access</h1>
    <p>Enter your access key to continue.</p>

    <?php if (!empty($loginError)): ?>
      <div class="error"><?= htmlspecialchars($loginError) ?></div>
    <?php endif; ?>

    <form method="POST" action="admin.php">
      <label for="admin_key">Access Key</label>
      <input type="password" id="admin_key" name="admin_key"
             placeholder="Enter access key" autofocus>
      <button type="submit" class="btn-login">Sign In</button>
    </form>
  </div>
</div>

<footer>
  <strong>On-Site Studios</strong><br>
  1400 Lombardi Ave, Suite 50, Green Bay, WI 54304<br>
  <a href="https://on-sitestudios.com">on-sitestudios.com</a>
</footer>

</body>
</html>
<?php
// Stop here — don't render the admin panel
exit;
endif;

// ── Authenticated — rest of admin panel below ─────────────────────────────

$uploadDir   = rtrim(UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR;
$message     = '';
$messageType = '';

// ── Delete handler ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $slug    = preg_replace('/[^a-z0-9\-]/', '', $_POST['delete_file']);
    $deleted = false;
    foreach (['png', 'pdf'] as $ext) {
        $path = $uploadDir . $slug . '.' . $ext;
        if (file_exists($path)) { unlink($path); $deleted = true; }
    }
    $message     = $deleted ? 'File deleted.' : 'File not found.';
    $messageType = $deleted ? 'success' : 'error';
}

// ── Upload handler ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['instruction_file'])) {
    $file     = $_FILES['instruction_file'];
    $docLabel = trim($_POST['doc_name'] ?? '');

    if (empty($docLabel)) {
        $message     = 'Please enter a document name.';
        $messageType = 'error';

    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds the server upload limit.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds the form size limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded. Please try again.',
            UPLOAD_ERR_NO_FILE    => 'No file was selected.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server is missing a temporary folder — contact your host.',
            UPLOAD_ERR_CANT_WRITE => 'Server failed to write the file — check disk space/permissions.',
        ];
        $message     = $uploadErrors[$file['error']] ?? 'Upload error (code ' . $file['error'] . ').';
        $messageType = 'error';

    } else {
        $handle  = fopen($file['tmp_name'], 'rb');
        $magic   = $handle ? fread($handle, 8) : '';
        if ($handle) fclose($handle);

        $origExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $isPng   = (substr($magic, 0, 4) === "\x89PNG") && ($origExt === 'png');
        $isPdf   = (substr($magic, 0, 4) === '%PDF')    && ($origExt === 'pdf');
        if ($magic === '') { $isPng = ($origExt === 'png'); $isPdf = ($origExt === 'pdf'); }

        if (!$isPng && !$isPdf) {
            $message     = 'Only PNG and PDF files are accepted.';
            $messageType = 'error';
        } else {
            $slug = strtolower(trim($docLabel));
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');

            if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);

            if (!is_writable($uploadDir)) {
                $message     = 'The <code>' . htmlspecialchars($uploadDir) . '</code> folder is not writable. Set permissions to 755.';
                $messageType = 'error';
            } else {
                $fileExt  = $isPng ? 'png' : 'pdf';
                $destName = $slug . '.' . $fileExt;
                $destPath = $uploadDir . $destName;

                if (move_uploaded_file($file['tmp_name'], $destPath)) {
                    $message     = 'Uploaded successfully: <strong>' . htmlspecialchars($destName) . '</strong>';
                    $messageType = 'success';
                } else {
                    $message     = 'move_uploaded_file() failed. Destination: <code>' . htmlspecialchars($destPath) . '</code>';
                    $messageType = 'error';
                }
            }
        }
    }
}

// ── Load all uploaded files ───────────────────────────────────────────────

$uploadedFiles = [];
if (is_dir($uploadDir)) {
    foreach (glob($uploadDir . '*.{png,pdf}', GLOB_BRACE) ?: [] as $path) {
        $basename        = pathinfo($path, PATHINFO_FILENAME);
        $ext             = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $label           = ucwords(str_replace('-', ' ', $basename));
        $viewerUrl       = PUBLIC_URL . '?doc=' . urlencode($basename);
        $qrUrl           = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($viewerUrl);
        $uploadedFiles[] = compact('basename', 'ext', 'label', 'viewerUrl', 'qrUrl', 'path');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin – <?= htmlspecialchars(SITE_TITLE) ?></title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --oss-purple:      #3d1a6e;
    --oss-purple-mid:  #5a2d9c;
    --oss-purple-light:#7b4bbf;
    --oss-text-light:  #e8d9ff;
    --oss-accent:      #c9a8ff;
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
    flex-wrap: wrap;
  }
  header img.logo { height: 48px; width: auto; }
  .header-right {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: .75rem;
  }
  .badge {
    background: #e53e3e;
    color: #fff;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: .25rem .6rem;
    border-radius: 4px;
    white-space: nowrap;
  }
  .btn-logout {
    background: rgba(255,255,255,.15);
    color: #fff;
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 5px;
    padding: .3rem .8rem;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s;
  }
  .btn-logout:hover { background: rgba(255,255,255,.25); }

  /* ── Container ── */
  .container {
    max-width: 780px;
    margin: 0 auto;
    padding: 1.75rem 1rem;
    flex: 1;
    width: 100%;
  }

  /* ── Cards ── */
  .card {
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 12px rgba(61,26,110,.07);
  }

  h2 {
    font-size: .8rem;
    font-weight: 700;
    color: var(--oss-purple-mid);
    text-transform: uppercase;
    letter-spacing: .09em;
    margin-bottom: 1rem;
  }

  /* ── Messages ── */
  .message { padding: .85rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: .9rem; }
  .message.success { background: #edfaf3; border: 1px solid #68d391; color: #276749; }
  .message.error   { background: #fff5f5; border: 1px solid #fc8181; color: #9b2c2c; }

  /* ── Form ── */
  .field { margin-bottom: 1rem; }
  .field label { display: block; font-size: .82rem; color: var(--oss-purple-mid); margin-bottom: .35rem; font-weight: 600; }
  .field input[type="text"] {
    width: 100%;
    background: #faf7ff;
    border: 1px solid #c9a8ff;
    border-radius: 6px;
    color: #1a0a2e;
    padding: .55rem .8rem;
    font-size: .9rem;
  }
  .field input[type="text"]:focus { outline: none; border-color: var(--oss-purple-mid); }

  .upload-area {
    border: 2px dashed #c9a8ff;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    margin-bottom: 1rem;
    background: #faf7ff;
  }
  .upload-area:hover { border-color: var(--oss-purple-mid); background: #f0e8ff; }
  .upload-area input[type="file"] { display: none; }
  .upload-area p { color: #7b4bbf; font-size: .88rem; }
  .upload-area strong { color: var(--oss-purple); }
  #file-name { font-size: .85rem; color: var(--oss-purple-mid); margin-top: .5rem; min-height: 1.2em; font-weight: 600; }

  .btn {
    display: inline-block;
    background: var(--oss-purple-mid);
    color: #fff;
    border: none;
    padding: .65rem 1.4rem;
    border-radius: 6px;
    font-size: .95rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
    width: 100%;
  }
  .btn:hover { background: var(--oss-purple); }

  /* ── Doc grid ── */
  .doc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 1.25rem; }

  .doc-card {
    background: #faf7ff;
    border: 1px solid #ddd0f5;
    border-radius: 8px;
    padding: 1.25rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .qr-preview-wrap {
    background: #fff;
    border: 3px solid var(--oss-purple);
    border-radius: 6px;
    padding: 10px 10px 8px;
    margin-bottom: .75rem;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }
  .qr-preview-wrap img.qr { display: block; width: 140px; height: 140px; }
  .qr-preview-label {
    font-size: .78rem;
    font-weight: 700;
    color: var(--oss-purple);
    text-align: center;
    max-width: 140px;
    word-break: break-word;
    line-height: 1.3;
  }

  .doc-card .doc-label { font-weight: 700; font-size: .95rem; color: var(--oss-purple); margin-bottom: .2rem; }
  .doc-card .doc-slug  { font-size: .75rem; color: #a0aec0; margin-bottom: .5rem; word-break: break-all; }
  .doc-card .doc-url   { font-size: .72rem; color: var(--oss-purple-light); word-break: break-all; margin-bottom: .75rem; }

  .doc-card .btn-sm {
    display: inline-block;
    font-size: .78rem;
    background: var(--oss-purple-mid);
    color: #fff;
    padding: .35rem .85rem;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: .4rem;
    width: 100%;
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: background .2s;
  }
  .doc-card .btn-sm:hover { background: var(--oss-purple); }

  .btn-delete {
    background: none;
    border: 1px solid #fc8181;
    color: #e53e3e;
    border-radius: 5px;
    font-size: .75rem;
    padding: .3rem .75rem;
    cursor: pointer;
    width: 100%;
    margin-top: .2rem;
    font-family: inherit;
  }
  .btn-delete:hover { background: #fff5f5; }

  .no-docs { color: #a0aec0; font-style: italic; font-size: .9rem; }

  /* ── Footer ── */
  footer {
    background: var(--oss-purple);
    color: var(--oss-text-light);
    text-align: center;
    padding: 1.1rem 1rem;
    font-size: .8rem;
    line-height: 1.7;
  }
  footer strong { color: #fff; font-size: .85rem; }
  footer a { color: var(--oss-accent); text-decoration: none; }
  footer a:hover { text-decoration: underline; }
</style>
</head>
<body>

<header>
  <img class="logo" src="onsite_logo.png" alt="On-Site Studios">
  <div class="header-right">
    <span class="badge">Admin</span>
    <a class="btn-logout" href="admin.php?logout=1">Sign Out</a>
  </div>
</header>

<div class="container">

  <?php if ($message): ?>
    <div class="message <?= $messageType ?>"><?= $message ?></div>
  <?php endif; ?>

  <!-- Upload Card -->
  <div class="card">
    <h2>Upload a Document</h2>
    <form method="POST" enctype="multipart/form-data" id="upload-form">
      <div class="field">
        <label for="doc_name">Document Name <span style="color:#a0aec0;font-weight:400">(becomes the URL slug and QR label)</span></label>
        <input type="text" id="doc_name" name="doc_name" placeholder="e.g. Quick Start Guide">
      </div>
      <div class="upload-area" onclick="document.getElementById('file-input').click()">
        <p><strong>Click to choose a file</strong> or drag &amp; drop</p>
        <p>PNG or PDF</p>
        <div id="file-name"></div>
        <input type="file" id="file-input" name="instruction_file" accept=".png,.pdf,image/png,application/pdf">
      </div>
      <button type="submit" class="btn">Upload &amp; Publish</button>
    </form>
  </div>

  <!-- Published Documents -->
  <div class="card">
    <h2>Published Documents</h2>
    <?php if (empty($uploadedFiles)): ?>
      <p class="no-docs">No documents uploaded yet.</p>
    <?php else: ?>
      <div class="doc-grid">
        <?php foreach ($uploadedFiles as $doc): ?>
          <div class="doc-card">
            <div class="qr-preview-wrap">
              <img class="qr"
                   src="<?= htmlspecialchars($doc['qrUrl']) ?>"
                   width="140" height="140"
                   alt="QR"
                   crossorigin="anonymous">
              <div class="qr-preview-label"><?= htmlspecialchars($doc['label']) ?></div>
            </div>

            <div class="doc-label"><?= htmlspecialchars($doc['label']) ?></div>
            <div class="doc-slug"><?= htmlspecialchars($doc['basename'] . '.' . $doc['ext']) ?></div>
            <div class="doc-url"><?= htmlspecialchars($doc['viewerUrl']) ?></div>

            <button class="btn-sm qr-dl-btn"
                    data-qr-url="<?= htmlspecialchars($doc['qrUrl']) ?>"
                    data-label="<?= htmlspecialchars($doc['label']) ?>"
                    data-basename="<?= htmlspecialchars($doc['basename']) ?>">⬇ QR Code</button>

            <a class="btn-sm" href="print-qr.php?key=<?= urlencode(ADMIN_KEY) ?>&doc=<?= urlencode($doc['basename']) ?>" target="_blank">🖨 Print QR</a>
            <a class="btn-sm" href="<?= htmlspecialchars($doc['viewerUrl']) ?>" target="_blank">👁 Preview</a>

            <form method="POST" onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($doc['label'])) ?>?')">
              <input type="hidden" name="delete_file" value="<?= htmlspecialchars($doc['basename']) ?>">
              <button type="submit" class="btn-delete">🗑 Delete</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div><!-- /container -->

<footer>
  <strong>On-Site Studios</strong><br>
  1400 Lombardi Ave, Suite 50, Green Bay, WI 54304<br>
  <a href="https://on-sitestudios.com">on-sitestudios.com</a>
  &nbsp;·&nbsp;
  © <?= date('Y') ?> On-Site Studios. All rights reserved.
  &nbsp;·&nbsp;
  <span style="color:#7b4bbf">Admin Panel — Keep this URL private</span>
</footer>

<script>
// ── Upload form helpers ────────────────────────────────────────────────────
const fileInput = document.getElementById('file-input');
const fileLabel = document.getElementById('file-name');

fileInput.addEventListener('change', () => {
  fileLabel.textContent = fileInput.files[0] ? '✓ ' + fileInput.files[0].name : '';
});

document.getElementById('upload-form').addEventListener('submit', function (e) {
  const docName = document.getElementById('doc_name').value.trim();
  if (!docName) {
    e.preventDefault();
    alert('Please enter a Document Name before uploading.');
    document.getElementById('doc_name').focus();
  }
});

// ── QR + Label composite download ─────────────────────────────────────────
function downloadQrLabel(qrUrl, label, basename) {
  const QR_SIZE   = 300;
  const PADDING   = 20;
  const LABEL_H   = 52;
  const FONT_SIZE = 22;

  const canvasW = QR_SIZE + PADDING * 2;
  const canvasH = QR_SIZE + PADDING * 2 + LABEL_H;

  const canvas  = document.createElement('canvas');
  canvas.width  = canvasW;
  canvas.height = canvasH;
  const ctx = canvas.getContext('2d');

  const img = new Image();
  img.crossOrigin = 'anonymous';

  img.onload = function () {
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, canvasW, canvasH);

    ctx.drawImage(img, PADDING, PADDING, QR_SIZE, QR_SIZE);

    ctx.textAlign    = 'center';
    ctx.textBaseline = 'middle';
    let fontSize = FONT_SIZE;
    ctx.font = 'bold ' + fontSize + "px 'Segoe UI', Arial, sans-serif";
    while (ctx.measureText(label).width > canvasW - PADDING * 2 && fontSize > 10) {
      fontSize--;
      ctx.font = 'bold ' + fontSize + "px 'Segoe UI', Arial, sans-serif";
    }
    ctx.fillStyle = '#3d1a6e';
    ctx.fillText(label, canvasW / 2, QR_SIZE + PADDING + LABEL_H / 2);

    canvas.toBlob(function (blob) {
      const url = URL.createObjectURL(blob);
      const a   = document.createElement('a');
      a.href     = url;
      a.download = 'qr-' + basename + '.png';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    }, 'image/png');
  };

  img.onerror = function () {
    alert('Could not load the QR image. Check your internet connection and try again.');
  };

  img.src = qrUrl + '&cb=' + Date.now();
}

// ── Wire up QR buttons ─────────────────────────────────────────────────────
document.querySelectorAll('.qr-dl-btn').forEach(function (btn) {
  btn.addEventListener('click', function () {
    downloadQrLabel(
      this.dataset.qrUrl,
      this.dataset.label,
      this.dataset.basename
    );
  });
});
</script>

</body>
</html>