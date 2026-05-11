<?php
$pageIcon = $pageIcon ?? 'bi bi-grid';
$pageActions = $pageActions ?? '';
$crumb = $crumb ?? '';
?>
<div class="page-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <div class="page-pretitle">Barangay TRX Information System</div>
        <h1 class="page-title mb-1"><i class="<?= e($pageIcon) ?> me-2"></i><?= e($pageTitle ?? 'Dashboard') ?></h1>
        <?php if (!empty($pageSubtitle)): ?><p class="page-subtitle mb-1"><?= e($pageSubtitle) ?></p><?php endif; ?>
        <?php if (!empty($crumb)): ?><div class="page-breadcrumb small text-muted"><?= $crumb ?></div><?php endif; ?>
    </div>
    <?php if (!empty($pageActions)): ?><div class="page-actions no-print"><?= $pageActions ?></div><?php endif; ?>
</div>
