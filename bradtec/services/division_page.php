<?php
/**
 * Shared template for the division service pages (construction, mining,
 * transportation). The thin page files set $DIVISION_ID and a $PAGE config
 * array, then require this file. Real estate has its own page (property
 * catalogue) and is not routed here.
 *
 * Expected before include:
 *   $ROOT        — relative path prefix (always '../../' from services/)
 *   $PAGE_TITLE, $PAGE_DESC, $PAGE_OG_IMAGE, $PAGE_CRUMBS — set by the page
 *   $DIVISION_ID — id in services.json, e.g. 'construction'
 *   $PAGE        — config array, optional keys:
 *     'overview'     => ['heading', 'image', 'alt', 'cta_label', 'cta_interest']
 *     'capabilities' => ['eyebrow', 'heading', 'blurb', 'items', 'item_note', 'id']
 *                       items: list of strings, or of ['name' =>, 'desc' =>] pairs
 *     'equipment'    => ['eyebrow', 'heading', 'image', 'alt']  (or omit/null)
 *     'sections'     => ['sustainability', 'safety']  → files in services/sections/
 *     'cta'          => ['heading', 'copy', 'primary_label', 'primary_interest',
 *                        'secondary_label', 'secondary_href']
 */

require_once __DIR__ . '/../api/config.php';

/* Look up this division in services.json */
$services = json_read('services.json', []);
$division = null;
foreach ($services as $s) {
    if (isset($s['id']) && $s['id'] === $DIVISION_ID) {
        $division = $s;
        break;
    }
}

