<?php
$ROOT = './';
$PAGE_TITLE = 'Our Services — BRADTEC CO. LTD';
$PAGE_DESC = 'Explore BRADTEC services: construction, mining, real estate and transportation & import/export — one company, four industries, one standard of excellence.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Services', 'url' => '/services']];
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';

$services = json_read('services.json', []);
?>

<main>
  <section class="page-hero">
    <div class="bg-img" style="background-image:url('https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1920&q=80')" aria-hidden="true"></div>
    <div class="container">
      <p class="crumb"><a href="<?= $ROOT ?>">Home</a> <span class="sep">/</span> Services</p>
      <h1>Our Services</h1>
      <p>One company. Four industries. One standard of excellence — engineering progress across construction, mining, real estate and transportation.</p>
      <div class="accent-bar" aria-hidden="true"></div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="grid-2">
        <?php foreach ($services as $i => $s):
          $accent = $s['accent'] === 'green' ? 'green' : 'blue';
        ?>
        <article class="card service-card reveal reveal-delay-<?= $i % 3 ?>">
          <a href="<?= $ROOT ?>services/<?= e($s['id']) ?>" style="display:block">
            <div class="sc-media">
              <img src="<?= e($s['image']) ?>" alt="<?= e($s['name']) ?> — <?= e($s['tagline']) ?>" loading="lazy">
            </div>
            <div class="sc-body">
              <span class="sc-icon sc-<?= $accent ?>">
                <?php if ($s['id'] === 'construction'): ?>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4"/></svg>
                <?php elseif ($s['id'] === 'mining'): ?>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20l3-9 3 4 3-8 3 5 4-12M3 21h18"/></svg>
                <?php elseif ($s['id'] === 'real-estate'): ?>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11l9-8 9 8M5 10v10h14V10M10 20v-6h4v6"/></svg>
                <?php else: ?>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 16V8a2 2 0 0 1 2-2h11v10M13 6h4l3 3v7M6 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                <?php endif; ?>
              </span>
              <h3><?= e($s['name']) ?></h3>
              <p><?= e($s['tagline']) ?>.</p>
              <div class="sc-foot">
                <span class="sc-badge b-<?= $accent ?>"><?= $s['id'] === 'transportation' ? 'Import &amp; Export' : e($s['name']) ?></span>
                <span class="link-arrow">Explore <span class="arrow" aria-hidden="true">→</span></span>
              </div>
            </div>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section-soft">
    <div class="container">
      <div class="cta-band reveal">
        <h2>Not sure which service you need?</h2>
        <p>Talk to our team — we'll help you find the right solution across construction, mining, real estate and transportation.</p>
        <a href="<?= $ROOT ?>contact" class="btn btn-green">Get In Touch</a>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
