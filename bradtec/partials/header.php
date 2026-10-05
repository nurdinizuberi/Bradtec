<?php
require_once __DIR__ . '/../api/config.php';
$company = load_company();

$ROOT = isset($ROOT) ? $ROOT : '';
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function nav_active(array $paths, string $uri): string {
    foreach ($paths as $p) {
        if ($uri === $p || str_starts_with($uri, $p . '/')) {
            return ' active';
        }
    }
    return '';
}

$logoExists = is_file(__DIR__ . '/../assets/images/logo.png') || is_file(__DIR__ . '/../assets/images/bradt.png');
?>
<?php $logoFile = is_file(__DIR__ . '/../assets/images/logo.png') ? 'logo.png' : 'bradt.png'; ?>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M4MNX9NR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a href="<?= $ROOT ?>" class="brand" aria-label="<?= e($company['name']) ?> — Home">
      <?php if ($logoExists): ?>
        <picture>
          <source srcset="<?= $ROOT ?>assets/images/bradt.webp" type="image/webp" width="52" height="52">
          <img src="<?= $ROOT ?>assets/images/<?= $logoFile ?>" alt="<?= e($company['name']) ?> logo" class="brand-logo" width="52" height="52" loading="eager" decoding="async">
        </picture>
      <?php else: ?>
        <span class="brand-mark" aria-hidden="true">
          <span class="brand-mark-b"></span>
        </span>
        <span class="brand-text">
          <span class="brand-name"><?= e($company['short_name']) ?><em>CO. LTD</em></span>
          <span class="brand-tagline"><?= e($company['tagline']) ?></span>
        </span>
      <?php endif; ?>
    </a>

    <nav class="main-nav" id="mainNav" aria-label="Main navigation">
      <ul class="nav-list">
        <li><a href="<?= $ROOT ?>" class="nav-link<?= ($uri === '/' || $uri === '/index.php' || $uri === '/index') ? ' active' : '' ?>">Home</a></li>
        <li><a href="<?= $ROOT ?>about" class="nav-link<?= nav_active(['/about'], $uri) ?>">About Us</a></li>
        <li class="has-dropdown<?= nav_active(['/services'], $uri) ?>">
          <button type="button" class="nav-link dropdown-toggle" aria-haspopup="true" aria-expanded="false">
            Services <svg class="chev" viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="dropdown">
            <div class="dropdown-grid">
              <a class="dropdown-item" href="<?= $ROOT ?>services/construction">
                <span class="di-icon di-blue">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4"/></svg>
                </span>
                <span>
                  <strong>Construction</strong>
                  <small>Building infrastructure &amp; development solutions</small>
                </span>
              </a>
              <a class="dropdown-item" href="<?= $ROOT ?>services/mining">
                <span class="di-icon di-green">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20l3-9 3 4 3-8 3 5 4-12M3 21h18"/></svg>
                </span>
                <span>
                  <strong>Mining</strong>
                  <small>Responsible resource extraction &amp; mining solutions</small>
                </span>
              </a>
              <a class="dropdown-item" href="<?= $ROOT ?>services/real-estate">
                <span class="di-icon di-blue">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11l9-8 9 8M5 10v10h14V10M10 20v-6h4v6"/></svg>
                </span>
                <span>
                  <strong>Real Estate</strong>
                  <small>Premium properties &amp; investment opportunities</small>
                </span>
              </a>
              <a class="dropdown-item" href="<?= $ROOT ?>services/transportation">
                <span class="di-icon di-green">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 16V8a2 2 0 0 1 2-2h11v10M13 6h4l3 3v7M6 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                </span>
                <span>
                  <strong>Transportation</strong>
                  <small>Transport, logistics, import &amp; export solutions</small>
                </span>
              </a>
              <a class="dropdown-item" href="<?= $ROOT ?>services/imports-exports">
                <span class="di-icon di-blue">
                  <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </span>
                <span>
                  <strong>Imports/Exports &amp; Distribution</strong>
                  <small>Global trade, customs clearance &amp; distribution</small>
                </span>
              </a>
            </div>
            <a href="<?= $ROOT ?>services" class="dropdown-all">View all services <span aria-hidden="true">→</span></a>
          </div>
        </li>
        <li><a href="<?= $ROOT ?>projects" class="nav-link<?= nav_active(['/projects'], $uri) ?>">Projects</a></li>
        <li><a href="<?= $ROOT ?>contact" class="nav-link<?= nav_active(['/contact'], $uri) ?>">Contact Us</a></li>
      </ul>
      <a href="<?= $ROOT ?>contact" class="btn btn-primary btn-nav">Get In Touch</a>
    </nav>

    <button type="button" class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="mainNav">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
