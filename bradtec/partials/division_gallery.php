<?php
/* Expects $division (array from services.json) and $ROOT.
   Shows the division's extra photos (added in admin) as a lightbox gallery.
   Renders nothing when the division has no gallery images. */
$gallery = isset($division['gallery']) && is_array($division['gallery'])
    ? array_values(array_filter($division['gallery']))
    : [];
if (empty($gallery)) {
    return;
}
?>
<section class="section section-soft" id="gallery">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow"><?= e($division['name']) ?> Gallery</span>
      <h2>Our Work in Pictures</h2>
      <p>A closer look at our <?= strtolower(e($division['name'])) ?> projects and operations.</p>
    </div>
    <div class="gallery-grid">
      <?php foreach ($gallery as $i => $src): ?>
      <button type="button" class="gallery-item reveal reveal-delay-<?= $i % 3 ?>" data-lightbox='<?= e(json_encode($gallery)) ?>' aria-label="View photo <?= $i + 1 ?>">
        <img src="<?= e($src) ?>" alt="<?= e($division['name']) ?> photo <?= $i + 1 ?>" loading="lazy">
      </button>
      <?php endforeach; ?>
    </div>
  </div>
</section>
