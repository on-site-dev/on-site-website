<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
  // echo 'You are accessing the website from localhost.';
  // TEST Connection
  // define('DB_SERVER', '192.168.0.75');
  define('DB_SERVER', 'localhost');
  define('DB_SERVERPORT', '3306');
  define('DB_USERNAME', 'dba');
  define('DB_PASSWORD', 'dba');
  define('DB_NAME', 'wordpress');
} else {
  // echo 'You are NOT accessing the website from localhost.';
  // PROD Connection
  // define('DB_SERVER', '72.167.36.231');
  define('DB_SERVER', 'localhost');
  define('DB_SERVERPORT', '3306');
  define('DB_USERNAME', 'dba');
  define('DB_PASSWORD', 'barfeethedog');
  define('DB_NAME', 'wordpress');
}

header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'UTF-8');
// mb_internal_encoding('UTF-8');


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
    <meta name="description" content="The easiest, most affordable way to create video content for your business - On-Site Studios, America’s leading remote video production company.">
    <meta name="generator" content="HubSpot">
    <title>Fully Remote &amp; Affordable Video Production | On-Site Studios</title>
    <link rel="shortcut icon" href="images/Icon.jpg">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="js/jquery-1.7.1.js"></script>
    <script>
        hsjQuery = window['jQuery'];
    </script>
    <meta property="og:description" content="The easiest, most affordable way to create video content for your business - On-Site Studios, America’s leading remote video production company.">
    <meta property="og:title" content="Fully Remote &amp; Affordable Video Production | On-Site Studios">
    <meta name="twitter:description" content="The easiest, most affordable way to create video content for your business - On-Site Studios, America’s leading remote video production company.">
    <meta name="twitter:title" content="Fully Remote &amp; Affordable Video Production | On-Site Studios">

    <link rel="stylesheet" href="css/module_Home_Two-Column-Section.min.css">
    <link rel="stylesheet" href="css/module_Simple_Columns.min.css">
    <link rel="stylesheet" href="css/template_owlcarouselmin.min.css">
    <link rel="stylesheet" href="css/module_B2B_-_Content_Carousel_Roemon.min.css">
    <link rel="stylesheet" href="css/module_Ark_Features_Roemon_Custom.min.css">

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/module_Agency_-_Testimonial_Slider.min.css">
    <link rel="stylesheet" href="css/module_Agency_-_Background_Image.min.css">
    <link rel="stylesheet" href="css/module_Social_Icons.min.css">

    <!--  Added by GoogleAnalytics4 integration -->
    <script>
        var _hsp = window._hsp = window._hsp || [];
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        var useGoogleConsentModeV2 = true;
        var waitForUpdateMillis = 1000;


        if (!window._hsGoogleConsentRunOnce) {
            window._hsGoogleConsentRunOnce = true;

            gtag('consent', 'default', {
                'ad_storage': 'denied',
                'analytics_storage': 'denied',
                'ad_user_data': 'denied',
                'ad_personalization': 'denied',
                'wait_for_update': waitForUpdateMillis
            });

            if (useGoogleConsentModeV2) {
                _hsp.push(['useGoogleConsentModeV2'])
            } else {
                _hsp.push(['addPrivacyConsentListener', function(consent) {
                    var hasAnalyticsConsent = consent && (consent.allowed || (consent.categories && consent.categories.analytics));
                    var hasAdsConsent = consent && (consent.allowed || (consent.categories && consent.categories.advertisement));

                    gtag('consent', 'update', {
                        'ad_storage': hasAdsConsent ? 'granted' : 'denied',
                        'analytics_storage': hasAnalyticsConsent ? 'granted' : 'denied',
                        'ad_user_data': hasAdsConsent ? 'granted' : 'denied',
                        'ad_personalization': hasAdsConsent ? 'granted' : 'denied'
                    });
                }]);
            }
        }

        gtag('js', new Date());
        gtag('set', 'developer_id.dZTQ1Zm', true);
        gtag('config', 'G-130Q5BCS1E');
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-130Q5BCS1E"></script>

    <!-- /Added by GoogleAnalytics4 integration -->

    <link rel="canonical" href="https://new.on-sitestudios.com">

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PQR93CQ2');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-130Q5BCS1E"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-130Q5BCS1E');
    </script>
    <meta property="og:url" content="https://new.on-sitestudios.com">
    <meta name="twitter:card" content="summary">
    <meta http-equiv="content-language" content="en">
    <link rel="stylesheet" href="css/template_layout.min.css">


    <link rel="stylesheet" href="css/template_B2B_main_style_2019.css">


    <style>
        .icons img:hover {
            -ms-transform: scale(1.5);
            -webkit-transform: scale(1.1);
        }

        .section-padding {
            padding-bottom: 0px;
        }

        .contentSliderContainer {
            padding: 40px 0px 0px;
        }

        
        /* --- Blog grid fixes (place LAST) --- */
      *, *::before, *::after { box-sizing: border-box; }

      /* Use one wrapper; border belongs here */
      .blog-wrap{
        max-width: 1200px;
        width: 100%;
        margin: 0vh auto;
        padding: 6px;
      /*  border: 1px solid #000;*/
      }

      /* Grid */
      .blog-grid{
        display: grid;
        grid-template-columns: repeat(3, minmax(0,1fr));
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
      }
      @media (max-width: 900px){ .blog-grid{ grid-template-columns: repeat(2, minmax(0,1fr)); } }
      @media (max-width: 600px){ .blog-grid{ grid-template-columns: 1fr; } }
      .blog-item{ min-width:0; }
      .blog-card{ display:block; text-decoration:none; color:inherit; }

      /* Thumbnail */
      .blog-grid .thumb{
        /* remove figure’s default margins! */
        margin: 0;

        position: relative;
        width: 100%;
        aspect-ratio: 3 / 2;      /* landscape */
        overflow: hidden;
        border-radius: 12px;
        background: #eee;
      }
      .blog-grid .thumb img{
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        object-position: center;
      }

      .pager{
        margin:16px 0 0;
        display:flex;
        gap:10px;
        align-items:center;
        justify-content:center;   /* ⟵ center the buttons */
        width:100%;
      }


      .pager .btn{
        display:inline-block;
        padding:6px 12px;
        border-radius:8px;
        text-decoration:none;
        font-weight:600;
        line-height:1;
      }

      .pager .btn-blue{
        background:#6631ba;
        color:#fff;
        border:1px solid rgba(0,0,0,.08);
        box-shadow:0 1px 2px rgba(0,0,0,.06);
        transition:background .15s ease, transform .05s ease;
      }
      .pager .btn-blue:hover{ background:#1557d6; }
      .pager .btn-blue:active{ transform:translateY(1px); }
      .pager .btn-blue:focus-visible{ outline:2px solid #84a7ff; outline-offset:2px; }
      .pager .btn-blue[aria-disabled="true"]{ opacity:.5; pointer-events:none; }
        
      .filterbar{
        display:flex;
        justify-content:flex-end; /* put it on the right; use center if you prefer */
        align-items:center;
        gap:8px;
        margin:6px 0 10px;
      }
      .filterbar select{
        appearance:none;
        background:#6631ba;
        color:#fff;
        border:none;
        border-radius:8px;
        padding:8px 28px 8px 12px;
        font-weight:600;
        cursor:pointer;
      }
      .filterbar select:focus{ outline:2px solid #6631ba; }
      .sr-only{ position:absolute; left:-9999px; }
        
          
    </style>


</head>

<body class="top   hs-content-id-31027348770 hs-site-page page " style="">

    <?php
        $pagetitle = "Blog Page";
        include_once 'header.php';
    ?>

    <div class="body-container-wrapper">
        <div class="body-container container-fluid">

            <?php 
              $showScheduleContactLinks = "N";
              $pageTitle = "The New Video Marketing Resource Center";
              $pageSubTitle = "Expert advice to help you plan, create, and share better videos.";

              //$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
              //$posts = getPosts($page, 12);
              $index = isset($_GET['index']) ? intval($_GET['index']) : -1;

              // Selected term (0/empty = All)
              $term = isset($_GET['term']) ? (int)$_GET['term'] : 0;

              // Page number (keep whatever you already use)
              $page = isset($page) ? (int)$page : (isset($_GET['page']) ? (int)$_GET['page'] : 1);

              // Load the dropdown items and posts (new signatures below)
              // $terms = getBlogTerms();
              // $posts = getPosts($term, $page, 9); // 9 per page just as an example
                        
              $perPage = 12; // or 9 if you prefer—just keep it consistent
              $terms   = getBlogTerms();
              $posts   = getPosts($term, $page, $perPage);

              $total        = getPostCount($term);
              $totalPages   = max(1, (int)ceil($total / $perPage));
              $hasPrev      = ($page > 1);
              $hasNext      = ($page < $totalPages);
                      
              // if (isset($posts[$index])): 
              //   $p = $posts[$index]; 
              //   error_log('Post=');
              //   error_log(print_r($p,true));
              //   $post_title = $p['post_title'];
              //   $pageTitle = $post_title;
              //   $pageSubTitle = "";
              //   if (strpos($pageTitle, '|') > 0) {
              //     $parts = explode('|', $post_title);
              //     $pageTitle = $parts[0];
              //     $pageSubTitle = $parts[1];
              //   }
              //   error_log('pt=' . $pageTitle . ', pst=' . $pageSubTitle);
              // endif;

              include_once 'rowwrapper.php'; 

              error_log('total=' . $total . ', total pages=' . $totalPages . ', term=' . $term . ', page=' . $page . ', index=' . $index);
            ?>

            
            
  <div class="blog-wrap">
  <!--  <h1>Blog Page</h1>-->

  <div class="filterbar">
    <form method="get" action="blog.php" id="term-filter">
      <label for="term" class="sr-only">Filter by category</label>
      <select id="term" name="term" onchange="this.form.page.value=1; this.form.submit()">

        <option value="" <?= $term ? '' : 'selected' ?>>All</option>
        <?php foreach ($terms as $t): ?>
          <option value="<?= (int)$t['term_id'] ?>" <?= ($term == (int)$t['term_id'] ? 'selected' : '') ?>>
            <?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="index" value="-1">
      <input type="hidden" name="page" value="<?= (int)$page ?>">
    </form>
  </div>
      
      
      
<?php if ($index < 0): ?>
  <ul class="blog-grid">
    <?php foreach ($posts as $i => $p):
      $imgUrl  = imageUrlFromExcerpt($p['post_excerpt'] ?? '');
      $title   = htmlspecialchars($p['post_title'] ?: 'Untitled', ENT_QUOTES, 'UTF-8');
    ?>
      <li class="blog-item">
        <a class="blog-card"
           href="blog.php?index=<?= $i ?>&page=<?= $page ?>&term=<?= (int)$term ?>"
           target="_self" aria-label="<?= $title ?>">
          <figure class="thumb">
            <img src="<?= $imgUrl ?>" alt="<?= $title ?>" loading="lazy" decoding="async">
          </figure>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php if ($totalPages > 1): ?>
    <p class="pager">
      <?php if ($hasPrev): ?>
        <a class="btn btn-blue"
          href="blog.php?page=<?= $page-1 ?>&term=<?= (int)$term ?>">Prev</a>
      <?php else: ?>
        <span class="btn btn-blue" aria-disabled="true">Prev</span>
      <?php endif; ?>

      <?php if ($hasNext): ?>
        <a class="btn btn-blue"
          href="blog.php?page=<?= $page+1 ?>&term=<?= (int)$term ?>">Next</a>
      <?php else: ?>
        <span class="btn btn-blue" aria-disabled="true">Next</span>
      <?php endif; ?>
    </p>
  <?php endif; ?>

  <?php else: ?>
    <?php if (isset($posts[$index])): 
          $p = $posts[$index]; 
    ?>
      <article>
        <h2><?= htmlspecialchars($p['post_title'], ENT_QUOTES, 'UTF-8') ?></h2>
        <div><?= mapUploadsHtml($p['post_content']) ?></div>
      </article>

      <p class="pager">
        <a class="btn btn-blue" href="blog.php?page=<?= (int)$page ?>&term=<?= (int)$term ?>">Back to list</a>
      </p>

    <?php else: ?>
      <p>Post not found.</p>
      <p class="pager">
        <a class="btn btn-blue" href="blog.php?term=<?= (int)$term ?>">Back to list</a>
      </p>
    <?php endif; ?>
  <?php endif; ?>


</div>



            <div class="row-fluid-wrapper row-depth-1 row-number-1 ">

                <?php include_once 'footer.php';  ?>

                <!-- HubSpot performance collection script -->
                <script defer src="js/embed.js"></script>
                <script>
                    var hsVars = hsVars || {};
                    hsVars['language'] = 'en';
                </script>

                <script src="js/project.js"></script>
                <script src="js/project.js"></script>
                <script src="js/module_Agency_-_Dynamic_Background_Color.min.js"></script>
                <!-- HubSpot Video embed loader -->
                <script async data-hs-portal-id="7145159" data-hs-ignore="true" data-cookieconsent="ignore" data-hs-page-id="31027348770" src="https://static.hsappstatic.net/video-embed/ex/loader.js"></script>
                <script src="js/template_owl-carousel.min.js"></script>
                <script src="js/module_B2B_-_Content_Carousel_Roemon.min.js"></script>
                <script src="js/template_Ark_scripts.min.js"></script>
                <script src="js/module_Agency_-_Testimonial_Slider.min.js"></script>
                <script src="js/module_Agency_-_Background_Image.min.js"></script>
                <script defer src="js/index.js"></script>

                <!-- Google Tag Manager (noscript) -->
                <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PQR93CQ2" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
                <!-- End Google Tag Manager (noscript) -->

                <script type="text/javascript">
                    _linkedin_partner_id = "4951010";
                    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
                    window._linkedin_data_partner_ids.push(_linkedin_partner_id);
                </script>
                <script type="text/javascript">
                    (function(l) {
                        if (!l) {
                            window.lintrk = function(a, b) {
                                window.lintrk.q.push([a, b])
                            };
                            window.lintrk.q = []
                        }
                        var s = document.getElementsByTagName("script")[0];
                        var b = document.createElement("script");
                        b.type = "text/javascript";
                        b.async = true;
                        b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
                        s.parentNode.insertBefore(b, s);
                    })(window.lintrk);
                </script> <noscript> <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=4951010&amp;fmt=gif"> </noscript>



                <!-- Generated by the HubSpot Template Builder - template version 1.03 -->

            </div>
            
        </div>
    </div>
</body>

</html>

<?php
function getPostCount(int $termId = 0): int {
  $count = 0;
  $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERVERPORT);
  if (!$link) return 0;

  $hasTermId = hasPostsTermIdColumn($link);

  if ($hasTermId) {
    if ($termId > 0) {
      $sql = "SELECT COUNT(*) AS c FROM wp_posts
              WHERE post_status='publish' AND post_type='post' AND term_id = ?";
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'i', $termId);
    } else {
      $sql = "SELECT COUNT(*) AS c FROM wp_posts
              WHERE post_status='publish' AND post_type='post'";
      $stmt = mysqli_prepare($link, $sql);
    }
  } else {
    if ($termId > 0) {
      $sql = "SELECT COUNT(DISTINCT p.ID) AS c
              FROM wp_posts p
              JOIN wp_term_relationships tr ON tr.object_id = p.ID
              JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy='category'
              WHERE p.post_status='publish' AND p.post_type='post' AND tt.term_id = ?";
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'i', $termId);
    } else {
      $sql = "SELECT COUNT(*) AS c FROM wp_posts
              WHERE post_status='publish' AND post_type='post'";
      $stmt = mysqli_prepare($link, $sql);
    }
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


function hasPostsTermIdColumn(mysqli $link): bool {
  $res = mysqli_query($link, "SHOW COLUMNS FROM wp_posts LIKE 'term_id'");
  $ok  = ($res && mysqli_num_rows($res) > 0);
  if ($res) mysqli_free_result($res);
  return $ok;
}

function getBlogTerms(): array {
  $rows = [];
  $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERVERPORT);
  if ($link) {
      mysqli_set_charset($link, 'utf8mb4'); // important
      mysqli_query($link, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
  }

  if (!$link) return $rows;

  if (hasPostsTermIdColumn($link)) {
    // Simple custom schema: wp_posts.term_id exists
    $sql = "
      SELECT DISTINCT t.term_id, t.name
      FROM wp_posts p
      JOIN wp_terms t ON t.term_id = p.term_id
      WHERE p.post_status='publish' AND p.post_type='post'
      ORDER BY t.name ASC
    ";
    if ($res = mysqli_query($link, $sql)) {
      while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      mysqli_free_result($res);
    }
  } else {
    // Standard WP schema: use relationships (categories)
    $sql = "
      SELECT DISTINCT t.term_id, t.name
      FROM wp_terms t
      JOIN wp_term_taxonomy tt ON tt.term_id = t.term_id AND tt.taxonomy = 'category'
      JOIN wp_term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
      JOIN wp_posts p ON p.ID = tr.object_id
      WHERE p.post_status='publish' AND p.post_type='post'
      ORDER BY t.name ASC
    ";
    if ($res = mysqli_query($link, $sql)) {
      while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      mysqli_free_result($res);
    }
  }
  mysqli_close($link);
  return $rows;
}

function getPosts(int $termId = 0, int $page = 1, int $perPage = 9): array {
  $out    = [];
  $offset = max(0, ($page - 1) * $perPage);

  
    
  $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERVERPORT);
  if ($link) {
      mysqli_set_charset($link, 'utf8mb4'); // important
      mysqli_query($link, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
  }

    
  if (!$link) return $out;

  if (hasPostsTermIdColumn($link)) {
    // Custom schema path
    $base = "
      SELECT ID, post_title, post_content, post_excerpt, post_date, post_name, term_id
      FROM wp_posts
      WHERE post_status='publish' AND post_type='post' ";
    $tail = " ORDER BY post_date DESC LIMIT ?, ?";

    if ($termId > 0) {
      $sql  = $base . " AND term_id=? " . $tail;
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'iii', $termId, $offset, $perPage);
    } else {
      $sql  = $base . $tail;
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'ii', $offset, $perPage);
    }
  } else {
    // Standard WP relationships (categories)
    if ($termId > 0) {
      $sql = "
        SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_excerpt, p.post_date, p.post_name
        FROM wp_posts p
        JOIN wp_term_relationships tr ON tr.object_id = p.ID
        JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy = 'category'
        WHERE p.post_status='publish' AND p.post_type='post' AND tt.term_id = ?
        ORDER BY p.post_date DESC
        LIMIT ?, ?
      ";
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'iii', $termId, $offset, $perPage);
    } else {
      $sql = "
        SELECT p.ID, p.post_title, p.post_content, p.post_excerpt, p.post_date, p.post_name
        FROM wp_posts p
        WHERE p.post_status='publish' AND p.post_type='post'
        ORDER BY p.post_date DESC
        LIMIT ?, ?
      ";
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'ii', $offset, $perPage);
    }
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



