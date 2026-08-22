<?php $ROOT = isset($ROOT) ? $ROOT : ''; ?>
<script src="<?= $ROOT ?>assets/js/main.js" defer></script>
<?php if (!empty($PAGE_SCRIPTS)): foreach ((array)$PAGE_SCRIPTS as $s): ?>
<script src="<?= $ROOT ?>assets/js/<?= e($s) ?>" defer></script>
<?php endforeach; endif; ?>
