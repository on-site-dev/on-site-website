<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

define('DB_SERVER', 'localhost');
define('DB_SERVERPORT', '3306');
define('DB_USERNAME', 'dba');
define('DB_PASSWORD', 'barfeethedog');
define('DB_NAME', 'wordpress');

header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'UTF-8');

?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en" > <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en" >        <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en" >               <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="On-Site Studios">
    <meta name="description" content="Page configuration elements grid.">
    <title>Page Config | On-Site Studios</title>
    <link rel="shortcut icon" href="images/Icon.jpg">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="js/jquery-1.7.1.js"></script>
    <script>
        hsjQuery = window['jQuery'];
    </script>

    <link rel="stylesheet" href="css/template_layout.min.css">
    <link rel="stylesheet" href="css/template_B2B_main_style_2019.css">

    <style>
        .section-padding {
            padding-bottom: 0px;
        }

        /* --- Page-config grid (place LAST) --- */
        *, *::before, *::after { box-sizing: border-box; }

        .cfg-wrap {
            max-width: 1200px;
            width: 100%;
            margin: 0vh auto;
            padding: 6px;
        }

        .cfg-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 16px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        @media (max-width: 900px) { .cfg-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media (max-width: 600px) { .cfg-grid { grid-template-columns: 1fr; } }

        .cfg-item { min-width: 0; }
        .cfg-card {
            display: block;
            text-decoration: none;
            color: inherit;
            border: 1px solid rgba(0,0,0,.08);
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            height: 100%;
        }

        .cfg-grid .thumb {
            margin: 0;
            position: relative;
            width: 100%;
            aspect-ratio: 3 / 2;
            overflow: hidden;
            background: #eee;
        }
        .cfg-grid .thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        .cfg-body { padding: 12px 14px 14px; }
        .cfg-title { font-weight: 700; font-size: 1.05rem; margin: 0 0 4px; }
        .cfg-sub { font-size: .8rem; color: #666; margin: 0 0 8px; word-break: break-all; }
        .cfg-content {
            font-size: .9rem;
            color: #333;
            margin: 0 0 10px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .cfg-meta { font-size: .75rem; color: #888; display: flex; justify-content: space-between; align-items: center; }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .02em;
        }
        .badge-active { background: #e5f6ea; color: #1c7c3f; }
        .badge-inactive { background: #fdecec; color: #b3261e; }

        .pager {
            margin: 16px 0 0;
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .pager .btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            line-height: 1;
        }
        .pager .btn-blue {
            background: #6631ba;
            color: #fff;
            border: 1px solid rgba(0,0,0,.08);
            box-shadow: 0 1px 2px rgba(0,0,0,.06);
            transition: background .15s ease, transform .05s ease;
        }
        .pager .btn-blue:hover { background: #1557d6; }
        .pager .btn-blue:active { transform: translateY(1px); }
        .pager .btn-blue:focus-visible { outline: 2px solid #84a7ff; outline-offset: 2px; }
        .pager .btn-blue[aria-disabled="true"] { opacity: .5; pointer-events: none; }

        .filterbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            margin: 6px 0 16px;
            flex-wrap: wrap;
        }
        .filterbar select {
            appearance: none;
            background: #6631ba;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 28px 8px 12px;
            font-weight: 600;
            cursor: pointer;
        }
        .filterbar select:focus { outline: 2px solid #6631ba; }
        .sr-only { position: absolute; left: -9999px; }

        .empty-state { text-align: center; padding: 60px 10px; color: #777; }
    </style>

</head>

<body class="top hs-content-id-page-config hs-site-page page " style="">

    <?php
        $pagetitle = "Page Config";
        if (file_exists(__DIR__ . '/header.php')) {
            include_once 'header.php';
        }
    ?>

    <div class="body-container-wrapper">
        <div class="body-container container-fluid">

            <?php
                $index = isset($_GET['index']) ? intval($_GET['index']) : -1;

                // Selected filters
                $pageNameFilter = isset($_GET['page_name']) ? trim($_GET['page_name']) : '';
                $statusFilter   = isset($_GET['status']) ? trim($_GET['status']) : 'A'; // default: Active only

                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($page < 1) $page = 1;

                $perPage = 12;

                $pageNames  = getDistinctPageNames();
                $rows       = getPageConfigRows($pageNameFilter, $statusFilter, $page, $perPage);

                $total      = getPageConfigCount($pageNameFilter, $statusFilter);
                $totalPages = max(1, (int)ceil($total / $perPage));
                $hasPrev    = ($page > 1);
                $hasNext    = ($page < $totalPages);
            ?>

            <div class="cfg-wrap">
                <h1>Page Config</h1>

                <div class="filterbar">
                    <form method="get" action="page_config.php" id="cfg-filter">
                        <label for="page_name" class="sr-only">Filter by page</label>
                        <select id="page_name" name="page_name" onchange="this.form.page.value=1; this.form.submit()">
                            <option value="" <?= $pageNameFilter === '' ? 'selected' : '' ?>>All Pages</option>
                            <?php foreach ($pageNames as $pn): ?>
                                <option value="<?= htmlspecialchars($pn, ENT_QUOTES, 'UTF-8') ?>" <?= ($pageNameFilter === $pn ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($pn, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="status" class="sr-only">Filter by status</label>
                        <select id="status" name="status" onchange="this.form.page.value=1; this.form.submit()">
                            <option value="A" <?= $statusFilter === 'A' ? 'selected' : '' ?>>Active</option>
                            <option value="I" <?= $statusFilter === 'I' ? 'selected' : '' ?>>Inactive</option>
                            <option value=""  <?= $statusFilter === ''  ? 'selected' : '' ?>>All Statuses</option>
                        </select>

                        <input type="hidden" name="index" value="-1">
                        <input type="hidden" name="page" value="<?= (int)$page ?>">
                    </form>
                </div>

                <?php if ($index < 0): ?>

                    <?php if (empty($rows)): ?>
                        <div class="empty-state">No page config rows found for the selected filters.</div>
                    <?php else: ?>
                        <ul class="cfg-grid">
                            <?php foreach ($rows as $i => $r):
                                $imgUrl   = resolveConfigImage($r['image'] ?? '');
                                $title    = htmlspecialchars($r['page_name'] ?: 'Untitled', ENT_QUOTES, 'UTF-8');
                                $fileName = htmlspecialchars($r['file_name'] ?: '', ENT_QUOTES, 'UTF-8');
                                $elemId   = htmlspecialchars($r['element_name_id'] ?: '', ENT_QUOTES, 'UTF-8');
                                $content  = htmlspecialchars($r['content'] ?: '', ENT_QUOTES, 'UTF-8');
                                $isActive = (strtoupper((string)$r['status']) === 'A');
                            ?>
                                <li class="cfg-item">
                                    <a class="cfg-card"
                                       href="page_config.php?index=<?= $i ?>&page=<?= $page ?>&page_name=<?= urlencode($pageNameFilter) ?>&status=<?= urlencode($statusFilter) ?>"
                                       target="_self" aria-label="<?= $title ?>">
                                        <figure class="thumb">
                                            <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= $title ?>" loading="lazy" decoding="async">
                                        </figure>
                                        <div class="cfg-body">
                                            <p class="cfg-title"><?= $title ?></p>
                                            <p class="cfg-sub"><?= $fileName ?><?= ($fileName && $elemId) ? ' &middot; ' : '' ?><?= $elemId ?></p>
                                            <p class="cfg-content"><?= $content ?></p>
                                            <div class="cfg-meta">
                                                <span class="badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                                                    <?= $isActive ? 'Active' : 'Inactive' ?>
                                                </span>
                                                <span>ID: <?= (int)$r['ID'] ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if ($totalPages > 1): ?>
                            <p class="pager">
                                <?php if ($hasPrev): ?>
                                    <a class="btn btn-blue"
                                       href="page_config.php?page=<?= $page-1 ?>&page_name=<?= urlencode($pageNameFilter) ?>&status=<?= urlencode($statusFilter) ?>">Prev</a>
                                <?php else: ?>
                                    <span class="btn btn-blue" aria-disabled="true">Prev</span>
                                <?php endif; ?>

                                <span>Page <?= (int)$page ?> of <?= (int)$totalPages ?></span>

                                <?php if ($hasNext): ?>
                                    <a class="btn btn-blue"
                                       href="page_config.php?page=<?= $page+1 ?>&page_name=<?= urlencode($pageNameFilter) ?>&status=<?= urlencode($statusFilter) ?>">Next</a>
                                <?php else: ?>
                                    <span class="btn btn-blue" aria-disabled="true">Next</span>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php else: ?>

                    <?php if (isset($rows[$index])):
                        $r = $rows[$index];
                        $imgUrl = resolveConfigImage($r['image'] ?? '');
                    ?>
                        <article>
                            <h2><?= htmlspecialchars($r['page_name'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <figure class="thumb" style="max-width:480px;border-radius:12px;">
                                <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy">
                            </figure>
                            <table>
                                <tr><td><strong>ID</strong></td><td><?= (int)$r['ID'] ?></td></tr>
                                <tr><td><strong>File Name</strong></td><td><?= htmlspecialchars($r['file_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td></tr>
                                <tr><td><strong>Element Name/ID</strong></td><td><?= htmlspecialchars($r['element_name_id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td></tr>
                                <tr><td><strong>Status</strong></td><td><?= strtoupper((string)$r['status']) === 'A' ? 'Active' : 'Inactive' ?></td></tr>
                                <tr><td><strong>Created</strong></td><td><?= htmlspecialchars($r['created_by'] ?? '', ENT_QUOTES, 'UTF-8') ?> on <?= htmlspecialchars($r['created_on'] ?? '', ENT_QUOTES, 'UTF-8') ?></td></tr>
                                <tr><td><strong>Modified</strong></td><td><?= htmlspecialchars($r['modified_by'] ?? '', ENT_QUOTES, 'UTF-8') ?> on <?= htmlspecialchars($r['modified_on'] ?? '', ENT_QUOTES, 'UTF-8') ?></td></tr>
                            </table>
                            <div><?= nl2br(htmlspecialchars($r['content'] ?? '', ENT_QUOTES, 'UTF-8')) ?></div>
                        </article>

                        <p class="pager">
                            <a class="btn btn-blue" href="page_config.php?page=<?= (int)$page ?>&page_name=<?= urlencode($pageNameFilter) ?>&status=<?= urlencode($statusFilter) ?>">Back to list</a>
                        </p>

                    <?php else: ?>
                        <p>Row not found.</p>
                        <p class="pager">
                            <a class="btn btn-blue" href="page_config.php?page_name=<?= urlencode($pageNameFilter) ?>&status=<?= urlencode($statusFilter) ?>">Back to list</a>
                        </p>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <?php if (file_exists(__DIR__ . '/footer.php')) { include_once 'footer.php'; } ?>

        </div>
    </div>
</body>

</html>

<?php

/**
 * Total count of wp_page_config rows matching the given filters.
 */
function getPageConfigCount(string $pageName = '', string $status = 'A'): int {
    $count = 0;
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERVERPORT);
    if (!$link) return 0;

    $where  = [];
    $types  = '';
    $params = [];

    if ($pageName !== '') {
        $where[]  = 'page_name = ?';
        $types   .= 's';
        $params[] = $pageName;
    }
    if ($status !== '') {
        $where[]  = 'status = ?';
        $types   .= 's';
        $params[] = $status;
    }

    $sql = "SELECT COUNT(*) AS c FROM wp_page_config";
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    $stmt = mysqli_prepare($link, $sql);
    if ($stmt && !empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if ($stmt && mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($res)) $count = (int)$row['c'];
        if ($res) mysqli_free_result($res);
    }
    if ($stmt) mysqli_stmt_close($stmt);
    mysqli_close($link);
    return $count;
}

/**
 * Fetch a page of wp_page_config rows matching the given filters.
 */
function getPageConfigRows(string $pageName = '', string $status = 'A', int $page = 1, int $perPage = 12): array {
    $out    = [];
    $offset = max(0, ($page - 1) * $perPage);

    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERVERPORT);
    if ($link) {
        mysqli_set_charset($link, 'utf8mb4');
        mysqli_query($link, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    }
    if (!$link) return $out;

    $where  = [];
    $types  = '';
    $params = [];

    if ($pageName !== '') {
        $where[]  = 'page_name = ?';
        $types   .= 's';
        $params[] = $pageName;
    }
    if ($status !== '') {
        $where[]  = 'status = ?';
        $types   .= 's';
        $params[] = $status;
    }

    $sql = "SELECT ID, page_name, file_name, element_name_id, content, image, status,
                   created_by, created_on, modified_by, modified_on
            FROM wp_page_config";
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sql .= " ORDER BY page_name ASC, ID ASC LIMIT ?, ?";

    $types   .= 'ii';
    $params[] = $offset;
    $params[] = $perPage;

    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if ($stmt && mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) $out[] = $row;
        mysqli_free_result($res);
    }
    if ($stmt) mysqli_stmt_close($stmt);
    mysqli_close($link);

    return $out;
}

/**
 * Distinct page_name values, for the filter dropdown.
 */
function getDistinctPageNames(): array {
    $out  = [];
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERVERPORT);
    if (!$link) return $out;

    mysqli_set_charset($link, 'utf8mb4');

    $sql = "SELECT DISTINCT page_name FROM wp_page_config WHERE page_name IS NOT NULL AND page_name <> '' ORDER BY page_name ASC";
    if ($res = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) $out[] = $row['page_name'];
        mysqli_free_result($res);
    }
    mysqli_close($link);
    return $out;
}

/**
 * Resolve the thumbnail URL for a page_config row's `image` value.
 * Accepts either a full/relative URL already, or a bare filename that lives
 * under the local images/ directory. Falls back to a placeholder.
 */
function resolveConfigImage(?string $image,
                             string $imagesBaseUrl = 'images/',
                             ?string $imagesFsPath = null,
                             string $placeholder = 'images/placeholder-2x3.jpg'): string {
    if ($imagesFsPath === null) $imagesFsPath = __DIR__ . '/images/';

    $raw = trim((string)$image);
    if ($raw === '') return $placeholder;

    // If it's already a URL or root-relative path, use it as-is.
    if (preg_match('#^(https?:)?//#i', $raw) || $raw[0] === '/') {
        return $raw;
    }

    // Otherwise treat it as a filename under images/, checking safe chars.
    $name = basename($raw);
    $name = preg_replace('/[^A-Za-z0-9._-]/', '', $name);

    if (!preg_match('/\.(jpe?g|png|gif|webp|avif|svg)$/i', $name)) {
        $name .= '.jpg';
    }

    if (!file_exists($imagesFsPath . $name)) {
        return $placeholder;
    }

    return rtrim($imagesBaseUrl, '/') . '/' . rawurlencode($name);
}