require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
?>
<?php if ($division !== null): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": <?= json_script_encode($division['name'] . ' — ' . SITE_NAME) ?>,
  "description": <?= json_script_encode($division['description'] ?? $division['tagline'] ?? '') ?>,
  "provider": {
    "@type": "Organization",
    "name": <?= json_script_encode(SITE_NAME) ?>,
    "url": <?= json_script_encode(rtrim(SITE_URL, '/') . '/') ?>
  },
  "areaServed": "TZ",
  "serviceType": <?= json_script_encode($division['name']) ?>
}
</script>
<?php endif; ?>
<main>
  <?php if ($division === null): ?>
  <!-- Division missing from services.json — render gracefully instead of crashing -->
  <section class="page-hero">
    <div class="bg-img" style="background-image:url('https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1200&q=75')" aria-hidden="true"></div>
    <div class="container">
      <p class="crumb"><a href="<?= $ROOT ?>">Home</a> <span class="sep">/</span> <a href="<?= $ROOT ?>services">Services</a></p>
      <h1>Content Unavailable</h1>
      <p>This section is being updated. Please check back soon or contact our team for details.</p>
      <div class="accent-bar" aria-hidden="true"></div>
    </div>
  </section>
  <section class="section">
    <div class="container" style="text-align:center">
      <div class="card" style="padding:48px 32px;max-width:560px;margin:0 auto">
        <h2 style="margin-bottom:12px">Need information about this service?</h2>
        <p class="muted" style="margin-bottom:24px">Our team will be happy to answer any questions about <?= e(ucwords(str_replace('-', ' ', $DIVISION_ID))) ?> services.</p>
        <a href="<?= $ROOT ?>contact" class="btn btn-primary">Contact BRADTEC</a>
      </div>
    </div>
  </section>
  <?php else: ?>

  <?php require __DIR__ . '/../partials/division_hero.php'; ?>

  <!-- Overview -->
  <?php $ov = isset($PAGE['overview']) ? $PAGE['overview'] : []; ?>
  <section class="section" id="overview">
    <div class="container split">
      <div class="split-copy reveal">
        <span class="eyebrow"><?= e($division['name']) ?> Overview</span>
        <h2><?= e(isset($ov['heading']) ? $ov['heading'] : 'Our ' . $division['name'] . ' Services') ?></h2>
        <p><?= e($division['overview']) ?></p>
        <div class="green-rule" aria-hidden="true"></div>
        <a href="<?= $ROOT ?>contact?interest=<?= rawurlencode(isset($ov['cta_interest']) ? $ov['cta_interest'] : $division['name']) ?>" class="btn btn-primary"><?= e(isset($ov['cta_label']) ? $ov['cta_label'] : 'Request a Quote') ?></a>
      </div>
      <div class="split-media reveal reveal-delay-1">
        <span class="sm-frame" aria-hidden="true"></span>
        <img class="sm-main" src="<?= e(isset($ov['image']) ? $ov['image'] : $division['image']) ?>" alt="<?= e(isset($ov['alt']) ? $ov['alt'] : $division['name']) ?>" width="800" height="600" loading="lazy" decoding="async">
      </div>
    </div>
  </section>

  <!-- Capabilities / channels -->
  <?php $cap = isset($PAGE['capabilities']) ? $PAGE['capabilities'] : []; ?>
  <?php if (!empty($cap['items'])): ?>
  <section class="section section-soft" id="<?= e(isset($cap['id']) ? $cap['id'] : 'capabilities') ?>">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow"><?= e($cap['eyebrow']) ?></span>
        <h2><?= e($cap['heading']) ?></h2>
        <?php if (!empty($cap['blurb'])): ?><p><?= e($cap['blurb']) ?></p><?php endif; ?>
      </div>
      <div class="cap-grid">
        <?php foreach ($cap['items'] as $i => $item):
          $isPair = is_array($item);
        ?>
        <div class="cap-item reveal reveal-delay-<?= $i % 3 ?>">
          <span class="cp-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg></span>
          <div>
            <h4><?= e($isPair ? $item['name'] : $item) ?></h4>
            <p><?= e($isPair ? $item['desc'] : (isset($cap['item_note']) ? $cap['item_note'] : '')) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php require __DIR__ . '/../partials/division_gallery.php'; ?>
  <?php require __DIR__ . '/../partials/catalogue.php'; ?>
  <?php require __DIR__ . '/../partials/featured_projects.php'; ?>

  <!-- Equipment / technology (per-division optional) -->
  <?php $eq = isset($PAGE['equipment']) ? $PAGE['equipment'] : null; ?>
  <?php if ($eq && !empty($division['equipment'])): ?>
  <section class="section" id="equipment">
    <div class="container split">
      <div class="split-media reveal">
        <span class="sm-frame" aria-hidden="true"></span>
        <img class="sm-main" src="<?= e($eq['image']) ?>" alt="<?= e($eq['alt']) ?>" width="800" height="600" loading="lazy" decoding="async">
      </div>
      <div class="split-copy reveal reveal-delay-1">
        <span class="eyebrow"><?= e($eq['eyebrow']) ?></span>
        <h2><?= e($eq['heading']) ?></h2>
        <ul class="highlight-list">
          <?php foreach ($division['equipment'] as $eqItem): ?>
          <li>
            <span class="hl-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg></span>
            <span><strong><?= e($eqItem) ?></strong></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Division-specific extra sections (e.g. mining sustainability/safety) -->
  <?php foreach ((isset($PAGE['sections']) ? (array)$PAGE['sections'] : []) as $sec):
    $secFile = __DIR__ . '/sections/' . basename($sec) . '.php';
    if (is_file($secFile)) require $secFile;
  endforeach; ?>

  <!-- CTA -->
  <?php $cta = isset($PAGE['cta']) ? $PAGE['cta'] : []; ?>
  <section class="section section-soft">
    <div class="container">
      <div class="cta-band reveal">
        <h2><?= e(isset($cta['heading']) ? $cta['heading'] : 'Get in Touch') ?></h2>
        <p><?= e(isset($cta['copy']) ? $cta['copy'] : 'Contact our team to learn more about our services.') ?></p>
        <a href="<?= $ROOT ?>contact?interest=<?= rawurlencode(isset($cta['primary_interest']) ? $cta['primary_interest'] : $division['name']) ?>" class="btn btn-green"><?= e(isset($cta['primary_label']) ? $cta['primary_label'] : 'Request a Quote') ?></a>
        <?php if (!empty($cta['secondary_label'])): ?>
        <a href="<?= e($cta['secondary_href']) ?>" class="btn btn-outline-light" style="margin-left:12px"><?= e($cta['secondary_label']) ?></a>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>
</html>