function getFiles($path) 
{
    $files = array();

    try {
        // $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
    }
    catch (Exception $e) 
    {
        // Code to handle the exception
        echo "Caught exception: " . $e->getMessage();
    } 
    finally 
    {
        // Optional: Code that always executes
        // echo "This will always run.";
    }

    return $files;

}


function rewriteUploadsUrl(string $url, array $oldBases, string $newBase): string {
  foreach ($oldBases as $old) {
    if (strpos($url, $old) === 0) {
      return $newBase . substr($url, strlen($old));
    }
  }
  return $url;
}

function rewriteSrcset(string $srcset, array $oldBases, string $newBase): string {
  $items = preg_split('/\s*,\s*/', trim($srcset));
  $out   = [];
  foreach ($items as $item) {
    if ($item === '') continue;
    if (preg_match('/^\s*([^\s]+)(\s+.+)?$/', $item, $m)) {
      $url = rewriteUploadsUrl($m[1], $oldBases, $newBase);
      $out[] = $url . (!empty($m[2]) ? $m[2] : '');
    } else {
      $out[] = $item;
    }
  }
  return implode(', ', $out);
}

function mapUploadsHtml(string $html): string {
  $oldBases = [
    'http://192.168.0.75/wordpress/wp-content/uploads/',
    'https://192.168.0.75/wordpress/wp-content/uploads/',
    'http://192.168.0.75/wp-content/uploads/',
    'https://192.168.0.75/wp-content/uploads/',
  ];
  // $newBase   = 'https://mypicturedayapp.com/onsitews/blog/uploads/';
  $newBase   = 'blog/uploads/';

  // NEW: root-relative paths that should get /onsitews prefixed
  $prefix    = '';
  $prefixFor = ['/blog/']; // add '/category/', '/tag/' if needed later

  if ($html === '') return $html;

  $dom = new DOMDocument('1.0', 'UTF-8');
  libxml_use_internal_errors(true);
  $dom->loadHTML('<?xml encoding="utf-8"?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

  $attrNames = ['src','href','poster','data-src','data-lazy','data-original','data-srcset','srcset','data-lazy-src'];
  $xpath = new DOMXPath($dom);

  foreach ($xpath->query('//*') as $el) {
    foreach ($attrNames as $attr) {
      if (!$el->hasAttribute($attr)) continue;

      $val = $el->getAttribute($attr);

      if ($attr === 'srcset' || $attr === 'data-srcset') {
        // 1) rewrite old uploads → new uploads
        $val = rewriteSrcset($val, $oldBases, $newBase);
        // 2) prefix root-relative items like "/blog/..."
        $val = prefixRootRelativeInSrcset($val, $prefix, $prefixFor);
      } else {
        // 1) rewrite old uploads → new uploads
        $val = rewriteUploadsUrl($val, $oldBases, $newBase);
        // 2) prefix root-relative like "/blog/..."
        $val = prefixCertainRootRelative($val, $prefix, $prefixFor);
      }

      $el->setAttribute($attr, $val);
    }

    // inline CSS: background-image: url(...)
    if ($el->hasAttribute('style')) {
      $style = $el->getAttribute('style');
      $style = preg_replace_callback(
        '/url\(([^)]+)\)/i',
        function ($m) use ($oldBases, $newBase, $prefix, $prefixFor) {
          $raw = trim($m[1], " \t\n\r\0\x0B'\"");
          $rew = rewriteUploadsUrl($raw, $oldBases, $newBase);
          $rew = prefixCertainRootRelative($rew, $prefix, $prefixFor);
          $wrap = (strpos($m[1], '"') !== false) ? '"' : ((strpos($m[1], "'") !== false) ? "'" : '');
          return 'url(' . ($wrap ? $wrap.$rew.$wrap : $rew) . ')';
        },
        $style
      );
      $el->setAttribute('style', $style);
    }
  }

  $out = $dom->saveHTML();
  libxml_clear_errors();
  $out = preg_replace('/^<\?xml.+?\?>/', '', $out);

  // Belt-and-suspenders: catch any missed simple cases
  $out = preg_replace('#(href|src)=([\'"])/blog/#i', '$1=$2/blog/', $out);

  return $out;
}


function prefixCertainRootRelative(string $url, string $prefix, array $paths): string {
  // Only prefix true root-relative URLs (start with "/"), not protocol-relative ("//")
  if ($url === '' || $url[0] !== '/' || strpos($url, '//') === 0) return $url;
  // Avoid double-prefixing
  if (strpos($url, $prefix . '/') === 0) return $url;
  foreach ($paths as $p) {
    if (strpos($url, $p) === 0) return $prefix . $url;
  }
  return $url;
}

function prefixRootRelativeInSrcset(string $srcset, string $prefix, array $paths): string {
  $items = preg_split('/\s*,\s*/', trim($srcset));
  $out   = [];
  foreach ($items as $item) {
    if ($item === '') continue;
    if (preg_match('/^\s*([^\s]+)(\s+.+)?$/', $item, $m)) {
      $url = prefixCertainRootRelative($m[1], $prefix, $paths);
      $out[] = $url . (!empty($m[2]) ? $m[2] : '');
    } else {
      $out[] = $item;
    }
  }
  return implode(', ', $out);
}
function imageUrlFromExcerpt(?string $excerpt,
                             string $imagesBaseUrl = 'images/',
                             ?string $imagesFsPath = null,
                             string $placeholder = 'images/placeholder-2x3.jpg'): string {
  if ($imagesFsPath === null) $imagesFsPath = __DIR__ . '/images/';

  // Clean it up: strip tags, trim, basename to drop any accidental paths
  $raw = basename(trim(strip_tags((string)$excerpt)));

  if ($raw === '') return $placeholder;

  // Allow only safe filename chars
  $name = preg_replace('/[^A-Za-z0-9._-]/', '', $raw);

  // If no extension, default to .jpg
  if (!preg_match('/\.(jpe?g|png|gif|webp|avif)$/i', $name)) {
    $name .= '.jpg';
  }

  // Optional: server-side existence check → fallback
  if (!file_exists($imagesFsPath . $name)) {
    return $placeholder;
  }

  // Build URL (URL-encode just the filename)
  return rtrim($imagesBaseUrl, '/') . '/' . rawurlencode($name);
}

function fixMojibake(string $s): string {
  // common bad→good swaps
  $map = [
    "â€™" => "’", "â€˜" => "‘",
    "â€œ" => "“", "â€" => "”",
    "â€“" => "–", "â€”" => "—", "â€¦" => "…",
    "Â"   => "",   // stray non-breaking space prefix
    "Ã©" => "é", "Ã¡" => "á", "Ã³" => "ó", "Ã­" => "í", "Ãº" => "ú", "Ã±" => "ñ",
    "Ã¼" => "ü", "Ã¶" => "ö", "Ã¤" => "ä", "ÃŸ" => "ß",
    "â€¢" => "•",
    // the “‚Äô” family (double-mangled smart quotes)
    "‚Äô" => "’", "‚Äö" => "”", "‚Äú" => "“", "‚Äî" => "—",
    "‚Äôs" => "’s",
  ];
  return strtr($s, $map);
}


?>
