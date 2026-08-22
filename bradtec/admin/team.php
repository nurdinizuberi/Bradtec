<?php
$ACTIVE = 'team';
require __DIR__ . '/partials/header.php';
?>
<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Leadership Team</h2>
      <p class="panel-sub">Manage team members shown on the About page. Add photos, roles, bios and social links.</p>
    </div>
    <button type="button" class="btn btn-green btn-sm" id="addBtn">+ Add Member</button>
  </div>
  <div class="table-wrap" id="crudTable"><p class="panel-empty">Loading…</p></div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
