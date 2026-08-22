<?php
$ROOT = './';
$PAGE_TITLE = 'BRADTEC CO. LTD — Engineering Progress. Building the Future.';
$PAGE_DESC = 'BRADTEC CO. LTD engineers progress through professional construction, responsible mining, premium real estate development, and reliable transportation and international trade solutions.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80';
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';

$company = load_company();
$services = json_read('services.json', []);
$projects = array_slice(json_read('projects.json', []), 0, 3);
$stats = $company['stats'];
?>

<main>
  <!-- ============ HERO ============ -->
  <section class="hero" aria-label="Introduction">
    <div class="hero-lines" aria-hidden="true">
      <span class="hl"></span><span class="hl"></span><span class="hl"></span><span class="hl"></span><span class="hl"></span>
    </div>
    <svg class="hero-sweep" viewBox="0 0 420 420" fill="none" aria-hidden="true">
      <path d="M20 380 C 140 330, 120 230, 240 180 S 360 110, 400 30" stroke="#12c44d" stroke-width="6" stroke-linecap="round"/>
      <path d="M370 30 l 28 12 M370 30 l 2 30" stroke="#12c44d" stroke-width="6" stroke-linecap="round"/>
    </svg>
    <div class="container hero-content">
      <p class="hero-eyebrow"><span class="dot"></span> <?= e($company['name']) ?> · Construction · Mining · Real Estate · Transportation</p>
      <h1>Engineering Progress.<br><span class="grad">Building the Future.</span></h1>
      <p class="lead">At <?= e($company['name']) ?>, we engineer progress through professional construction, responsible mining, premium real estate development, and reliable transportation and international trade solutions.</p>
      <div class="hero-ctas">
        <a href="<?= $ROOT ?>services" class="btn btn-primary">Explore Our Services</a>
        <a href="<?= $ROOT ?>contact" class="btn btn-outline-light">Contact BRADTEC</a>
      </div>
      <p class="hero-script"><?= e($company['tagline']) ?></p>
    </div>
    <?php if (!empty($stats)): ?>
    <div class="container">
      <div class="hero-meta">
        <?php foreach (array_slice($stats, 0, 3) as $s): ?>
          <div class="hm-item">
            <div class="hm-num"><span data-counter="<?= (int)$s['value'] ?>">0</span><?= e($s['suffix']) ?></div>
            <div class="hm-label"><?= e($s['label']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <!-- ============ WELCOME / BRAND INTRO ============ -->
  <section class="section" id="welcome">
    <div class="container split">
      <div class="split-media reveal">
        <span class="sm-frame" aria-hidden="true"></span>
        <img class="sm-main" src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=80" alt="White and brown concrete building under blue sky — BRADTEC engineering excellence" loading="lazy">
        <div class="sm-float">
          <span class="sf-num"><span data-counter="<?= !empty($stats[0]) ? (int)$stats[0]['value'] : 10 ?>">0</span>+</span>
          <span class="sf-label">Years of<br>Engineering Progress</span>
        </div>
      </div>
      <div class="split-copy reveal reveal-delay-1">
        <span class="eyebrow">Welcome to <?= e($company['short_name']) ?></span>
        <h2>Welcome to <?= e($company['name']) ?></h2>
        <p>At <?= e($company['name']) ?>, we don't just build structures, extract minerals, or transport goods—we engineer progress. Our foundation is built on professionalism, trust, and an unwavering commitment to delivering top-tier services that enhance communities and industries alike.</p>
        <p>With every project we undertake, we strive to set new standards for quality, efficiency, and integrity.</p>
        <div class="green-rule" aria-hidden="true"></div>
        <ul class="highlight-list">
          <li>
            <span class="hl-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"/><path d="M22 4 12 14l-3-3"/></svg></span>
            <span><strong>One standard of excellence</strong><p>Across construction, mining, real estate and transportation.</p></span>
          </li>
          <li>
            <span class="hl-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10z"/></svg></span>
            <span><strong>Built on trust</strong><p>Transparent operations and lasting partnerships with clients and communities.</p></span>
          </li>
        </ul>
        <a href="<?= $ROOT ?>about" class="link-arrow">Discover BRADTEC <span class="arrow" aria-hidden="true">→</span></a>
      </div>
    </div>
  </section>

  <!-- ============ OUR CORE SERVICES ============ -->
  <section class="section section-soft" id="services">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">What We Do</span>
        <h2>Our Core Services</h2>
        <p><strong style="color:var(--blue-mid)">One company. Four industries. One standard of excellence.</strong></p>
      </div>
      <div class="grid-2">
        <?php foreach ($services as $i => $s):
          $accent = $s['accent'] === 'green' ? 'green' : 'blue';
        ?>
        <article class="card service-card reveal reveal-delay-<?= $i % 3 ?>">
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
              <a href="<?= $ROOT ?>services/<?= e($s['id']) ?>" class="link-arrow">Explore <span class="arrow" aria-hidden="true">→</span></a>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ LOGO-INSPIRED GROWTH ============ -->
  <section class="section" id="growth">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">Our Journey</span>
        <h2>Growth → Progress → Excellence</h2>
        <p>Every BRADTEC division is driven by the same upward standard — moving from professional foundations to world-class results.</p>
      </div>

      <div class="growth-visual reveal" aria-hidden="true">
        <svg class="growth-svg" viewBox="0 0 1000 210" fill="none">
          <defs>
            <linearGradient id="growthLine" x1="0" y1="0" x2="1" y2="0">
              <stop offset="0" stop-color="#11328b"/>
              <stop offset="1" stop-color="#08ac3d"/>
            </linearGradient>
          </defs>
          <path d="M40,190 C 220,178 320,150 430,128 S 660,84 800,60 S 940,34 975,26"
                stroke="url(#growthLine)" stroke-width="6" stroke-linecap="round"/>
          <circle cx="95" cy="178" r="13" fill="#11328b" stroke="#fff" stroke-width="4"/>
          <circle cx="430" cy="128" r="13" fill="#1d44a8" stroke="#fff" stroke-width="4"/>
          <circle cx="800" cy="60" r="13" fill="#08a83c" stroke="#fff" stroke-width="4"/>
          <circle cx="975" cy="26" r="15" fill="#08ac3d" stroke="#fff" stroke-width="4"/>
          <text x="95" y="184" text-anchor="middle" fill="#fff" font-family="Manrope, sans-serif" font-weight="800" font-size="16">1</text>
          <text x="430" y="134" text-anchor="middle" fill="#fff" font-family="Manrope, sans-serif" font-weight="800" font-size="16">2</text>
          <text x="800" y="66" text-anchor="middle" fill="#fff" font-family="Manrope, sans-serif" font-weight="800" font-size="16">3</text>
          <text x="975" y="32" text-anchor="middle" fill="#fff" font-family="Manrope, sans-serif" font-weight="800" font-size="17">4</text>
        </svg>
      </div>

      <div class="growth-steps">
        <div class="growth-step reveal reveal-delay-0">
          <h3>Professionalism</h3>
          <p>Disciplined expertise at the foundation of everything we deliver.</p>
        </div>
        <div class="growth-step reveal reveal-delay-1">
          <h3>Innovation</h3>
          <p>Modern technology and smarter ways of working.</p>
        </div>
        <div class="growth-step reveal reveal-delay-2">
          <h3>Growth</h3>
          <p>Building value for clients, communities and industries.</p>
        </div>
        <div class="growth-step reveal reveal-delay-3">
          <h3>Excellence</h3>
          <p>Setting new standards in everything we do.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ WHY CHOOSE BRADTEC ============ -->
  <section class="section section-soft" id="why">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">The BRADTEC Difference</span>
        <h2>Why Choose BRADTEC?</h2>
        <p>Four reasons partners and communities trust us with their most important projects.</p>
      </div>
      <div class="grid-2">
        <article class="card feature-card reveal">
          <span class="fc-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg></span>
          <h3>Unmatched Expertise</h3>
          <p>Our team consists of experienced professionals dedicated to ensuring every project meets the highest professional standards.</p>
        </article>
        <article class="card feature-card reveal reveal-delay-1">
          <span class="fc-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2 4 6v6c0 5 3.4 8.6 8 10 4.6-1.4 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/></svg></span>
          <h3>Commitment to Trust</h3>
          <p>Transparency and integrity are the backbone of our operations, earning the confidence of businesses, partners, and communities.</p>
        </article>
        <article class="card feature-card reveal reveal-delay-1">
          <span class="fc-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-4 10.5c.8.7 1.3 1.7 1.5 2.5h5c.2-.8.7-1.8 1.5-2.5A6 6 0 0 0 12 3z"/></svg></span>
          <h3>Cutting-Edge Solutions</h3>
          <p>We embrace innovation, advanced techniques, and modern technologies to deliver results that redefine industry expectations.</p>
        </article>
        <article class="card feature-card reveal reveal-delay-2">
          <span class="fc-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 6 6 6 6 0 0 1-6 6 6 6 0 0 0-6 6 6 6 0 0 1 6-6 6 6 0 0 1 6-6 6 6 0 0 0-6-6z"/></svg></span>
          <h3>Sustainability &amp; Responsibility</h3>
          <p>Every step we take is guided by environmental, ethical, and social considerations to create long-term value.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- ============ PROJECTS PREVIEW ============ -->
  <section class="section" id="projects">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">Our Portfolio</span>
        <h2>Our Projects</h2>
        <p>A selection of the work we're proud to deliver across our four industries.</p>
      </div>
      <div class="project-grid">
        <?php foreach ($projects as $i => $p): ?>
        <article class="card project-card reveal reveal-delay-<?= $i % 3 ?>" data-category="<?= e($p['category']) ?>">
          <a href="<?= $ROOT ?>projects" class="pc-link" style="display:block">
            <div class="pc-media">
              <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
              <span class="pc-status <?= $p['status'] === 'Ongoing' || $p['status'] === 'In Progress' ? 'st-green' : ($p['status'] === 'Completed' ? '' : 'st-gray') ?>"><?= e($p['status']) ?></span>
            </div>
            <div class="pc-body">
              <h3><?= e($p['name']) ?></h3>
              <div class="pc-meta">
                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg><?= e($p['location']) ?></span>
                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><?= e($p['date']) ?></span>
              </div>
              <span class="pc-more">View Project →</span>
            </div>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
      <div class="text-center mt-40 reveal">
        <a href="<?= $ROOT ?>projects" class="btn btn-outline">View All Projects</a>
      </div>
    </div>
  </section>

  <!-- ============ STATS ============ -->
  <section class="section section-blue" id="stats">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow eyebrow-light">By The Numbers</span>
        <h2>Measured Achievements</h2>
        <p>Placeholder figures shown here — update them anytime from the admin dashboard.</p>
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

  <!-- ============ CTA ============ -->
  <section class="section">
    <div class="container">
      <div class="cta-band reveal">
        <h2>Ready to engineer progress with us?</h2>
        <p>Whether it's construction, mining, real estate or transportation — let's build, develop, connect and create opportunities together.</p>
        <a href="<?= $ROOT ?>contact" class="btn btn-green">Get In Touch</a>
        <a href="<?= $ROOT ?>services" class="btn btn-outline-light" style="margin-left:12px">Explore Our Services</a>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
