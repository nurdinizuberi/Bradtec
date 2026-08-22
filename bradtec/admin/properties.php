<?php
$ACTIVE = 'properties';
require __DIR__ . '/partials/header.php';
?>
<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Property Listings</h2>
      <p class="panel-sub">Manage the real estate catalogue — homes, offices, land and more, with full listing details.</p>
    </div>
    <button type="button" class="btn btn-sm" id="addBtn">Add Property</button>
  </div>
  <div id="crudTable"></div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
