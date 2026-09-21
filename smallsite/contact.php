<?php
// ─── OSS Studio – Contact Page ─────────────────────────────────────────────
require_once 'config.php';

$sent  = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name']  ?? '');
    $phone     = trim($_POST['phone']      ?? '');
    $email     = trim($_POST['email']      ?? '');
    $comments  = trim($_POST['comments']   ?? '');

    if (!$firstName || !$lastName || !$phone || !$email || !$comments) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $to      = 'ross@on-sitestudios.com';
        $subject = 'Website Contact Form: ' . $firstName . ' ' . $lastName;
        $body    = "First Name:    $firstName\n"
                 . "Last Name:     $lastName\n"
                 . "Phone:         $phone\n"
                 . "Email:         $email\n"
                 . "\nComments / Notes:\n$comments";
        $headers = "From: noreply@on-sitestudios.com\r\nReply-To: $email\r\n";

        if (mail($to, $subject, $body, $headers)) {
            $sent = true;
        } else {
            $error = 'Sorry, there was a problem sending your message. Please email us directly.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contact – On-Site Studios</title>
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
  }

  .header-logo { display: flex; align-items: center; flex-shrink: 0; }

  .header-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-left: auto;
    flex-shrink: 0;
  }

  .header-nav a {
    color: var(--oss-text-light);
    text-decoration: none;
    font-size: clamp(.75rem, 2.5vw, .9rem);
    white-space: nowrap;
    transition: color .2s;
  }
  .header-nav a:hover { color: #fff; }
  .header-nav a.active { color: #fff; font-weight: 600; }

  /* ── Main ── */
  main {
    flex: 1;
    padding: 1.5rem 1rem 2rem;
    max-width: 700px;
    margin: 0 auto;
    width: 100%;
  }

  h1 {
    font-size: clamp(1.2rem, 4vw, 1.6rem);
    font-weight: 700;
    color: var(--oss-purple);
    margin-bottom: 1.5rem;
  }

  /* ── Contact card ── */
  .contact-card {
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(61,26,110,.10);
    padding: 1.5rem 1.5rem 1.75rem;
    margin-bottom: 1.75rem;
    display: flex;
    gap: 1.25rem;
    align-items: flex-start;
  }

  .contact-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--oss-purple-mid);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
    font-weight: 700;
    letter-spacing: -.02em;
  }

  .contact-info h2 {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--oss-purple);
    margin-bottom: .15rem;
  }

  .contact-info .role {
    font-size: .85rem;
    color: var(--oss-purple-light);
    font-weight: 500;
    margin-bottom: .75rem;
  }

  .contact-info .detail-row {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .9rem;
    color: #333;
    margin-bottom: .35rem;
  }

  .contact-info .detail-row .icon { font-size: 1rem; flex-shrink: 0; }

  .contact-info .detail-row a {
    color: var(--oss-purple-mid);
    text-decoration: none;
  }
  .contact-info .detail-row a:hover { text-decoration: underline; }

  /* ── Contact form ── */
  .contact-form-wrap {
    background: #fff;
    border: 1px solid #ddd0f5;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(61,26,110,.10);
    padding: 1.5rem;
  }

  .contact-form-wrap h2 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--oss-purple);
    margin-bottom: 1.1rem;
  }

  /* Two-column row for first/last name */
  .form-row {
    display: flex;
    gap: .75rem;
    margin-bottom: 1rem;
  }
  .form-row .form-group { flex: 1; margin-bottom: 0; }

  @media (max-width: 480px) {
    .form-row { flex-direction: column; gap: 0; }
    .form-row .form-group { margin-bottom: 1rem; }
  }

  .form-group { margin-bottom: 1rem; }

  .form-group label {
    display: block;
    font-size: .85rem;
    font-weight: 600;
    color: var(--oss-purple);
    margin-bottom: .3rem;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: .55rem .75rem;
    border: 1.5px solid #c9b8e8;
    border-radius: 6px;
    font-family: inherit;
    font-size: .95rem;
    color: #1a0a2e;
    background: #faf7ff;
    transition: border-color .2s;
    outline: none;
  }

  .form-group input:focus,
  .form-group textarea:focus {
    border-color: var(--oss-purple-mid);
    background: #fff;
  }

  .form-group textarea {
    height: 130px;
    resize: vertical;
  }

  .btn-send {
    display: block;
    width: 100%;
    background: var(--oss-purple-mid);
    color: #fff;
    border: none;
    padding: .65rem 1.5rem;
    border-radius: 6px;
    font-size: .95rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
    margin-top: .25rem;
  }
  .btn-send:hover { background: var(--oss-purple); }

  /* ── Alerts ── */
  .alert {
    padding: .85rem 1rem;
    border-radius: 8px;
    font-size: .9rem;
    margin-bottom: 1.25rem;
  }
  .alert-success {
    background: #edf7ee;
    border: 1px solid #a3d9a5;
    color: #1e5e22;
  }
  .alert-error {
    background: #fdf0f0;
    border: 1px solid #f5c0c0;
    color: #7a1c1c;
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
  }
  footer strong { color: #fff; font-size: .85rem; }
  footer a { color: var(--oss-accent); text-decoration: none; }
  footer a:hover { text-decoration: underline; }
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
    <a href="contact.php" class="active">Contact</a>
  </nav>
</header>

<main>
  <h1>Contact Us</h1>

<div style="
  background: #fff;
  border: 1px solid #ddd0f5;
  border-radius: 12px;
  box-shadow: 0 2px 16px rgba(61,26,110,.10);
  padding: 1.4rem 1.5rem;
  margin-bottom: 1.75rem;
">
  <h2 style="
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--oss-purple);
    margin-bottom: .4rem;
    text-transform: none;
    letter-spacing: normal;
  ">Connect With Us!</h2>
  <p style="
    font-size: 1rem;
    font-weight: 600;
    color: var(--oss-purple-mid);
    margin-bottom: .65rem;
  ">Reach out at any time.</p>
  <p style="
    font-size: .92rem;
    color: #444;
    line-height: 1.65;
  ">We're always ready to discuss your needs over the phone or showcase our process via a live-stream demo. If you have any questions, please reach out.</p>
