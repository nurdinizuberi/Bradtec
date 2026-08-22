<?php
/* Expects $division (array) and $ROOT. Reads products from data/products.json */
$allProducts = json_read('products.json', []);
$products = array_values(array_filter($allProducts, function ($p) use ($division) {
    return isset($p['division']) && $p['division'] === $division['id'];
}));
$quoteText = isset($division['quote_label']) ? $division['quote_label'] : 'Request a Quote';
?>
<section class="section" id="catalogue">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e($division['name']) ?> Catalogue</span>
      <h2>Products &amp; Services</h2>
      <p>Explore our <?= strtolower(e($division['name'])) ?> offerings. Items below are editable placeholders — the full range is managed from the admin dashboard.</p>
    </div>

    <?php if (empty($products)): ?>
      <div class="empty-state card reveal">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg>
        <h3>Catalogue coming soon</h3>
        <p>BRADTEC is currently updating this catalogue. Contact us for details.</p>
        <a href="<?= $ROOT ?>contact" class="btn btn-primary btn-sm mt-16"><?= e($quoteText) ?></a>
      </div>
    <?php else: ?>
    <div class="catalog-grid">
      <?php foreach ($products as $i => $p): ?>
      <article class="card catalog-card reveal reveal-delay-<?= $i % 3 ?>">
        <div class="cc-media">
          <img src="<?= e($p['image'] ?? '') ?>" alt="<?= e($p['name'] ?? '') ?>" loading="lazy">
        </div>
        <div class="cc-body">
          <span class="cc-cat"><?= e($p['category'] ?? $division['name']) ?></span>
          <h3><?= e($p['name'] ?? '') ?></h3>
          <p><?= e($p['description'] ?? '') ?></p>
          <?php if (!empty($p['features']) && is_array($p['features'])): ?>
          <ul class="cc-list">
            <?php foreach (array_slice($p['features'], 0, 4) as $f): ?>
            <li><?= e($f) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <?php if (!empty($p['specifications']) && is_array($p['specifications'])): ?>
          <dl class="cc-specs">
            <?php foreach (array_slice($p['specifications'], 0, 4) as $k => $v): ?>
            <div><dt><?= e($k) ?></dt><dd><?= e(is_array($v) ? implode(', ', $v) : $v) ?></dd></div>
            <?php endforeach; ?>
          </dl>
          <?php endif; ?>
          <?php if (!empty($p['location']) || !empty($p['availability']) || !empty($p['coverage'])): ?>
          <dl class="cc-specs">
            <?php if (!empty($p['location'])): ?><div><dt>Location</dt><dd><?= e($p['location']) ?></dd></div><?php endif; ?>
            <?php if (!empty($p['availability'])): ?><div><dt>Availability</dt><dd><?= e($p['availability']) ?></dd></div><?php endif; ?>
            <?php if (!empty($p['coverage'])): ?><div><dt>Coverage</dt><dd><?= e($p['coverage']) ?></dd></div><?php endif; ?>
            <?php if (!empty($p['transport_type'])): ?><div><dt>Transport type</dt><dd><?= e($p['transport_type']) ?></dd></div><?php endif; ?>
          </dl>
          <?php endif; ?>
          <div class="cc-actions">
            <a href="<?= $ROOT ?>contact?interest=<?= rawurlencode($division['name']) ?>&amp;subject=<?= rawurlencode(($p['name'] ?? $division['name']) . ' — Inquiry') ?>" class="btn btn-primary btn-sm"><?= e($quoteText) ?></a>
            <?php if (!empty($p['gallery']) && is_array($p['gallery'])): ?>
            <button type="button" class="btn btn-outline btn-sm" data-lightbox='<?= e(json_encode($p['gallery'])) ?>'>Gallery</button>
            <?php endif; ?>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
