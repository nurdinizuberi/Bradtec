<?php
$ROOT = './';
$PAGE_TITLE = 'Our Projects — BRADTEC CO. LTD';
$PAGE_DESC = 'Explore BRADTEC projects across construction, mining, real estate and transportation — delivered with one standard of excellence.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Projects', 'url' => '/projects']];
$PAGE_SCRIPTS = ['projects.js'];
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';

$projects = json_read('projects.json', []);
$categories = ['All', 'Construction', 'Mining', 'Real Estate', 'Transportation'];
?>

<main>
  <section class="page-hero">
    <div class="bg-img" style="background-image:url('https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1920&q=80')" aria-hidden="true"></div>
    <div class="container">
      <p class="crumb"><a href="<?= $ROOT ?>">Home</a> <span class="sep">/</span> Projects</p>
      <h1>Our Projects</h1>
      <p>Work we're proud of across construction, mining, real estate and transportation — each delivered to the BRADTEC standard.</p>
      <div class="accent-bar" aria-hidden="true"></div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="filter-bar reveal" role="tablist" aria-label="Filter projects by category">
        <?php foreach ($categories as $i => $c): ?>
        <button type="button" class="filter-btn<?= $i === 0 ? ' active' : '' ?>" data-filter="<?= e($c === 'All' ? '' : $c) ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"><?= e($c) ?></button>
        <?php endforeach; ?>
      </div>

      <div class="project-grid" id="projectGrid">
        <?php foreach ($projects as $i => $p): ?>
        <article class="card project-card reveal in-view" data-category="<?= e($p['category']) ?>">
          <button type="button" class="pc-link" data-project="<?= e($p['id']) ?>" style="display:block;width:100%;border:none;background:none;padding:0;text-align:left;cursor:pointer" aria-label="View project details: <?= e($p['name']) ?>">
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
          </button>
        </article>
        <?php endforeach; ?>
      </div>

      <div class="empty-state card" id="projectEmpty" style="display:none">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg>
        <h3>No projects in this category yet</h3>
        <p>New projects are added as they're delivered.</p>
      </div>
    </div>
  </section>

  <section class="section section-soft">
    <div class="container">
      <div class="cta-band reveal">
        <h2>Have a project in mind?</h2>
        <p>From construction and mining to real estate and transportation — let's plan it together.</p>
        <a href="<?= $ROOT ?>contact" class="btn btn-green">Start a Conversation</a>
      </div>
    </div>
  </section>
</main>

<script>window.BRADTEC_ROOT = '<?= $ROOT ?>';</script>
<script type="application/json" id="projectsData"><?= json_script_encode($projects) ?></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
