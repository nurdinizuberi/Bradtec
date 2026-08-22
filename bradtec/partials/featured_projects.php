<?php
/* Expects $division (array) and $ROOT */
$category = $division['name'] === 'Transportation' ? 'Transportation' : $division['name'];
$all = json_read('projects.json', []);
$featured = array_values(array_filter($all, function ($p) use ($category) {
    return isset($p['category']) && $p['category'] === $category;
}));
$featured = array_slice($featured, 0, 3);
?>
<?php if (!empty($featured)): ?>
<section class="section section-soft" id="featured-projects">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Featured Work</span>
      <h2>Featured Projects</h2>
      <p>A look at recent <?= strtolower(e($division['name'])) ?> projects delivered by BRADTEC.</p>
    </div>
    <div class="project-grid">
      <?php foreach ($featured as $i => $p): ?>
      <article class="card project-card reveal reveal-delay-<?= $i % 3 ?>">
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
  </div>
</section>
<?php endif; ?>
