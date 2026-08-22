<?php
$ACTIVE = 'products';
require __DIR__ . '/partials/header.php';
?>
<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Products &amp; Services Catalogue</h2>
      <p class="panel-sub">Add, edit or remove catalogue items across all four divisions (construction, mining, real estate, transportation).</p>
    </div>
    <button type="button" class="btn btn-sm" id="addBtn">Add Product</button>
  </div>
  <div class="table-toolbar">
    <select id="divisionFilter" aria-label="Filter by division">
      <option value="">All Divisions</option>
      <option value="construction">Construction</option>
      <option value="mining">Mining</option>
      <option value="real-estate">Real Estate</option>
      <option value="transportation">Transportation</option>
    </select>
  </div>
  <div id="crudTable"></div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
