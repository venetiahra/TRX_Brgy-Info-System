<?php
require_once __DIR__ . '/../config/app.php';
$pageTitle = $pageTitle ?? APP_NAME;
$showNavbar = $showNavbar ?? true;
$bodyClass = $bodyClass ?? '';
$flash = get_flash();
$useAppLayout = $showNavbar && isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= e(url('assets/favicon.ico')) ?>">
    <link rel="shortcut icon" href="<?= e(url('assets/favicon.ico')) ?>">
    <link rel="apple-touch-icon" href="<?= e(url('assets/img/favicon-preview.png')) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
</head>
<body class="<?= e($bodyClass) ?> <?= $useAppLayout ? 'app-shell-body' : '' ?>">
<?php if ($useAppLayout): ?>
    <div class="app-shell">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="app-main">
            <header class="topbar">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-icon d-lg-none" type="button" data-sidebar-toggle><i class="bi bi-list"></i></button>
                    <div>
                        <div class="topbar-caption">Welcome back</div>
                        <div class="topbar-title"><?= e($_SESSION['user']['fullname'] ?? 'Barangay Staff') ?></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="search-shell d-none d-md-flex align-items-center">
                        <i class="bi bi-search me-2"></i>
                        <input type="text" class="form-control" value="Barangay TRX" readonly>
                    </div>
                    <div class="profile-chip">
                        <div class="profile-avatar"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div class="profile-name"><?= e($_SESSION['user']['fullname'] ?? 'Barangay User') ?></div>
                            <div class="profile-role"><?= e($_SESSION['user']['role'] ?? 'staff') ?></div>
                        </div>
                    </div>
                </div>
            </header>
            <main class="content-area">
                <?php if ($flash): ?>
                    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
                        <?= e($flash['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
<?php else: ?>
    <main>
        <?php if ($flash): ?>
            <div class="container pt-4">
                <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
                    <?= e($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>
<?php endif; ?>
