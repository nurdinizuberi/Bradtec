<?php
require_once __DIR__ . '/../api/config.php';
$company = load_company();
$ROOT = isset($ROOT) ? $ROOT : '';
$social = $company['social'];
$socialIcons = [
    'facebook' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.5 1.6-1.5h1.3V4.9c-.3 0-1.1-.1-2-.1-2 0-3.4 1.2-3.4 3.5V11H8.5v3H11v7h2.5z"/></svg>',
    'instagram' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg>',
    'linkedin' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M6.5 8.8H3.6V21h2.9V8.8zM5 7.4a1.7 1.7 0 1 0 0-3.4 1.7 1.7 0 0 0 0 3.4zM21 14.2c0-3.3-1.8-4.9-4.1-4.9-1.9 0-2.7 1-3.2 1.8V8.8h-2.9V21h2.9v-6.3c0-1.7.8-2.7 2.1-2.7 1.3 0 1.9.9 1.9 2.7V21H21v-6.8z"/></svg>',
    'youtube' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M22 12s0-3.4-.4-5c-.2-.9-.9-1.6-1.8-1.8C18.2 4.8 12 4.8 12 4.8s-6.2 0-7.8.4c-.9.2-1.6.9-1.8 1.8C2 8.6 2 12 2 12s0 3.4.4 5c.2.9.9 1.6 1.8 1.8 1.6.4 7.8.4 7.8.4s6.2 0 7.8-.4c-.9.2-.2-.9 0-1.8.4-1.6.4-5 .4-5zM10 15.5v-7l6 3.5-6 3.5z"/></svg>',
    'x' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17.5 3h3.1l-6.8 7.8L21.8 21h-6.3l-4.9-6.4L5 21H1.9l7.3-8.3L1.5 3h6.4l4.4 5.9L17.5 3zm-1.1 16.1h1.7L7.1 4.7H5.3l11.1 14.4z"/></svg>',
];
?>
<footer class="site-footer">
  <div class="footer-growth" aria-hidden="true">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" width="100%" height="60">
      <path d="M0,40 C360,10 720,55 1080,25 S1380,8 1440,20 L1440,60 L0,60 Z" fill="rgba(18,196,77,0.10)"/>
      <path d="M0,52 C420,30 760,62 1140,36 S1360,30 1440,40 L1440,60 L0,60 Z" fill="rgba(255,255,255,0.04)"/>
    </svg>
  </div>
  <div class="container footer-grid">
    <div class="footer-brand">
      <?php $logoFile = is_file(__DIR__ . '/../assets/images/logo.png') ? 'logo.png' : 'bradt.png'; ?>
      <?php if (is_file(__DIR__ . '/../assets/images/' . $logoFile)): ?>
        <picture>
          <source srcset="<?= $ROOT ?>assets/images/bradt.webp" type="image/webp" width="48" height="48">
          <img src="<?= $ROOT ?>assets/images/<?= $logoFile ?>" alt="<?= e($company['name']) ?> logo" class="footer-logo" width="48" height="48" loading="lazy" decoding="async">
        </picture>
      <?php else: ?>
        <span class="brand-text footer-brand-text">
          <span class="brand-name"><?= e($company['short_name']) ?><em>CO. LTD</em></span>
        </span>
      <?php endif; ?>
      <h3 class="footer-name"><?= e($company['name']) ?></h3>
      <p class="footer-tagline"><?= e($company['tagline']) ?></p>
      <p class="footer-desc"><?= e($company['description']) ?></p>
      <ul class="footer-social">
        <?php foreach ($socialIcons as $key => $icon): ?>
          <li><a href="<?= e($social[$key] ?? '#') ?>" target="_blank" rel="noopener" aria-label="<?= ucfirst($key) ?>"><?= $icon ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <nav class="footer-col" aria-label="Company links">
      <h4>Company</h4>
      <ul>
        <li><a href="<?= $ROOT ?>">Home</a></li>
        <li><a href="<?= $ROOT ?>about">About</a></li>
        <li><a href="<?= $ROOT ?>projects">Projects</a></li>
        <li><a href="<?= $ROOT ?>contact">Contact</a></li>
      </ul>
    </nav>

    <nav class="footer-col" aria-label="Services links">
      <h4>Services</h4>
      <ul>
        <li><a href="<?= $ROOT ?>services/construction">Construction</a></li>
        <li><a href="<?= $ROOT ?>services/mining">Mining</a></li>
        <li><a href="<?= $ROOT ?>services/real-estate">Real Estate</a></li>
        <li><a href="<?= $ROOT ?>services/transportation">Transportation</a></li>
        <li><a href="<?= $ROOT ?>services/imports-exports">Imports/Exports &amp; Distribution</a></li>
      </ul>
    </nav>

    <div class="footer-col">
      <h4>Contact</h4>
      <ul class="footer-contact">
        <li>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2z"/></svg>
          <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $company['phone'])) ?>"><?= e($company['phone']) ?></a>
        </li>
        <li>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6L22 7"/></svg>
          <a href="mailto:<?= e($company['email']) ?>"><?= e($company['email']) ?></a>
        </li>
        <li>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
          <span><?= e($company['address']) ?></span>
        </li>
        <li>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-8.5 8.5 8.6 8.6 0 0 1-3.7-.8L3 21l1.8-5.8A8.4 8.4 0 0 1 4 11.5 8.4 8.4 0 0 1 12.5 3a8.4 8.4 0 0 1 8.5 8.5z" transform="translate(0 0)"/><path d="M12.5 21c-1.3 0-2.5-.2-3.6-.7"/></svg>
          <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', $company['whatsapp'])) ?>" target="_blank" rel="noopener">WhatsApp Us</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p>© <?= date('Y') ?> <?= e($company['name']) ?>. All Rights Reserved.</p>
      <p class="footer-bottom-tag"><?= e($company['tagline']) ?> · Construction • Mining • Real Estate • Transportation • Imports/Exports &amp; Distribution</p>
    </div>
  </div>
</footer>
