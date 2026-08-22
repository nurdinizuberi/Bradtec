<?php
$ROOT = '../../';
$PAGE_TITLE = 'Real Estate & Property Listings — BRADTEC CO. LTD';
$PAGE_DESC = 'Premium spaces, exceptional opportunities. Browse BRADTEC property listings — residential, commercial, land and apartments with full details.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Services', 'url' => '/services'], ['name' => 'Real Estate', 'url' => '/services/real-estate']];
$PAGE_SCRIPTS = ['realestate.js'];
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';

$services = json_read('services.json', []);
$division = null;
foreach ($services as $s) { if ($s['id'] === 'real-estate') { $division = $s; break; } }
$properties = json_read('properties.json', []);
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
  <?php require __DIR__ . '/../partials/division_hero.php'; ?>

  <!-- Overview -->
  <section class="section" id="overview">
    <div class="container split">
      <div class="split-copy reveal">
        <span class="eyebrow">Real Estate Overview</span>
        <h2>Premium Spaces. Exceptional Opportunities.</h2>
        <p><?= e($division['overview']) ?></p>
        <div class="green-rule" aria-hidden="true"></div>
        <a href="<?= $ROOT ?>contact?interest=Real%20Estate" class="btn btn-primary">Contact Our Team</a>
      </div>
      <div class="split-media reveal reveal-delay-1">
        <span class="sm-frame" aria-hidden="true"></span>
        <img class="sm-main" src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80" alt="Premium modern residential property by BRADTEC" loading="lazy">
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/../partials/division_gallery.php'; ?>

  <!-- Property catalogue -->
  <section class="section section-soft" id="properties">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow">Property Catalogue</span>
        <h2>Available Properties</h2>
        <p>Search and filter our listings. Properties shown are editable placeholders — manage them from the admin dashboard.</p>
      </div>

      <div class="props-toolbar reveal">
        <div class="search-box">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="search" id="propSearch" placeholder="Search properties, locations…" aria-label="Search properties">
        </div>
        <div class="props-selects">
          <select id="propType" aria-label="Filter by property type">
            <option value="">All Types</option>
            <option>Residential</option>
            <option>Commercial</option>
            <option>Land</option>
            <option>Apartments</option>
            <option>Houses</option>
            <option>Offices</option>
            <option>Other</option>
          </select>
          <select id="propStatus" aria-label="Filter by status">
            <option value="">All Statuses</option>
            <option>Available</option>
            <option>Reserved</option>
            <option>Sold</option>
            <option>Coming Soon</option>
          </select>
        </div>
      </div>

      <div class="property-grid" id="propertyGrid">
        <?php foreach ($properties as $i => $p): ?>
        <article class="card property-card reveal reveal-delay-<?= $i % 3 ?>"
                 data-name="<?= e(strtolower($p['name'])) ?>"
                 data-location="<?= e(strtolower($p['location'])) ?>"
                 data-type="<?= e($p['type']) ?>" data-status="<?= e($p['status']) ?>">
          <div class="pr-media">
            <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
            <span class="pr-status st-<?= strtolower(str_replace(' ', '-', $p['status'])) ?>"><?= e($p['status']) ?></span>
            <span class="pr-price"><?= e($p['price']) ?></span>
          </div>
          <div class="pr-body">
            <h3><?= e($p['name']) ?></h3>
            <p class="pr-loc"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg><?= e($p['location']) ?></p>
            <div class="pr-specs">
              <?php if ((int)$p['bedrooms'] > 0): ?><span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6M3 18h18M3 18v2m18-2v2M6 10V7a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v3M13 10V7a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v3"/></svg><?= (int)$p['bedrooms'] ?> Beds</span><?php endif; ?>
              <?php if ((int)$p['bathrooms'] > 0): ?><span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 12h16v2a6 6 0 0 1-6 6H10a6 6 0 0 1-6-6v-2zM6 12V6a3 3 0 0 1 6 0v6M8 4v2m2-2v2"/></svg><?= (int)$p['bathrooms'] ?> Baths</span><?php endif; ?>
              <?php if (!empty($p['size'])): ?><span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 9l9-6 9 6M3 9v12m0-12h18m0 0v12M7 21v-6h10v6"/></svg><?= e($p['size']) ?></span><?php endif; ?>
            </div>
            <div class="pr-actions">
              <button type="button" class="btn btn-primary btn-sm" data-prop="<?= e($p['id']) ?>" data-prop-open>View Details</button>
              <a href="<?= $ROOT ?>contact?interest=Real%20Estate&amp;subject=<?= rawurlencode('Property Viewing: ' . $p['name']) ?>" class="btn btn-outline btn-sm">Request Viewing</a>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

      <div class="empty-state card" id="propEmpty" style="display:none">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <h3>No properties found</h3>
        <p>Try adjusting your search or filters.</p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="cta-band reveal">
        <h2>Looking for a specific property?</h2>
        <p>Tell us what you need — our real estate team will help you find the right space or investment.</p>
        <a href="<?= $ROOT ?>contact?interest=Real%20Estate" class="btn btn-green">Contact Our Team</a>
      </div>
    </div>
  </section>
</main>

<!-- Property details data for the modal -->
<script>window.BRADTEC_ROOT = '<?= $ROOT ?>';</script>
<script type="application/json" id="propertiesData"><?= json_script_encode($properties) ?></script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>
</html>
