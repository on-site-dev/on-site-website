<?php
// ─── OSS Studio Instructions – Configuration ───────────────────────────────

define('ADMIN_KEY', 'oss-admin-2024');
// Title shown on the public viewer page
define('SITE_TITLE', 'Studio Setup Instructions');
// Subfolder where uploaded files are stored (relative to this file)
define('UPLOAD_DIR', 'uploads/studio-instructions/');
// Auto-detect the public URL from the current request — works on any domain
define('PUBLIC_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST']
    . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/');