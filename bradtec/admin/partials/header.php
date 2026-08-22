<?php
require_once __DIR__ . '/../../api/config.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
/* Regenerate session ID periodically to prevent session fixation */
if (!isset($_SESSION['bradtec_last_regen'])) {
    $_SESSION['bradtec_last_regen'] = time();
} elseif (time() - $_SESSION['bradtec_last_regen'] > 300) {
    session_regenerate_id(true);
    $_SESSION['bradtec_last_regen'] = time();
}
$ACTIVE = isset($ACTIVE) ? $ACTIVE : '';
$company = load_company();

$NAV = [
    'dashboard' => ['label' => 'Dashboard', 'href' => 'index.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>'],
    'services' => ['label' => 'Services', 'href' => 'services.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg>'],
    'products' => ['label' => 'Products', 'href' => 'products.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>'],
    'properties' => ['label' => 'Properties', 'href' => 'properties.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8M5 10v10h14V10M10 20v-6h4v6"/></svg>'],
    'projects' => ['label' => 'Projects', 'href' => 'projects.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 15l4-6 4 3 5-8"/></svg>'],
    'messages' => ['label' => 'Messages', 'href' => 'messages.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>'],
    'team' => ['label' => 'Team', 'href' => 'team.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/></svg>'],
    'company' => ['label' => 'Company Info', 'href' => 'company.php', 'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3h0a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5h0a1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9v0a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></svg>'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title><?= e(($NAV[$ACTIVE]['label'] ?? 'Admin') . ' — BRADTEC Admin') ?></title>
<link rel="icon" type="image/svg+xml" href="../assets/favicon.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body data-admin-page="<?= e($ACTIVE) ?>">
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a href="index.php" class="admin-brand">
      <img src="../assets/images/bradt.png" alt="BRADTEC Logo" class="admin-brand-logo">
      <span>
        <strong><?= e($company['short_name']) ?> Admin</strong>
        <small><?= e($company['tagline']) ?></small>
      </span>
    </a>
    <nav class="admin-nav" aria-label="Admin navigation">
      <?php foreach ($NAV as $key => $item): ?>
      <a href="<?= e($item['href']) ?>" class="admin-nav-link<?= $ACTIVE === $key ? ' active' : '' ?>">
        <?= $item['icon'] ?><span><?= e($item['label']) ?></span>
      </a>
      <?php endforeach; ?>
      <a href="../" class="admin-nav-link" target="_blank" rel="noopener noreferrer"><?= '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>' ?><span>View Site</span></a>
    </nav>
    <div class="admin-sidebar-foot">
      <a href="#" id="logoutBtn" class="admin-nav-link"><?= '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>' ?><span>Logout</span></a>
    </div>
  </aside>
  <div class="admin-main">
    <header class="admin-topbar">
      <button type="button" class="admin-menu-toggle" id="adminMenuToggle" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
      <h1><?= e($NAV[$ACTIVE]['label'] ?? 'Admin') ?></h1>
      <a href="../" target="_blank" rel="noopener noreferrer" class="btn btn-sm">View Website ↗</a>
    </header>
    <main class="admin-content">
