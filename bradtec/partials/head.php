<?php
require_once __DIR__ . '/../api/config.php';
$company = load_company();

$PAGE_TITLE  = isset($PAGE_TITLE) ? $PAGE_TITLE : SITE_NAME . ' — ' . SITE_TAGLINE;
$PAGE_DESC   = isset($PAGE_DESC) ? $PAGE_DESC : $company['description'];

/* Canonical URL — explicit per-page override, otherwise derived from the clean URL. */
if (!isset($PAGE_CANONICAL) || $PAGE_CANONICAL === '') {
    $uriPath = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $uriPath = preg_replace('#/index\.php$#', '/', $uriPath);
    if ($uriPath !== '/' && substr($uriPath, -1) === '/') {
        $uriPath = rtrim($uriPath, '/');
    }
    $PAGE_CANONICAL = rtrim(SITE_URL, '/') . ($uriPath !== '' ? $uriPath : '/');
}

$PAGE_OG_IMAGE = isset($PAGE_OG_IMAGE) ? $PAGE_OG_IMAGE
    : 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = isset($PAGE_CRUMBS) ? (array)$PAGE_CRUMBS : [];
$ROOT = isset($ROOT) ? $ROOT : '';

/* Brand assets for schema / icons */
$logoFile = is_file(__DIR__ . '/../assets/images/logo.png') ? 'logo.png' : 'bradt.png';
$logoUrl  = rtrim(SITE_URL, '/') . '/assets/images/' . $logoFile;
$logoLocal = $ROOT . 'assets/images/' . $logoFile;

$ogType = (isset($PAGE_OG_TYPE) && $PAGE_OG_TYPE !== '') ? $PAGE_OG_TYPE : 'website';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($PAGE_TITLE) ?></title>
<meta name="description" content="<?= e($PAGE_DESC) ?>">
<meta name="robots" content="<?= !empty($PAGE_NOINDEX) ? 'noindex, nofollow' : 'index, follow, max-image-preview:large' ?>">
<link rel="canonical" href="<?= e($PAGE_CANONICAL) ?>">

<!-- Open Graph -->
<meta property="og:type" content="<?= e($ogType) ?>">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($PAGE_TITLE) ?>">
<meta property="og:description" content="<?= e($PAGE_DESC) ?>">
<meta property="og:url" content="<?= e($PAGE_CANONICAL) ?>">
<meta property="og:image" content="<?= e($PAGE_OG_IMAGE) ?>">
<meta property="og:locale" content="en_US">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($PAGE_TITLE) ?>">
<meta name="twitter:description" content="<?= e($PAGE_DESC) ?>">
<meta name="twitter:image" content="<?= e($PAGE_OG_IMAGE) ?>">
<meta name="theme-color" content="#11328b">

<link rel="icon" type="image/svg+xml" href="<?= $ROOT ?>assets/favicon.svg">
<link rel="apple-touch-icon" href="<?= e($logoLocal) ?>">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=Caveat:wght@600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<link rel="preload" href="<?= $ROOT ?>assets/css/main.css" as="style">
<link rel="stylesheet" href="<?= $ROOT ?>assets/css/main.css">

<!-- Structured data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": <?= json_script_encode($company['name']) ?>,
  "url": <?= json_script_encode(rtrim(SITE_URL, '/') . '/') ?>,
  "logo": <?= json_script_encode($logoUrl) ?>,
  "slogan": <?= json_script_encode($company['tagline']) ?>,
  "description": <?= json_script_encode($company['description']) ?>,
  "email": <?= json_script_encode($company['email']) ?>,
  "telephone": <?= json_script_encode($company['phone']) ?>,
  "address": {
    "@type": "PostalAddress",
    "streetAddress": <?= json_script_encode($company['address']) ?>,
    "addressCountry": "TZ"
  },
  "sameAs": [
    <?php
    $socialLinks = array_filter(array_values($company['social']), fn($u) => is_string($u) && $u !== '' && $u !== '#');
    foreach ($socialLinks as $i => $u): ?><?= $i > 0 ? ',' : '' ?><?= json_script_encode($u) ?><?php endforeach; ?>
  ]
}
</script>

<?php if (!empty($PAGE_CRUMBS)): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home", "item": <?= json_script_encode(rtrim(SITE_URL, '/') . '/') ?>},
    <?php foreach ($PAGE_CRUMBS as $i => $c): ?><?= $i > 0 ? ',' : '' ?>{"@type": "ListItem", "position": <?= $i + 2 ?>, "name": <?= json_script_encode($c['name']) ?>, "item": <?= json_script_encode(rtrim(SITE_URL, '/') . '/' . ltrim($c['url'], '/')) ?>}<?php endforeach; ?>
  ]
}
</script>
<?php endif; ?>
</head>
