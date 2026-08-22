<?php
$ACTIVE = 'dashboard';
require __DIR__ . '/partials/header.php';

$counts = [
    'projects' => count(json_read('projects.json', []) ?: []),
    'services' => count(json_read('services.json', []) ?: []),
    'products' => count(json_read('products.json', []) ?: []),
    'properties' => count(json_read('properties.json', []) ?: []),
    'inquiries' => count(json_read('inquiries.json', []) ?: []),
    'unread' => 0,
];
$inquiries = json_read('inquiries.json', []);
foreach ($inquiries as $q) { if (empty($q['read'])) $counts['unread']++; }
$recent = array_slice($inquiries, 0, 5);
?>

<div class="stat-cards">
  <div class="stat-card">
    <span class="sc-ico ico-blue"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 15l4-6 4 3 5-8"/></svg></span>
    <div><strong><?= (int)$counts['projects'] ?></strong><span>Projects</span></div>
  </div>
  <div class="stat-card">
    <span class="sc-ico ico-green"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg></span>
    <div><strong><?= (int)$counts['services'] ?></strong><span>Services</span></div>
  </div>
  <div class="stat-card">
    <span class="sc-ico ico-blue"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg></span>
    <div><strong><?= (int)$counts['products'] ?></strong><span>Products</span></div>
  </div>
  <div class="stat-card">
    <span class="sc-ico ico-green"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8M5 10v10h14V10M10 20v-6h4v6"/></svg></span>
    <div><strong><?= (int)$counts['properties'] ?></strong><span>Properties</span></div>
  </div>
  <div class="stat-card">
    <span class="sc-ico ico-blue"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
    <div><strong><?= (int)$counts['inquiries'] ?></strong><span>Inquiries</span></div>
  </div>
</div>

<div class="admin-grid-2">
  <section class="panel">
    <div class="panel-head">
      <h2>Recent Inquiries</h2>
      <a href="messages.php" class="link">View all</a>
    </div>
    <?php if (empty($recent)): ?>
      <p class="panel-empty">No inquiries yet. Inquiries from the contact form will appear here.</p>
    <?php else: ?>
    <ul class="inquiry-mini">
      <?php foreach ($recent as $q): ?>
      <li class="<?= empty($q['read']) ? 'unread' : '' ?>">
        <div class="im-head">
          <strong><?= e($q['name']) ?></strong>
          <span class="im-interest"><?= e($q['interest']) ?></span>
        </div>
        <p><?= e(mb_strimwidth($q['message'], 0, 90, '…')) ?></p>
        <small><?= e($q['created_at']) ?></small>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </section>

  <section class="panel">
    <div class="panel-head">
      <h2>Quick Actions</h2>
    </div>
    <div class="quick-actions">
      <a href="projects.php" class="qa">Add a Project</a>
      <a href="products.php" class="qa">Add a Product / Service</a>
      <a href="properties.php" class="qa">Add a Property</a>
      <a href="company.php" class="qa">Update Company Info</a>
      <a href="messages.php" class="qa"><?= (int)$counts['unread'] ?> Unread Messages</a>
    </div>
    <div class="panel-note">
      <h3>How content updates</h3>
      <p>Everything you save here updates the live website immediately — the front-end reads the same JSON files. The <code>data/</code> folder must remain writable by PHP.</p>
    </div>
  </section>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
