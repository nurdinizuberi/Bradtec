<?php
/* Expects $division (array from services.json) and $ROOT */
$company = load_company();
/* Service structured data for this division */
$serviceUrl = rtrim(SITE_URL, '/') . '/services/' . $division['id'];
$divisionDesc = !empty($division['overview']) ? $division['overview'] : $division['hero_sub'];
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": <?= json_script_encode($division['name']) ?>,
  "name": <?= json_script_encode($division['name']) ?>,
  "description": <?= json_script_encode($divisionDesc) ?>,
  "url": <?= json_script_encode($serviceUrl) ?>,
  "provider": {
    "@type": "Organization",
    "name": <?= json_script_encode($company['name']) ?>,
    "url": <?= json_script_encode(rtrim(SITE_URL, '/') . '/') ?>,
    "telephone": <?= json_script_encode($company['phone']) ?>
  },
  "areaServed": "Worldwide",
  "image": <?= json_script_encode($division['image']) ?>
}
</script>
<section class="page-hero">
  <div class="bg-img" style="background-image:url('<?= e($division['image']) ?>')" aria-hidden="true"></div>
  <div class="container">
    <p class="crumb"><a href="<?= $ROOT ?>">Home</a> <span class="sep">/</span> <a href="<?= $ROOT ?>services">Services</a> <span class="sep">/</span> <?= e($division['name']) ?></p>
    <h1><?= e($division['hero_heading']) ?></h1>
    <p><?= e($division['hero_sub']) ?></p>
    <div class="accent-bar" aria-hidden="true"></div>
    <p class="script-tagline"><?= e($company['tagline']) ?></p>
  </div>
</section>