</div>

  <!-- Contact card -->
  <div class="contact-card">
    <div class="contact-avatar">RB</div>
    <div class="contact-info">
      <h2>Ross Byrd</h2>
      <div class="role">Operations Manager</div>
      <div class="detail-row">
        <span class="icon">📞</span>
        <a href="tel:+19203213237">(920) 321-3237</a>
      </div>
      <div class="detail-row">
        <span class="icon">✉️</span>
        <a href="mailto:ross@on-sitestudios.com">ross@on-sitestudios.com</a>
      </div>
      <div class="detail-row">
        <span class="icon">🌐</span>
        <a href="https://www.on-sitestudios.com" target="_blank" rel="noopener">www.on-sitestudios.com</a>
      </div>
      <div class="detail-row">
        <span class="icon">📍</span>
        <span>1400 Lombardi Ave, Suite 50, Green Bay, WI 54304</span>
      </div>
    </div>
  </div>

  <!-- Contact form -->
  <div class="contact-form-wrap">
    <h2>Send a Message</h2>

    <?php if ($sent): ?>
      <div class="alert alert-success">
        ✅ Your message was sent! Ross will be in touch soon.
      </div>
    <?php else: ?>

      <?php if ($error): ?>
        <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="contact.php">

        <div class="form-row">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Jane"
                   value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Smith"
                   value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" placeholder="(920) 555-1234"
                 value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="jane@company.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label for="comments">Comments / Notes</label>
          <textarea id="comments" name="comments" placeholder="How can we help?" required><?= htmlspecialchars($_POST['comments'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-send">Send Message</button>
      </form>

    <?php endif; ?>
  </div>
</main>

<footer>
  <strong>On-Site Studios</strong><br>
  1400 Lombardi Ave, Suite 50, Green Bay, WI 54304<br>
  <a href="https://on-sitestudios.com">on-sitestudios.com</a>
  &nbsp;·&nbsp;
  © <?= date('Y') ?> On-Site Studios. All rights reserved.
</footer>

</body>
</html>