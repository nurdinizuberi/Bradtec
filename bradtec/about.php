<?php
$ROOT = './';
$PAGE_TITLE = 'About Us — BRADTEC CO. LTD';
$PAGE_DESC = 'Learn about BRADTEC CO. LTD — our mission, vision and values across construction, mining, real estate and transportation.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'About Us', 'url' => '/about']];
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';

$company = load_company();
$stats = $company['stats'];
?>

<main>
  <section class="page-hero">
    <div class="bg-img" style="background-image:url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1920&q=80')" aria-hidden="true"></div>
    <div class="container">
      <p class="crumb"><a href="<?= $ROOT ?>">Home</a> <span class="sep">/</span> About Us</p>
      <h1>About BRADTEC</h1>
      <p>A multi-industry company driven by professionalism, integrity and an unwavering commitment to excellence.</p>
      <div class="accent-bar" aria-hidden="true"></div>
      <p class="script-tagline"><?= e($company['tagline']) ?></p>
    </div>
  </section>

  <!-- Who we are -->
  <section class="section">
    <div class="container split">
      <div class="split-copy reveal">
        <span class="eyebrow">Who We Are</span>
        <h2>Engineering Progress Across Four Industries</h2>
        <p><?= nl2br(e($company['who_we_are'])) ?></p>
        <div class="green-rule" aria-hidden="true"></div>
        <p>From skylines to supply chains, our integrated capabilities let us serve clients with one seamless standard of quality — construction, mining, real estate and transportation working as one company.</p>
      </div>
      <div class="split-media reveal reveal-delay-1">
        <span class="sm-frame" aria-hidden="true"></span>
        <img class="sm-main" src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80" alt="BRADTEC construction site with cranes at dusk" loading="lazy">
      </div>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="section section-soft">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">Our Direction</span>
        <h2>Mission &amp; Vision</h2>
      </div>
      <div class="grid-2">
        <article class="card feature-card reveal">
          <span class="fc-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1"/></svg></span>
          <h3>Our Mission</h3>
          <p><?= e($company['mission']) ?></p>
        </article>
        <article class="card feature-card reveal reveal-delay-1">
          <span class="fc-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></span>
          <h3>Our Vision</h3>
          <p><?= e($company['vision']) ?></p>
        </article>
      </div>
    </div>
  </section>

  <!-- Values -->
  <section class="section">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">What Guides Us</span>
        <h2>Our Values</h2>
        <p>The principles behind every decision, project and partnership.</p>
      </div>
      <div class="grid-3">
        <?php foreach (array_slice($company['values'], 0, 6) as $i => $v): ?>
        <div class="value-chip reveal reveal-delay-<?= $i % 3 ?>" style="justify-content:flex-start">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
          <?= e($v) ?>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="grid-3 mt-24">
        <?php foreach (array_slice($company['values'], 6) as $i => $v): ?>
        <div class="value-chip reveal" style="justify-content:flex-start">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
          <?= e($v) ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Leadership Team -->
  <?php $team = json_read('team.json', []); ?>
  <?php if (!empty($team)): ?>
  <section class="section section-soft" id="team">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">Our People</span>
        <h2>Leadership Team</h2>
        <p>The experienced professionals driving BRADTEC's mission across four industries.</p>
      </div>
      <div class="team-carousel-wrap">
        <div class="team-carousel" id="teamCarousel">
          <?php foreach ($team as $i => $m): ?>
          <div class="card team-card">
            <div class="tc-photo">
              <?php if (!empty($m['image'])): ?>
                <img src="<?= e($m['image']) ?>" alt="<?= e($m['name']) ?>" loading="lazy">
              <?php else: ?>
                <span class="tc-initials"><?= e(mb_strimwidth($m['name'], 0, 2, '')) ?></span>
              <?php endif; ?>
            </div>
            <div class="tc-body">
              <h3><?= e($m['name']) ?></h3>
              <span class="tc-role"><?= e($m['role']) ?></span>
              <div class="tc-social">
                <?php if (!empty($m['social_instagram']) && $m['social_instagram'] !== '#'): ?>
                  <a href="<?= e($m['social_instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg></a>
                <?php endif; ?>
                <?php if (!empty($m['social_facebook']) && $m['social_facebook'] !== '#'): ?>
                  <a href="<?= e($m['social_facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.5 1.6-1.5h1.3V4.9c-.3 0-1.1-.1-2-.1-2 0-3.4 1.2-3.4 3.5V11H8.5v3H11v7h2.5z"/></svg></a>
                <?php endif; ?>
                <?php if (!empty($m['social_linkedin']) && $m['social_linkedin'] !== '#'): ?>
                  <a href="<?= e($m['social_linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6.5 8.8H3.6V21h2.9V8.8zM5 7.4a1.7 1.7 0 1 0 0-3.4 1.7 1.7 0 0 0 0 3.4zM21 14.2c0-3.3-1.8-4.9-4.1-4.9-1.9 0-2.7 1-3.2 1.8V8.8h-2.9V21h2.9v-6.3c0-1.7.8-2.7 2.1-2.7 1.3 0 1.9.9 1.9 2.7V21H21v-6.8z"/></svg></a>
                <?php endif; ?>
                <?php if (!empty($m['social_discord']) && $m['social_discord'] !== '#'): ?>
                  <a href="<?= e($m['social_discord']) ?>" target="_blank" rel="noopener" aria-label="Discord"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.3 4.2A19.7 19.7 0 0 0 15.5 3c-.2.4-.5.9-.7 1.3a18.3 18.3 0 0 0-5.6 0C9 3.9 8.7 3.4 8.5 3a19.5 19.5 0 0 0-4.8 1.2C.5 9.4-.3 14.4.1 19.3A19.8 19.8 0 0 0 6.1 22c.5-.7.9-1.4 1.3-2.2-.7-.3-1.4-.6-2-1l.5-.4a14.1 14.1 0 0 0 12.2 0l.5.4c-.7.4-1.3.7-2 1 .4.8.8 1.5 1.3 2.2a19.7 19.7 0 0 0 6-2.7c.5-5.7-.9-10.6-3.8-14.8zM8.3 16.4c-1.3 0-2.4-1.2-2.4-2.7s1-2.7 2.4-2.7 2.5 1.2 2.4 2.7-1 2.7-2.4 2.7zm7.4 0c-1.3 0-2.4-1.2-2.4-2.7s1-2.7 2.4-2.7 2.5 1.2 2.4 2.7-1 2.7-2.4 2.7z"/></svg></a>
                <?php endif; ?>
              </div>
              <?php if (!empty($m['bio'])): ?>
                <p class="tc-bio"><?= e($m['bio']) ?></p>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Stats -->
  <?php if (!empty($stats)): ?>
  <section class="section section-blue">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow eyebrow-light">By The Numbers</span>
        <h2>Our Achievements</h2>
        <p>Measured results that reflect our commitment to excellence across every project.</p>
      </div>
      <div class="stats-grid">
        <?php foreach ($stats as $i => $s): ?>
        <div class="stat-item reveal reveal-delay-<?= $i % 4 ?>">
          <div class="stat-num"><span data-counter="<?= (int)$s['value'] ?>">0</span><span class="suffix"><?= e($s['suffix']) ?></span></div>
          <div class="stat-label"><?= e($s['label']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="section">
    <div class="container">
      <div class="cta-band reveal">
        <h2>Let's build the future together.</h2>
        <p>Talk to our team about your next construction, mining, real estate or transportation project.</p>
        <a href="<?= $ROOT ?>contact" class="btn btn-green">Contact BRADTEC</a>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
