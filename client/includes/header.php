<?php
require_once __DIR__ . '/../../config/app.php';
$pageTitle = $pageTitle ?? APP_NAME;
$activePage = $activePage ?? 'home';
$flash = get_flash();
$faviconUrl = url('assets/favicon.ico') . '?v=2';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= e($faviconUrl) ?>">
    <link rel="shortcut icon" href="<?= e($faviconUrl) ?>">
    <link rel="apple-touch-icon" href="<?= e($faviconUrl) ?>">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= e(url('assets/css/client-style.css')) ?>">
</head>
<body class="client-body">

<nav class="navbar navbar-expand-lg client-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?= e(url('client/index.php')) ?>">
            <img src="<?= e(url('assets/img/logo-barangay-trx-inspired.svg')) ?>" alt="Barangay TRX logo" class="client-logo">
            <div>
                <div class="client-brand-title">Barangay TRX</div>
                <div class="client-brand-subtitle">Client Services Portal</div>
            </div>
        </a>

        <button class="navbar-toggler client-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" aria-controls="clientNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="clientNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="<?= e(url('client/index.php')) ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'request' ? 'active' : '' ?>" href="<?= e(url('client/request.php')) ?>">Request Certificate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'history' ? 'active' : '' ?>" href="<?= e(url('client/history.php')) ?>">Track Request</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'blotter' ? 'active' : '' ?>" href="<?= e(url('client/blotter.php')) ?>">Blotter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'officials' ? 'active' : '' ?>" href="<?= e(url('client/officials.php')) ?>">Officials</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-client-gradient btn-sm" href="<?= e(url('auth/login.php')) ?>">Admin Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 client-city-overlay rounded-4">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>