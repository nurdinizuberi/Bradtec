<?php $ROOT = isset($ROOT) ? $ROOT : ''; ?>
<!-- Google Tag Manager (moved to body for non-render-blocking) -->
<script defer src="https://www.googletagmanager.com/gtm.js?id=GTM-M4MNX9NR"></script>
<script src="<?= $ROOT ?>assets/js/main.js" defer></script>
<?php if (!empty($PAGE_SCRIPTS)): foreach ((array)$PAGE_SCRIPTS as $s): ?>
<script src="<?= $ROOT ?>assets/js/<?= e($s) ?>" defer></script>
<?php endforeach; endif; ?>
