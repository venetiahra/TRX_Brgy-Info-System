<?php
$currentPage = $currentPage ?? '';

$links = [
    'dashboard' => [
        'label' => 'Dashboard',
        'url'   => 'dashboard.php',
        'icon'  => 'bi bi-grid-1x2-fill'
    ],
    'residents' => [
        'label' => 'Residents',
        'url'   => 'residents/index.php',
        'icon'  => 'bi bi-people-fill'
    ],
    'certificates' => [
        'label' => 'Request Certificate',
        'url'   => 'certificates/request.php',
        'icon'  => 'bi bi-file-earmark-plus-fill'
    ],
    'history' => [
        'label' => 'Certificate History',
        'url'   => 'certificates/history.php',
        'icon'  => 'bi bi-clock-history'
    ],
    'blotter' => [
        'label' => 'Blotter',
        'url'   => 'blotter/index.php',
        'icon'  => 'bi bi-journal-text'
    ],
    'officials' => [
        'label' => 'Officials Portfolio',
        'url'   => 'officials/index.php',
        'icon'  => 'bi bi-person-badge-fill'
    ],
    'client' => [
        'label' => 'Client Portal',
        'url'   => 'client/index.php',
        'icon'  => 'bi bi-globe2'
    ],
];
?>

<aside class="sidebar" id="appSidebar">
    <div class="brand-panel">
        <img
            src="<?= e(url('assets/img/logo-barangay-trx-inspired.svg')) ?>"
            alt="Barangay TRX logo"
            class="brand-logo-img"
        >
        <div>
            <div class="brand-title">Barangay TRX</div>
            <div class="brand-subtitle">Information System</div>
        </div>
    </div>

    <div class="sidebar-section-label">Main Navigation</div>

    <nav class="sidebar-nav">
        <?php foreach ($links as $key => $item): ?>
            <a class="sidebar-link <?= $currentPage === $key ? 'active' : '' ?>"
               href="<?= e(url($item['url'])) ?>">
                <span class="sidebar-icon">
                    <i class="<?= e($item['icon']) ?>"></i>
                </span>
                <span><?= e($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="support-card">
            <div class="support-title">Branding Applied</div>
          
            <a href="<?= e(url('auth/logout.php')) ?>" class="btn btn-gradient btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </div>
</aside>

<div class="sidebar-backdrop"></div>