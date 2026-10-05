<?php
$ACTIVE = 'services';
require __DIR__ . '/partials/header.php';
?>
<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Business Divisions</h2>
      <p class="panel-sub">Edit the five BRADTEC divisions — names, taglines, page headings, images and accents.</p>
    </div>
    <button type="button" class="btn btn-sm" id="addBtn">Add Division</button>
  </div>
  <div id="crudTable"></div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
