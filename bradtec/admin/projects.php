<?php
$ACTIVE = 'projects';
require __DIR__ . '/partials/header.php';
?>
<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Projects Portfolio</h2>
      <p class="panel-sub">Add, edit or remove projects — names, categories, locations, images, status and dates.</p>
    </div>
    <button type="button" class="btn btn-sm" id="addBtn">Add Project</button>
  </div>
  <div id="crudTable"></div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
